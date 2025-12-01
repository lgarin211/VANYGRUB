<?php

require_once __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking for null review_text:\n";

$nullReviews = App\Models\CustomerReview::whereNull('review_text')->count();
echo "Reviews with null text: " . $nullReviews . "\n";

$emptyReviews = App\Models\CustomerReview::where('review_text', '')->count();
echo "Reviews with empty text: " . $emptyReviews . "\n";

$totalReviews = App\Models\CustomerReview::count();
echo "Total reviews: " . $totalReviews . "\n";

// Show some sample data
echo "\nSample review data:\n";
$sampleReviews = App\Models\CustomerReview::take(3)->get(['id', 'customer_name', 'review_text', 'is_approved']);
foreach ($sampleReviews as $review) {
    echo "ID: {$review->id}, Customer: {$review->customer_name}, Has text: " . ($review->review_text ? 'Yes' : 'No') . "\n";
}
