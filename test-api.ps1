# API Test Script for React Native Integration
Write-Host "=== TESTING LARAVEL API FOR REACT NATIVE ===" -ForegroundColor Green
Write-Host ""

# Test 1: Check if server is running
Write-Host "1. Testing Laravel server..." -ForegroundColor Yellow
try {
    $serverTest = Invoke-WebRequest -Uri "http://127.0.0.1:8000" -Method HEAD -TimeoutSec 5
    Write-Host "   ✓ Server is running" -ForegroundColor Green
} catch {
    Write-Host "   X Server not running! Run: php artisan serve" -ForegroundColor Red
    exit
}

# Test 2: Get Products
Write-Host "`n2. Testing Products API..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/public/products" -Method GET
    $data = $response.Content | ConvertFrom-Json
    
    Write-Host "   OK API Response: 200 OK" -ForegroundColor Green
    Write-Host "   OK Total Products: $($data.data.Count)" -ForegroundColor Green
    
    if ($data.data.Count -gt 0) {
        $product = $data.data[0]
        Write-Host "`n   First Product Details:" -ForegroundColor Cyan
        Write-Host "   - ID: $($product.id)"
        Write-Host "   - Name: $($product.name)"
        Write-Host "   - Price: $($product.selling_price)"
        Write-Host "   - SKU: $($product.sku)"
        Write-Host "   - Category: $($product.category.name)"
        Write-Host "   - Stock: $($product.stock_quantity)"
        Write-Host "   - Active: $($product.is_active)"
        
        # Check images
        if ($product.image_urls -and $product.image_urls.Count -gt 0) {
            Write-Host "`n   OK Product has $($product.image_urls.Count) image(s)" -ForegroundColor Green
            Write-Host "`n   Image URLs for React Native:" -ForegroundColor Cyan
            foreach ($url in $product.image_urls) {
                Write-Host "   - $url" -ForegroundColor White
            }
            
            # Test first image
            Write-Host "`n3. Testing Image Accessibility..." -ForegroundColor Yellow
            try {
                $imageUrl = $product.image_urls[0]
                $imageTest = Invoke-WebRequest -Uri $imageUrl -Method HEAD
                Write-Host "   OK Image is accessible!" -ForegroundColor Green
                Write-Host "   - URL: $imageUrl"
                Write-Host "   - Content-Type: $($imageTest.Headers.'Content-Type')"
                Write-Host "   - Size: $($imageTest.Headers.'Content-Length') bytes"
            } catch {
                Write-Host "   X Image NOT accessible!" -ForegroundColor Red
                Write-Host "   - URL: $imageUrl"
                Write-Host "   - Error: $($_.Exception.Message)"
                Write-Host "`n   Fix: Run 'php artisan storage:link'" -ForegroundColor Yellow
            }
        } else {
            Write-Host "`n   ! Product has NO images" -ForegroundColor Yellow
            Write-Host "   - Add images via admin dashboard" -ForegroundColor Yellow
        }
    } else {
        Write-Host "`n   ! No products found" -ForegroundColor Yellow
        Write-Host "   - Add products via admin dashboard" -ForegroundColor Yellow
    }
    
} catch {
    Write-Host "   X API Error: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 3: Get Categories
Write-Host "`n4. Testing Categories API..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/public/categories" -Method GET
    $categories = $response.Content | ConvertFrom-Json
    
    Write-Host "   OK Total Categories: $($categories.Count)" -ForegroundColor Green
    
    if ($categories.Count -gt 0) {
        Write-Host "`n   Categories:" -ForegroundColor Cyan
        foreach ($cat in $categories) {
            Write-Host "   - $($cat.name) ($($cat.products_count) products)"
        }
    }
} catch {
    Write-Host "   X Error: $($_.Exception.Message)" -ForegroundColor Red
}

# Summary
Write-Host "`n=== REACT NATIVE CONFIGURATION ===" -ForegroundColor Green
Write-Host ""
Write-Host "Use these URLs in your React Native app:" -ForegroundColor Cyan
Write-Host ""
Write-Host "// For Android Emulator:" -ForegroundColor Yellow
Write-Host "const API_URL = 'http://10.0.2.2:8000/api';" -ForegroundColor White
Write-Host ""
Write-Host "// For iOS Simulator:" -ForegroundColor Yellow
Write-Host "const API_URL = 'http://127.0.0.1:8000/api';" -ForegroundColor White
Write-Host ""
Write-Host "// For Physical Device (find your PC's IP with 'ipconfig'):" -ForegroundColor Yellow
Write-Host "const API_URL = 'http://YOUR_PC_IP:8000/api';" -ForegroundColor White
Write-Host ""
Write-Host "=== TEST COMPLETE ===" -ForegroundColor Green
