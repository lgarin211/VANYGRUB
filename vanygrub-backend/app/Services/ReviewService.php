<?php

namespace App\Services;

use App\Models\CustomerReview;
use App\Models\Order;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class ReviewService
{
    /**
     * Generate review token and QR code for order
     */
    public function generateReviewToken(Order $order): string
    {
        // Check if review already exists
        if ($order->hasReview()) {
            return $order->customerReviews()->first()->review_token;
        }

        // Generate new review token
        $reviewToken = CustomerReview::generateReviewToken();

        // Create review entry
        CustomerReview::create([
            'order_id' => $order->id,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'photo_url' => '', // Will be filled when customer submits
            'review_text' => '', // Will be filled when customer submits
            'rating' => 5, // Default rating
            'review_token' => $reviewToken,
            'is_approved' => false,
        ]);

        return $reviewToken;
    }

    /**
     * Generate QR code for review URL
     */
    public function generateQRCode(string $reviewToken, Order $order): string
    {
        $reviewUrl = url("/review/{$reviewToken}");

        // Generate QR code
        $qrCode = QrCode::size(300)
            ->backgroundColor(255, 255, 255)
            ->color(220, 38, 38) // VNY Store red color
            ->margin(2)
            ->generate($reviewUrl);

        // Save QR code to storage
        $fileName = "qr-codes/order-{$order->order_number}-{$reviewToken}.png";
        Storage::put("public/{$fileName}", $qrCode);

        return $fileName;
    }

    /**
     * Generate QR code with custom design
     */
    public function generateCustomQRCode(string $reviewToken, Order $order): string
    {
        $reviewUrl = url("/review/{$reviewToken}");

        // Generate QR code with custom style
        $qrCode = QrCode::size(400)
            ->backgroundColor(248, 249, 250) // Light background
            ->color(220, 38, 38) // VNY red
            ->margin(3)
            ->style('round') // Rounded corners
            ->eye('circle') // Circular eye style
            ->format('png')
            ->generate($reviewUrl);

        // Save to storage
        $fileName = "qr-codes/custom-order-{$order->order_number}-{$reviewToken}.png";
        Storage::put("public/{$fileName}", $qrCode);

        return $fileName;
    }

    /**
     * Submit customer review
     */
    public function submitReview(string $reviewToken, array $data): bool
    {
        $review = CustomerReview::where('review_token', $reviewToken)->first();

        if (!$review) {
            return false;
        }

        // Handle photo upload
        $photoPath = '';
        if (isset($data['photo']) && $data['photo']) {
            $photoPath = $data['photo']->store('customer-reviews', 'public');
        }

        // Update review
        $review->update([
            'photo_url' => $photoPath,
            'review_text' => $data['review_text'] ?? '',
            'rating' => $data['rating'] ?? 5,
            'is_approved' => false, // Needs admin approval
        ]);

        return true;
    }

    /**
     * Get review by token
     */
    public function getReviewByToken(string $reviewToken): ?CustomerReview
    {
        return CustomerReview::with('order', 'order.items', 'order.items.product')
            ->where('review_token', $reviewToken)
            ->first();
    }

    /**
     * Get approved reviews for display
     */
    public function getApprovedReviews(int $limit = null)
    {
        $query = CustomerReview::with('order')
            ->approved()
            ->latest();

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get featured reviews
     */
    public function getFeaturedReviews(int $limit = 6)
    {
        return CustomerReview::with('order')
            ->approved()
            ->featured()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Batch generate QR codes for orders without reviews
     */
    public function batchGenerateQRCodes(array $orderIds = null): array
    {
        $query = Order::whereDoesntHave('customerReviews');

        if ($orderIds) {
            $query->whereIn('id', $orderIds);
        }

        $orders = $query->get();
        $generated = [];

        foreach ($orders as $order) {
            $reviewToken = $this->generateReviewToken($order);
            $qrPath = $this->generateCustomQRCode($reviewToken, $order);

            $generated[] = [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'review_token' => $reviewToken,
                'qr_path' => $qrPath,
                'review_url' => url("/review/{$reviewToken}")
            ];
        }

        return $generated;
    }
}
