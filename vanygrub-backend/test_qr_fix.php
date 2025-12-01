<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Models\CustomerReview;
use Illuminate\Support\Str;

try {
    // Test create 1 QR token
    $token = 'VNY-' . time() . '-' . strtoupper(Str::random(9));
    $review = CustomerReview::create([
        'review_token' => $token,
        'customer_name' => null,
        'customer_email' => null,
        'order_id' => null,
        'photo_url' => null,
        'review_text' => null,
        'rating' => null,
        'is_approved' => false,
        'is_featured' => false
    ]);

    echo "Success! Created QR token: {$token}\n";
    echo "Review ID: {$review->id}\n";
    echo "Total CustomerReview records: " . CustomerReview::count() . "\n";
    echo "Fix applied successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}