# Test Logout Tracking Feature
# This script tests if logout tracking is working correctly

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Testing Logout Tracking Feature" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$baseUrl = "http://localhost:8000/api"

# Test credentials (use existing user or create one)
$email = "admin@example.com"
$password = "password"

Write-Host "Step 1: Login" -ForegroundColor Yellow
$loginBody = @{
    email = $email
    password = $password
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "$baseUrl/auth/login" `
        -Method Post `
        -Body $loginBody `
        -ContentType "application/json"
    
    $token = $loginResponse.token
    $userId = $loginResponse.user.id
    
    Write-Host "✓ Login successful!" -ForegroundColor Green
    Write-Host "  User ID: $userId" -ForegroundColor Gray
    Write-Host "  Token: $($token.Substring(0, 20))..." -ForegroundColor Gray
    Write-Host ""
} catch {
    Write-Host "✗ Login failed!" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    Write-Host ""
    Write-Host "Please ensure:" -ForegroundColor Yellow
    Write-Host "  1. Laravel server is running (php artisan serve)" -ForegroundColor Gray
    Write-Host "  2. User exists with email: $email" -ForegroundColor Gray
    Write-Host "  3. Password is correct: $password" -ForegroundColor Gray
    exit 1
}

Write-Host "Step 2: Get User Info Before Logout" -ForegroundColor Yellow
$headers = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
}

try {
    $userBefore = Invoke-RestMethod -Uri "$baseUrl/auth/user" `
        -Method Get `
        -Headers $headers
    
    Write-Host "✓ User info retrieved!" -ForegroundColor Green
    Write-Host "  Name: $($userBefore.name)" -ForegroundColor Gray
    Write-Host "  Last Login: $($userBefore.last_login_at)" -ForegroundColor Gray
    Write-Host "  Last Logout: $($userBefore.last_logout_at ?? 'Never')" -ForegroundColor Gray
    Write-Host ""
} catch {
    Write-Host "✗ Failed to get user info!" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
}

Write-Host "Step 3: Logout (This should update last_logout_at)" -ForegroundColor Yellow
try {
    $logoutResponse = Invoke-RestMethod -Uri "$baseUrl/auth/logout" `
        -Method Post `
        -Headers $headers
    
    Write-Host "✓ Logout successful!" -ForegroundColor Green
    Write-Host "  Message: $($logoutResponse.message)" -ForegroundColor Gray
    Write-Host ""
} catch {
    Write-Host "✗ Logout failed!" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    exit 1
}

Write-Host "Step 4: Login Again to Check Logout Timestamp" -ForegroundColor Yellow
try {
    $loginResponse2 = Invoke-RestMethod -Uri "$baseUrl/auth/login" `
        -Method Post `
        -Body $loginBody `
        -ContentType "application/json"
    
    $token2 = $loginResponse2.token
    
    Write-Host "✓ Re-login successful!" -ForegroundColor Green
    Write-Host ""
} catch {
    Write-Host "✗ Re-login failed!" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    exit 1
}

Write-Host "Step 5: Verify Logout Was Tracked" -ForegroundColor Yellow
$headers2 = @{
    "Authorization" = "Bearer $token2"
    "Accept" = "application/json"
}

try {
    $userAfter = Invoke-RestMethod -Uri "$baseUrl/auth/user" `
        -Method Get `
        -Headers $headers2
    
    Write-Host "✓ User info retrieved!" -ForegroundColor Green
    Write-Host ""
    Write-Host "RESULTS:" -ForegroundColor Cyan
    Write-Host "─────────────────────────────────────" -ForegroundColor Gray
    Write-Host "  Name: $($userAfter.name)" -ForegroundColor White
    Write-Host "  Email: $($userAfter.email)" -ForegroundColor White
    Write-Host ""
    Write-Host "  Last Login At: $($userAfter.last_login_at)" -ForegroundColor Green
    Write-Host "  Last Login IP: $($userAfter.last_login_ip)" -ForegroundColor Green
    Write-Host ""
    
    if ($userAfter.last_logout_at) {
        Write-Host "  Last Logout At: $($userAfter.last_logout_at)" -ForegroundColor Yellow
        Write-Host "  Last Logout IP: $($userAfter.last_logout_ip)" -ForegroundColor Yellow
        Write-Host ""
        Write-Host "✓ LOGOUT TRACKING IS WORKING! ✓" -ForegroundColor Green -BackgroundColor Black
    } else {
        Write-Host "  Last Logout At: Never" -ForegroundColor Red
        Write-Host "  Last Logout IP: N/A" -ForegroundColor Red
        Write-Host ""
        Write-Host "✗ LOGOUT TRACKING NOT WORKING!" -ForegroundColor Red -BackgroundColor Black
        Write-Host ""
        Write-Host "Troubleshooting:" -ForegroundColor Yellow
        Write-Host "  1. Check if migration ran: php artisan migrate:status" -ForegroundColor Gray
        Write-Host "  2. Check User model fillable array includes: last_logout_at, last_logout_ip" -ForegroundColor Gray
        Write-Host "  3. Check AuthController logout method updates user" -ForegroundColor Gray
    }
    Write-Host ""
    
} catch {
    Write-Host "✗ Failed to verify!" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
}

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Test Complete!" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
