<?php
echo "=== Testing All VNY Product URLs ===\n\n";

$baseUrl = "http://127.0.0.1:8000";
$testUrls = [
    "/vny/product" => "Product List Page",
    "/vny/product/16" => "Product Detail Page (ID: 16)",
];

foreach ($testUrls as $url => $description) {
    echo "Testing: {$description}\n";
    echo "URL: {$baseUrl}{$url}\n";

    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 10,
            'ignore_errors' => true
        ]
    ]);

    $response = @file_get_contents($baseUrl . $url, false, $context);
    $httpCode = 200;

    if (isset($http_response_header)) {
        $statusLine = $http_response_header[0];
        preg_match('/\d{3}/', $statusLine, $matches);
        $httpCode = $matches[0] ?? 200;
    }

    if ($response !== false && $httpCode == 200) {
        echo "✅ Status: OK ({$httpCode})\n";

        // Check if it contains expected content
        if (strpos($url, '/product/') !== false) {
            if (strpos($response, 'Pilih Warna') !== false && strpos($response, 'Pilih Ukuran') !== false) {
                echo "✅ Contains product detail elements\n";
            } else {
                echo "⚠️  Missing some product detail elements\n";
            }
        } else {
            if (strpos($response, 'product-card') !== false) {
                echo "✅ Contains product grid\n";
            } else {
                echo "⚠️  Missing product grid\n";
            }
        }
    } else {
        echo "❌ Status: Error ({$httpCode})\n";
        if ($response) {
            echo "Response length: " . strlen($response) . " bytes\n";
        }
    }

    echo "\n";
}

echo "=== Test Complete ===\n";
?>
