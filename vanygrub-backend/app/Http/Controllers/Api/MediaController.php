<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Upload single media file
     */
    public function uploadSingle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // 10MB max
            'type' => 'required|in:image,video,document',
            'folder' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $type = $request->input('type', 'image');
            $folder = $request->input('folder', 'general');

            // Validate file type based on category
            $this->validateFileType($file, $type);

            // Generate unique filename
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug($originalName) . '-' . time() . '.' . $extension;

            // Store file
            $path = $file->storeAs("media/{$type}/{$folder}", $filename, 'public');

            // Generate URL
            $url = Storage::url($path);

            // Save to database
            $media = Media::create([
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'url' => asset($url),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'type' => $type,
                'folder' => $folder
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => [
                    'id' => $media->id,
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'url' => asset($url),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'type' => $type,
                    'folder' => $folder,
                    'formatted_size' => $media->formatted_size
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload multiple media files
     */
    public function uploadMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required|array|max:10',
            'files.*' => 'required|file|max:10240', // 10MB max per file
            'type' => 'required|in:image,video,document',
            'folder' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $files = $request->file('files');
            $type = $request->input('type', 'image');
            $folder = $request->input('folder', 'general');
            $uploadedFiles = [];

            foreach ($files as $file) {
                // Validate file type
                $this->validateFileType($file, $type);

                // Generate unique filename
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $filename = Str::slug($originalName) . '-' . time() . '-' . Str::random(4) . '.' . $extension;

                // Store file
                $path = $file->storeAs("media/{$type}/{$folder}", $filename, 'public');

                // Generate URL
                $url = Storage::url($path);

                // Save to database
                $media = Media::create([
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'url' => asset($url),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'type' => $type,
                    'folder' => $folder
                ]);

                $uploadedFiles[] = [
                    'id' => $media->id,
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'url' => asset($url),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'type' => $type,
                    'folder' => $folder,
                    'formatted_size' => $media->formatted_size
                ];

                // Small delay to ensure unique timestamps
                usleep(1000);
            }

            return response()->json([
                'success' => true,
                'message' => count($uploadedFiles) . ' files uploaded successfully',
                'data' => $uploadedFiles
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete media file
     */
    public function deleteMedia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $path = $request->input('path');

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);

                return response()->json([
                    'success' => true,
                    'message' => 'File deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug file info - check if file format is preserved
     */
    public function debugFileInfo(Request $request, $id)
    {
        try {
            $media = Media::findOrFail($id);

            // Check if file exists
            $exists = Storage::disk('public')->exists($media->path);

            // Get file info from storage
            $storageInfo = [];
            if ($exists) {
                $storageInfo = [
                    'size' => Storage::disk('public')->size($media->path),
                    'mime_type' => Storage::disk('public')->mimeType($media->path),
                    'last_modified' => Storage::disk('public')->lastModified($media->path),
                ];
            }

            // Get actual file path for system check
            $actualPath = storage_path('app/public/' . $media->path);
            $fileInfo = [];
            if (file_exists($actualPath)) {
                $fileInfo = [
                    'actual_size' => filesize($actualPath),
                    'actual_mime' => mime_content_type($actualPath),
                    'is_readable' => is_readable($actualPath),
                    'file_extension' => pathinfo($actualPath, PATHINFO_EXTENSION),
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'media_record' => $media,
                    'file_exists' => $exists,
                    'storage_info' => $storageInfo,
                    'actual_file_info' => $fileInfo,
                    'full_path' => $actualPath
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Debug failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get media files by type and folder
     */
    public function getMedia(Request $request)
    {
        $type = $request->input('type', 'image');
        $folder = $request->input('folder', 'general');
        $page = (int) $request->input('page', 1);
        $perPage = min((int) $request->input('per_page', 20), 50);

        try {
            $directory = "media/{$type}/{$folder}";

            if (!Storage::disk('public')->exists($directory)) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'pagination' => [
                        'current_page' => 1,
                        'per_page' => $perPage,
                        'total' => 0,
                        'total_pages' => 0
                    ]
                ]);
            }

            $files = collect(Storage::disk('public')->files($directory))
                ->map(function ($filePath) {
                    $filename = basename($filePath);
                    return [
                        'filename' => $filename,
                        'path' => $filePath,
                        'url' => asset(Storage::url($filePath)),
                        'size' => Storage::disk('public')->size($filePath),
                        'last_modified' => Storage::disk('public')->lastModified($filePath),
                        'mime_type' => Storage::disk('public')->mimeType($filePath)
                    ];
                })
                ->sortByDesc('last_modified')
                ->values();

            $total = $files->count();
            $totalPages = ceil($total / $perPage);
            $offset = ($page - 1) * $perPage;

            $paginatedFiles = $files->slice($offset, $perPage)->values();

            return response()->json([
                'success' => true,
                'data' => $paginatedFiles,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => $totalPages
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get media: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate file type based on category
     */
    private function validateFileType($file, $type)
    {
        $allowedTypes = [
            'image' => ['jpeg', 'jpg', 'png', 'gif', 'webp', 'svg'],
            'video' => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'],
            'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt']
        ];

        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedTypes[$type] ?? [])) {
            throw new \InvalidArgumentException("File type '{$extension}' is not allowed for category '{$type}'");
        }

        // Additional MIME type validation
        $allowedMimes = [
            'image' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'],
            'video' => ['video/mp4', 'video/avi', 'video/quicktime', 'video/x-ms-wmv', 'video/x-flv', 'video/webm'],
            'document' => [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'text/plain'
            ]
        ];

        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $allowedMimes[$type] ?? [])) {
            throw new \InvalidArgumentException("MIME type '{$mimeType}' is not allowed for category '{$type}'");
        }
    }
}
