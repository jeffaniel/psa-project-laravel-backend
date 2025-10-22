Write-Host "=== TESTING LARAVEL API FOR REACT NATIVE ===" -ForegroundColor Green
Write-Host ""

# Test Products API
Write-Host "Testing Products API..." -ForegroundColor Yellow
$response = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/public/products" -Method GET
$data = $response.Content | ConvertFrom-Json

Write-Host "Total Products: $($data.data.Count)" -ForegroundColor Cyan
Write-Host ""

if ($data.data.Count -gt 0) {
    $product = $data.data[0]
    Write-Host "First Product:" -ForegroundColor Yellow
    Write-Host "  Name: $($product.name)"
    Write-Host "  Price: $($product.selling_price)"
    Write-Host "  Category: $($product.category.name)"
    Write-Host ""
    
    if ($product.image_urls -and $product.image_urls.Count -gt 0) {
        Write-Host "Image URL for React Native:" -ForegroundColor Green
        Write-Host "  $($product.image_urls[0])" -ForegroundColor White
        Write-Host ""
        
        # Test image accessibility
        try {
            $imageTest = Invoke-WebRequest -Uri $product.image_urls[0] -Method HEAD
            Write-Host "Image Status: ACCESSIBLE" -ForegroundColor Green
        } catch {
            Write-Host "Image Status: NOT ACCESSIBLE" -ForegroundColor Red
            Write-Host "Run: php artisan storage:link" -ForegroundColor Yellow
        }
    } else {
        Write-Host "No images found" -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "=== REACT NATIVE CONFIGURATION ===" -ForegroundColor Green
Write-Host ""
Write-Host "Android Emulator:" -ForegroundColor Yellow
Write-Host "  const API_URL = 'http://10.0.2.2:8000/api';"
Write-Host ""
Write-Host "iOS Simulator:" -ForegroundColor Yellow
Write-Host "  const API_URL = 'http://127.0.0.1:8000/api';"
Write-Host ""
