<?php

// Test script untuk mengecek API endpoints
echo "=== Testing VNY API Endpoints ===\n\n";

// Test Categories API
echo "1. Testing Categories API:\n";
echo "URL: https://vanygroup.id/api/vny/categories\n\n";

$categoriesResponse = @file_get_contents('https://vanygroup.id/api/vny/categories');
if ($categoriesResponse === false) {
    echo "❌ Categories API Error: Cannot fetch data\n";
    echo "Last error: " . error_get_last()['message'] . "\n\n";
} else {
    $categoriesData = json_decode($categoriesResponse, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "✅ Categories API Success\n";
        echo "Response structure:\n";
        if (isset($categoriesData['data'])) {
            echo "- Data field exists\n";
            echo "- Total categories: " . count($categoriesData['data']) . "\n";
            if (!empty($categoriesData['data'])) {
                $firstCategory = $categoriesData['data'][0];
                echo "- First category structure: " . json_encode($firstCategory, JSON_PRETTY_PRINT) . "\n";
            }
        } else {
            echo "- Raw response: " . $categoriesResponse . "\n";
        }
        echo "\n";
    } else {
        echo "❌ Categories API JSON Error: " . json_last_error_msg() . "\n";
        echo "Raw response: " . $categoriesResponse . "\n\n";
    }
}

// Test Products API
echo "2. Testing Products API:\n";
echo "URL: https://vanygroup.id/api/vny/products\n\n";

$productsResponse = @file_get_contents('https://vanygroup.id/api/vny/products');
if ($productsResponse === false) {
    echo "❌ Products API Error: Cannot fetch data\n";
    echo "Last error: " . error_get_last()['message'] . "\n\n";
} else {
    $productsData = json_decode($productsResponse, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "✅ Products API Success\n";
        echo "Response structure:\n";
        if (isset($productsData['data'])) {
            echo "- Data field exists\n";
            echo "- Total products: " . count($productsData['data']) . "\n";
            if (!empty($productsData['data'])) {
                $firstProduct = $productsData['data'][0];
                echo "- First product structure: " . json_encode($firstProduct, JSON_PRETTY_PRINT) . "\n";
            }
        } else {
            echo "- Raw response: " . $productsResponse . "\n";
        }
        echo "\n";
    } else {
        echo "❌ Products API JSON Error: " . json_last_error_msg() . "\n";
        echo "Raw response: " . $productsResponse . "\n\n";
    }
}

echo "=== Test Complete ===\n";
?>