# Test script for order API validation fix
Write-Host "Testing Order API Fix..." -ForegroundColor Yellow

# Test 1: Valid product ID (should succeed)
Write-Host "`n1. Testing with VALID product ID (16)..." -ForegroundColor Green
$validBody = '{"customer_name":"Test Customer Valid","customer_email":"valid@example.com","customer_phone":"123456789","shipping_address":"Test Address","shipping_city":"Test City","shipping_postal_code":"12345","total_amount":100000,"notes":"valid test","session_id":"test_session_valid","items":[{"product_id":16,"quantity":1,"price":"100000.00"}]}'

try {
    $response = Invoke-RestMethod -Uri "http://localhost:8000/api/vny/orders" -Method POST -Body $validBody -ContentType "application/json" -ErrorAction Stop
    Write-Host "SUCCESS: Order created with valid product ID" -ForegroundColor Green
    Write-Host "  Order Code: $($response.data.order_code)"
    Write-Host "  Order ID: $($response.data.id)"
}
catch {
    Write-Host "FAILED: Valid order should have succeeded" -ForegroundColor Red
    Write-Host "  Error: $($_.Exception.Message)"
}

# Test 2: Invalid product ID (should fail with our new validation)
Write-Host "`n2. Testing with INVALID product ID (27)..." -ForegroundColor Yellow
$invalidBody = '{"customer_name":"Test Customer Invalid","customer_email":"invalid@example.com","customer_phone":"123456789","shipping_address":"Test Address","shipping_city":"Test City","shipping_postal_code":"12345","total_amount":100000,"notes":"invalid test","session_id":"test_session_invalid","items":[{"product_id":27,"quantity":1,"price":"100000.00"}]}'

try {
    $response = Invoke-RestMethod -Uri "http://localhost:8000/api/vny/orders" -Method POST -Body $invalidBody -ContentType "application/json" -ErrorAction Stop
    Write-Host "UNEXPECTED: Invalid product ID should have failed!" -ForegroundColor Red
}
catch {
    Write-Host "SUCCESS: Invalid product ID correctly rejected" -ForegroundColor Green
    Write-Host "  Error Message: $($_.Exception.Message)"
}

Write-Host "`nTest completed!" -ForegroundColor Yellow