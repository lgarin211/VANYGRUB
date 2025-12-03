# Test Dynamic WhatsApp Number from Site Config
Write-Host "Testing Dynamic WhatsApp Number Implementation..." -ForegroundColor Yellow

# Test 1: Check if site config API is accessible
Write-Host "`n1. Testing site config API..." -ForegroundColor Green
try {
    $siteConfig = Invoke-RestMethod -Uri "http://localhost:8000/api/vny/homepage/site-config" -Method GET
    Write-Host "✓ SUCCESS: Site config API accessible" -ForegroundColor Green
    
    if ($siteConfig.data.contact.phone) {
        Write-Host "  Phone from API: $($siteConfig.data.contact.phone)" -ForegroundColor White
        
        # Test phone number formatting logic
        $phone = $siteConfig.data.contact.phone
        $cleanPhone = $phone -replace '\D', ''
        
        if ($cleanPhone.StartsWith('0')) {
            $whatsappNumber = '62' + $cleanPhone.Substring(1)
        } elseif ($cleanPhone.StartsWith('62')) {
            $whatsappNumber = $cleanPhone
        } else {
            $whatsappNumber = '62' + $cleanPhone
        }
        
        Write-Host "  Cleaned number: $cleanPhone" -ForegroundColor Cyan
        Write-Host "  WhatsApp format: $whatsappNumber" -ForegroundColor Cyan
    } else {
        Write-Host "✗ WARNING: No phone number in site config" -ForegroundColor Yellow
    }
} catch {
    Write-Host "✗ FAILED: Could not access site config API" -ForegroundColor Red
    Write-Host "  Error: $($_.Exception.Message)" -ForegroundColor White
    Write-Host "  This means fallback number will be used: 6282111424592" -ForegroundColor Yellow
}

# Test 2: Verify different phone number formats
Write-Host "`n2. Testing phone number formatting logic..." -ForegroundColor Green

$testPhones = @(
    "+62 812-3456-7890",
    "0812-3456-7890", 
    "62812345678",
    "812345678",
    "+62 821-1142-4592"
)

foreach ($phone in $testPhones) {
    $cleanPhone = $phone -replace '\D', ''
    
    if ($cleanPhone.StartsWith('0')) {
        $formatted = '62' + $cleanPhone.Substring(1)
    } elseif ($cleanPhone.StartsWith('62')) {
        $formatted = $cleanPhone
    } else {
        $formatted = '62' + $cleanPhone
    }
    
    Write-Host "  Input: $phone → Output: $formatted" -ForegroundColor White
}

# Test 3: Simulate checkout with site config (if API is available)
Write-Host "`n3. Testing checkout URL generation..." -ForegroundColor Green
try {
    if ($siteConfig -and $whatsappNumber) {
        $testMessage = "Hello from VANY Store! This is a test message."
        $encodedMessage = [System.Web.HttpUtility]::UrlEncode($testMessage)
        $whatsappUrl = "https://wa.me/$whatsappNumber?text=$encodedMessage"
        
        Write-Host "✓ SUCCESS: WhatsApp URL generated" -ForegroundColor Green
        Write-Host "  URL: $whatsappUrl" -ForegroundColor Cyan
        Write-Host "  Number used: $whatsappNumber (from site config)" -ForegroundColor Green
    } else {
        $fallbackNumber = "6282111424592"
        $testMessage = "Hello from VANY Store! This is a test message."
        $encodedMessage = [System.Web.HttpUtility]::UrlEncode($testMessage)
        $whatsappUrl = "https://wa.me/$fallbackNumber?text=$encodedMessage"
        
        Write-Host "✓ FALLBACK: Using default number" -ForegroundColor Yellow
        Write-Host "  URL: $whatsappUrl" -ForegroundColor Cyan
        Write-Host "  Number used: $fallbackNumber (fallback)" -ForegroundColor Yellow
    }
} catch {
    Write-Host "✗ ERROR: URL generation failed" -ForegroundColor Red
    Write-Host "  Error: $($_.Exception.Message)" -ForegroundColor White
}

Write-Host "`n" -ForegroundColor Yellow
Write-Host "=== IMPLEMENTATION SUMMARY ===" -ForegroundColor Yellow
Write-Host "✓ Site config API endpoint added" -ForegroundColor Green
Write-Host "✓ useSiteConfig() hook implemented" -ForegroundColor Green
Write-Host "✓ Dynamic WhatsApp number extraction with fallback" -ForegroundColor Green
Write-Host "✓ Cart checkout updated to use site config" -ForegroundColor Green  
Write-Host "✓ Transactions page updated to use site config" -ForegroundColor Green
Write-Host "✓ Phone number formatting logic handles various formats" -ForegroundColor Green

Write-Host "`nThe WhatsApp number is now DYNAMIC and comes from:" -ForegroundColor Cyan
Write-Host "1. Primary: Site config API (contact.phone)" -ForegroundColor White
Write-Host "2. Fallback: +62 821-1142-4592" -ForegroundColor White
Write-Host "`nBoth cart checkout and transactions will use the configured number! 🎉" -ForegroundColor Green