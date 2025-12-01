<?php

namespace App\Http\Controllers;

use App\Models\CustomerReview;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * Show review form by token
     */
    public function showReviewForm($token)
    {
        $review = $this->reviewService->getReviewByToken($token);

        if (!$review) {
            return response()->json([
                'error' => 'Review token tidak valid atau sudah expired'
            ], 404);
        }

        // If review already submitted
        if ($review->photo_url && $review->review_text) {
            return response()->json([
                'message' => 'Review sudah pernah disubmit',
                'review' => $review
            ]);
        }

        return response()->json([
            'review_token' => $token,
            'order' => $review->order,
            'customer_name' => $review->customer_name,
            'order_items' => $review->order ? $review->order->items : []
        ]);
    }

    /**
     * Submit customer review
     */
    public function submitReview(Request $request, $token)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
            'review_text' => 'required|string|min:10|max:1000',
            'rating' => 'required|integer|between:1,5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Data tidak valid',
                'messages' => $validator->errors()
            ], 422);
        }

        $result = $this->reviewService->submitReview($token, $request->all());

        if (!$result) {
            return response()->json([
                'error' => 'Review token tidak valid'
            ], 404);
        }

        return response()->json([
            'message' => 'Review berhasil disubmit! Terima kasih atas feedback Anda.',
            'success' => true
        ]);
    }

    /**
     * Get approved reviews for public display
     */
    public function getApprovedReviews(Request $request)
    {
        $limit = $request->get('limit', null);
        $reviews = $this->reviewService->getApprovedReviews($limit);

        return response()->json([
            'reviews' => $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'customer_name' => $review->customer_name,
                    'photo_url' => $review->photo_url ? asset('storage/' . $review->photo_url) : null,
                    'review_text' => $review->review_text,
                    'rating' => $review->rating,
                    'order_number' => $review->order ? $review->order->order_number : 'N/A',
                    'created_at' => $review->created_at,
                    'formatted_date' => $review->created_at->format('d M Y')
                ];
            })
        ]);
    }

    /**
     * Get featured reviews
     */
    public function getFeaturedReviews()
    {
        $reviews = $this->reviewService->getFeaturedReviews();

        return response()->json([
            'reviews' => $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'customer_name' => $review->customer_name,
                    'photo_url' => $review->photo_url ? asset('storage/' . $review->photo_url) : null,
                    'review_text' => $review->review_text,
                    'rating' => $review->rating,
                    'order_number' => $review->order ? $review->order->order_number : 'N/A',
                    'created_at' => $review->created_at,
                    'formatted_date' => $review->created_at->format('d M Y'),
                    'is_featured' => true
                ];
            })
        ]);
    }

    /**
     * Generate QR codes in batch (admin function)
     */
    public function batchGenerateQR(Request $request)
    {
        $orderIds = $request->get('order_ids', null);
        $generated = $this->reviewService->batchGenerateQRCodes($orderIds);

        return response()->json([
            'message' => 'QR codes generated successfully',
            'generated_count' => count($generated),
            'qr_codes' => $generated
        ]);
    }

    /**
     * Generate multiple review tokens for batch QR generation
     */
    public function generateBatchTokens(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Quantity must be between 1-1000',
                'messages' => $validator->errors()
            ], 422);
        }

        $quantity = $request->get('quantity');
        $tokens = [];

        try {
            for ($i = 0; $i < $quantity; $i++) {
                // Generate unique token
                $token = 'VNY-' . time() . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 9));

                // Create placeholder review entry with token only
                $review = CustomerReview::create([
                    'review_token' => $token,
                    'customer_name' => null, // Will be filled when review is submitted
                    'customer_email' => null, // Will be filled when review is submitted
                    'order_id' => null, // Will be filled when review is submitted
                    'photo_url' => null,
                    'review_text' => null,
                    'rating' => null,
                    'is_approved' => false,
                    'is_featured' => false
                ]);

                $tokens[] = [
                    'id' => $review->id,
                    'token' => $token,
                    'review_url' => url("/review/{$token}")
                ];
            }

            return response()->json([
                'success' => true,
                'message' => "{$quantity} QR tokens generated successfully",
                'quantity' => $quantity,
                'tokens' => $tokens
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate tokens: ' . $e->getMessage()
            ], 500);
        }
    }
}
