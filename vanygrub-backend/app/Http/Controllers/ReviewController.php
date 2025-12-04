<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerReview;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Show the review form for a specific review token
     */
    public function show($token)
    {
        // Check if this token exists in the customer_reviews table
        $existingReview = CustomerReview::where('review_token', $token)->first();
        
        // If token doesn't exist in database - invalid barcode
        if (!$existingReview) {
            return view('pages.review-invalid', compact('token'));
        }
        
        // If token exists and review_text is already filled - review completed
        if (!empty($existingReview->review_text)) {
            return view('pages.review-completed', compact('existingReview', 'token'));
        }
        
        // Token exists but review_text is empty - show form to fill review
        // Try to find associated order
        $order = null;
        if ($existingReview->order_id) {
            $order = Order::find($existingReview->order_id);
        }
        
        // Create mock order if no real order found
        if (!$order) {
            $order = (object) [
                'order_number' => $token,
                'created_at' => $existingReview->created_at ?? now()
            ];
        }
        
        return view('pages.review-form', compact('order', 'existingReview', 'token'));
    }

    /**
     * Store the customer review
     */
    public function store(Request $request, $token)
    {
        // Try to find the order by token
        $order = Order::where('order_number', $token)
            ->first();

        // Check if review already exists for this token
        $existingReview = CustomerReview::where('review_token', $token)->first();
        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Review sudah pernah diberikan untuk kode ini'
            ], 400);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'review_text' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
        ], [
            'customer_name.required' => 'Nama harus diisi',
            'review_text.required' => 'Review harus diisi',
            'rating.required' => 'Rating harus dipilih',
            'rating.min' => 'Rating minimal 1 bintang',
            'rating.max' => 'Rating maksimal 5 bintang',
            'photo.image' => 'File harus berupa gambar',
            'photo.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'photo.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $photoUrl = null;

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $filename = 'review_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();

                // Create reviews directory if not exists
                $reviewsPath = storage_path('app/public/reviews');
                if (!file_exists($reviewsPath)) {
                    mkdir($reviewsPath, 0755, true);
                }

                // Resize and optimize image using native PHP GD
                $sourceImage = null;
                $imageType = exif_imagetype($photo->getPathname());

                switch ($imageType) {
                    case IMAGETYPE_JPEG:
                        $sourceImage = imagecreatefromjpeg($photo->getPathname());
                        break;
                    case IMAGETYPE_PNG:
                        $sourceImage = imagecreatefrompng($photo->getPathname());
                        break;
                    case IMAGETYPE_GIF:
                        $sourceImage = imagecreatefromgif($photo->getPathname());
                        break;
                    default:
                        throw new \Exception('Unsupported image type');
                }

                if ($sourceImage) {
                    // Get original dimensions
                    $originalWidth = imagesx($sourceImage);
                    $originalHeight = imagesy($sourceImage);

                    // Calculate new dimensions (max 400x400 while maintaining aspect ratio)
                    $maxSize = 400;
                    if ($originalWidth > $originalHeight) {
                        $newWidth = $maxSize;
                        $newHeight = ($originalHeight / $originalWidth) * $maxSize;
                    } else {
                        $newHeight = $maxSize;
                        $newWidth = ($originalWidth / $originalHeight) * $maxSize;
                    }

                    // Create new image
                    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

                    // Preserve transparency for PNG
                    if ($imageType == IMAGETYPE_PNG) {
                        imagealphablending($resizedImage, false);
                        imagesavealpha($resizedImage, true);
                    }

                    // Resize image
                    imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

                    // Save resized image
                    $fullPath = $reviewsPath . '/' . $filename;
                    switch ($imageType) {
                        case IMAGETYPE_JPEG:
                            imagejpeg($resizedImage, $fullPath, 85);
                            break;
                        case IMAGETYPE_PNG:
                            imagepng($resizedImage, $fullPath);
                            break;
                        case IMAGETYPE_GIF:
                            imagegif($resizedImage, $fullPath);
                            break;
                    }

                    // Clean up memory
                    imagedestroy($sourceImage);
                    imagedestroy($resizedImage);

                    $photoUrl = Storage::url('reviews/' . $filename);
                } else {
                    // Fallback: just move the file without resizing
                    $path = $photo->storeAs('reviews', $filename, 'public');
                    $photoUrl = Storage::url($path);
                }
            }

            // Create review
            $review = CustomerReview::create([
                'order_id' => $order && isset($order->id) ? $order->id : null, // Allow null for manual reviews
                'customer_name' => $request->customer_name,
                'customer_email' => 'guest@vanygrub.com', // Default email for manual reviews
                'photo_url' => $photoUrl ?? '',
                'review_text' => $request->review_text,
                'rating' => $request->rating,
                'review_token' => $token, // Use the provided token
                'is_approved' => false, // Need admin approval
                'is_featured' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Terima kasih! Review Anda telah berhasil dikirim dan akan ditinjau oleh admin.',
                'review_id' => $review->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan review. Silakan coba lagi.'
            ], 500);
        }
    }
}
