<?php

// Test API untuk product detail
echo "=== Testing Product Detail API ===\n\n";

$productId = 16;
$apiUrl = "https://vanyadmin.progesio.my.id/api/vny/products/{$productId}";

echo "Testing Product ID: {$productId}\n";
echo "URL: {$apiUrl}\n\n";

$response = @file_get_contents($apiUrl);
if ($response === false) {
    echo "❌ API Error: Cannot fetch product data\n";
    echo "Last error: " . error_get_last()['message'] . "\n\n";
} else {
    $data = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "✅ API Success\n";
        if (isset($data['data'])) {
            echo "Product found:\n";
            echo "- ID: " . $data['data']['id'] . "\n";
            echo "- Name: " . $data['data']['name'] . "\n";
            echo "- Price: " . $data['data']['price'] . "\n";
            echo "- Colors: " . implode(', ', $data['data']['colors'] ?? []) . "\n";
            echo "- Sizes: " . implode(', ', $data['data']['sizes'] ?? []) . "\n";
        } else {
            echo "No data field in response:\n";
            echo $response . "\n";
        }
    } else {
        echo "❌ JSON Error: " . json_last_error_msg() . "\n";
        echo "Raw response: " . $response . "\n";
    }
}

echo "\n=== Test Complete ===\n";
?>
