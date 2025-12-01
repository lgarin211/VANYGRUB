<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Display a listing of gallery items
     */
    public function index(Request $request): JsonResponse
    {
        $query = Gallery::query();

        // Filter by type if provided
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Search by title or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $galleries = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'message' => 'Gallery items retrieved successfully',
            'data' => $galleries
        ]);
    }

    /**
     * Store a newly created gallery item
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:50240', // Max 50MB
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'nullable|string|max:100',
            'alt_text' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $fileType = $this->getFileType($file);
            
            // Validate file type
            if (!$this->isValidFileType($file, $fileType)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid file type. Only images, videos, and documents are allowed.'
                ], 422);
            }

            // Generate unique filename
            $fileName = $this->generateFileName($file);
            
            // Define storage path based on file type
            $storagePath = "gallery/{$fileType}s/" . date('Y/m');
            
            // Store file
            $filePath = $file->storeAs($storagePath, $fileName, 'public');
            
            // Get file info
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();
            
            // Create gallery record
            $gallery = Gallery::create([
                'title' => $request->title ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'description' => $request->description,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_url' => Storage::url($filePath),
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'type' => $fileType,
                'category' => $request->category ?: 'uncategorized',
                'alt_text' => $request->alt_text,
                'uploaded_by' => auth()->id(), // If using authentication
            ]);

            // Generate thumbnail for images and videos
            if ($fileType === 'image') {
                $this->generateImageThumbnail($gallery);
            } elseif ($fileType === 'video') {
                $this->generateVideoThumbnail($gallery);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'File uploaded successfully',
                'data' => $gallery->fresh()
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified gallery item
     */
    public function show(string $id): JsonResponse
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gallery item not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Gallery item retrieved successfully',
            'data' => $gallery
        ]);
    }

    /**
     * Update the specified gallery item
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gallery item not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'nullable|string|max:100',
            'alt_text' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $gallery->update($request->only(['title', 'description', 'category', 'alt_text']));

        return response()->json([
            'status' => 'success',
            'message' => 'Gallery item updated successfully',
            'data' => $gallery->fresh()
        ]);
    }

    /**
     * Remove the specified gallery item
     */
    public function destroy(string $id): JsonResponse
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gallery item not found'
            ], 404);
        }

        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($gallery->file_path)) {
                Storage::disk('public')->delete($gallery->file_path);
            }

            // Delete thumbnail if exists
            if ($gallery->thumbnail_path && Storage::disk('public')->exists($gallery->thumbnail_path)) {
                Storage::disk('public')->delete($gallery->thumbnail_path);
            }

            // Delete database record
            $gallery->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Gallery item deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Delete failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload multiple files at once
     */
    public function bulkUpload(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required|array',
            'files.*' => 'file|max:50240',
            'category' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $uploadedFiles = [];
        $errors = [];

        foreach ($request->file('files') as $index => $file) {
            try {
                $fileType = $this->getFileType($file);
                
                if (!$this->isValidFileType($file, $fileType)) {
                    $errors[] = "File {$index}: Invalid file type";
                    continue;
                }

                $fileName = $this->generateFileName($file);
                $storagePath = "gallery/{$fileType}s/" . date('Y/m');
                $filePath = $file->storeAs($storagePath, $fileName, 'public');
                
                $gallery = Gallery::create([
                    'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_url' => Storage::url($filePath),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'type' => $fileType,
                    'category' => $request->category ?: 'uncategorized',
                    'uploaded_by' => auth()->id(),
                ]);

                // Generate thumbnails
                if ($fileType === 'image') {
                    $this->generateImageThumbnail($gallery);
                } elseif ($fileType === 'video') {
                    $this->generateVideoThumbnail($gallery);
                }

                $uploadedFiles[] = $gallery;

            } catch (\Exception $e) {
                $errors[] = "File {$index}: " . $e->getMessage();
            }
        }

        return response()->json([
            'status' => count($errors) === 0 ? 'success' : 'partial_success',
            'message' => count($uploadedFiles) . ' files uploaded successfully' . (count($errors) > 0 ? ', ' . count($errors) . ' failed' : ''),
            'data' => $uploadedFiles,
            'errors' => $errors
        ], count($errors) === 0 ? 201 : 207);
    }

    /**
     * Get file type based on mime type
     */
    private function getFileType($file): string
    {
        $mimeType = $file->getMimeType();
        
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } else {
            return 'document';
        }
    }

    /**
     * Check if file type is valid
     */
    private function isValidFileType($file, $type): bool
    {
        $allowedMimes = [
            'image' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'],
            'video' => ['video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/webm'],
            'audio' => ['audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a'],
            'document' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
        ];

        return in_array($file->getMimeType(), $allowedMimes[$type] ?? []);
    }

    /**
     * Generate unique filename
     */
    private function generateFileName($file): string
    {
        $extension = $file->getClientOriginalExtension();
        return Str::uuid() . '.' . $extension;
    }

    /**
     * Generate thumbnail for image
     */
    private function generateImageThumbnail($gallery)
    {
        // Implementation for image thumbnail generation
        // You can use intervention/image package for this
    }

    /**
     * Generate thumbnail for video
     */
    private function generateVideoThumbnail($gallery)
    {
        // Implementation for video thumbnail generation
        // You can use FFmpeg for this
    }
}