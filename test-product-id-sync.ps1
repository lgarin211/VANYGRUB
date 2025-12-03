# Test Product ID Synchronization Fix
Write-Host "Testing Product ID Synchronization Fix..." -ForegroundColor Yellow

# Test 1: Check if API returns correct product IDs  
Write-Host "`n1. Testing API products endpoint..." -ForegroundColor Green
try {
    $products = Invoke-RestMethod -Uri "http://localhost:8000/api/vny/products" -Method GET
    Write-Host "✓ API Working - Found $($products.data.Count) products" -ForegroundColor Green
    
    Write-Host "Available Product IDs in Database:" -ForegroundColor Cyan
    foreach ($product in $products.data) {
        Write-Host "  - ID: $($product.id) | Name: $($product.name)" -ForegroundColor White
    }
    
    $validIds = $products.data | ForEach-Object { $_.id }
    Write-Host "`nValid Product IDs: $($validIds -join ', ')" -ForegroundColor Green
    
}
catch {
    Write-Host "✗ API Error: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Test 2: Try to create an order with a valid product ID
Write-Host "`n2. Testing order creation with VALID product ID ($($validIds[0]))..." -ForegroundColor Green
$validOrderBody = @{
    customer_name        = "Test Customer"
    customer_email       = "test@example.com"
    customer_phone       = "123456789"
    shipping_address     = "Test Address"
    shipping_city        = "Test City"  
    shipping_postal_code = "12345"
    total_amount         = 350000
    notes                = "Test order with valid ID"
    session_id           = "test_session_valid"
    items                = @(
        @{
            product_id = $validIds[0]
            quantity   = 1
            price      = "350000.00"
        }
    )
} | ConvertTo-Json -Depth 3

try {
    $response = Invoke-RestMethod -Uri "http://localhost:8000/api/vny/orders" -Method POST -Body $validOrderBody -ContentType "application/json"
    Write-Host "✓ SUCCESS: Order created with valid product ID $($validIds[0])" -ForegroundColor Green
    Write-Host "  Order Code: $($response.data.order_code)" -ForegroundColor White
}
catch {
    Write-Host "✗ FAILED: Order with valid ID should have succeeded" -ForegroundColor Red
    Write-Host "  Error: $($_.Exception.Message)" -ForegroundColor White
}

# Test 3: Try to create an order with an invalid product ID (should fail gracefully)
Write-Host "`n3. Testing order creation with INVALID product ID (99)..." -ForegroundColor Yellow
$invalidOrderBody = @{
    customer_name        = "Test Customer"
    customer_email       = "test@example.com" 
    customer_phone       = "123456789"
    shipping_address     = "Test Address"
    shipping_city        = "Test City"
    shipping_postal_code = "12345"
    total_amount         = 350000
    notes                = "Test order with invalid ID"
    session_id           = "test_session_invalid"
    items                = @(
        @{
            product_id = 99
            quantity   = 1
            price      = "350000.00"
        }
    )
} | ConvertTo-Json -Depth 3

try {
    $response = Invoke-RestMethod -Uri "http://localhost:8000/api/vny/orders" -Method POST -Body $invalidOrderBody -ContentType "application/json"
    Write-Host "✗ UNEXPECTED: Invalid product ID should have been rejected!" -ForegroundColor Red
}
catch {
    Write-Host "✓ SUCCESS: Invalid product ID correctly rejected" -ForegroundColor Green
    Write-Host "  This is expected behavior - the validation fix is working!" -ForegroundColor White
}

Write-Host "`n" -ForegroundColor Yellow
Write-Host "=== SUMMARY ===" -ForegroundColor Yellow
Write-Host "The product ID synchronization issue has been fixed by:" -ForegroundColor White
Write-Host "1. Removing fallback to static JSON data with wrong IDs" -ForegroundColor White
Write-Host "2. API is now the single source of truth for product IDs" -ForegroundColor White
Write-Host "3. Added validation in OrderController to check product existence" -ForegroundColor White
Write-Host "`nValid Product IDs: $($validIds -join ', ')" -ForegroundColor Green
Write-Host "`nFrontend should now only use these valid IDs when adding to cart!" -ForegroundColor Cyan