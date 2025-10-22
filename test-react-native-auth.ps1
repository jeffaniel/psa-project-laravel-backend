# React Native Authentication API Testing Script
# This script tests all authentication endpoints required by the React Native app

$baseUrl = "http://localhost:8000/api"
$testEmail = "testuser_$(Get-Random)@example.com"
$testPassword = "password123"
$testName = "Test User"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "React Native Auth API Testing" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Test 1: Register
Write-Host "Test 1: Register New User" -ForegroundColor Yellow
Write-Host "POST $baseUrl/auth/register" -ForegroundColor Gray
$registerBody = @{
    name = $testName
    email = $testEmail
    password = $testPassword
    password_confirmation = $testPassword
} | ConvertTo-Json

try {
    $registerResponse = Invoke-RestMethod -Uri "$baseUrl/auth/register" `
        -Method Post `
        -Body $registerBody `
        -ContentType "application/json"
    
    $token = $registerResponse.token
    $userId = $registerResponse.user.id
    
    Write-Host "✓ Registration successful!" -ForegroundColor Green
    Write-Host "  User ID: $userId" -ForegroundColor Gray
    Write-Host "  Name: $($registerResponse.user.name)" -ForegroundColor Gray
    Write-Host "  Email: $($registerResponse.user.email)" -ForegroundColor Gray
    Write-Host "  Token: $($token.Substring(0, 20))..." -ForegroundColor Gray
    Write-Host ""
} catch {
    Write-Host "✗ Registration failed!" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    exit 1
}

# Test 2: Get Current User
Write-Host "Test 2: Get Current User" -ForegroundColor Yellow
Write-Host "GET $baseUrl/auth/user" -ForegroundColor Gray
try {
    $headers = @{
        "Authorization" = "Bearer $token"
        "Accept" = "application/json"
    }
    
    $userResponse = Invoke-RestMethod -Uri "$baseUrl/auth/user" `
        -Method Get `
        -Headers $headers
    
    Write-Host "✓ Get user successful!" -ForegroundColor Green
    Write-Host "  User ID: $($userResponse.id)" -ForegroundColor Gray
    Write-Host "  Name: $($userResponse.name)" -ForegroundColor Gray
    Write-Host "  Email: $($userResponse.email)" -ForegroundColor Gray
    Write-Host ""
} catch {
    Write-Host "✗ Get user failed!" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
}

# Test 3: Logout
Write-Host "Test 3: Logout" -ForegroundColor Yellow
Write-Host "POST $baseUrl/auth/logout" -ForegroundColor Gray
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
}

# Test 4: Login
Write-Host "Test 4: Login" -ForegroundColor Yellow
Write-Host "POST $baseUrl/auth/login" -ForegroundColor Gray
$loginBody = @{
    email = $testEmail
    password = $testPassword
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "$baseUrl/auth/login" `
        -Method Post `
        -Body $loginBody `
        -ContentType "application/json"
    
    $token = $loginResponse.token
    
    Write-Host "✓ Login successful!" -ForegroundColor Green
    Write-Host "  User ID: $($loginResponse.user.id)" -ForegroundColor Gray
    Write-Host "  Name: $($loginResponse.user.name)" -ForegroundColor Gray
    Write-Host "  Email: $($loginResponse.user.email)" -ForegroundColor Gray
    Write-Host "  Token: $($token.Substring(0, 20))..." -ForegroundColor Gray
    Write-Host ""
} catch {
    Write-Host "✗ Login failed!" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    exit 1
}

# Test 5: Refresh Token
Write-Host "Test 5: Refresh Token" -ForegroundColor Yellow
Write-Host "POST $baseUrl/auth/refresh" -ForegroundColor Gray
try {
    $headers = @{
        "Authorization" = "Bearer $token"
        "Accept" = "application/json"
    }
    
    $refreshResponse = Invoke-RestMethod -Uri "$baseUrl/auth/refresh" `
        -Method Post `
        -Headers $headers
    
    $newToken = $refreshResponse.token
    
    Write-Host "✓ Token refresh successful!" -ForegroundColor Green
    Write-Host "  New Token: $($newToken.Substring(0, 20))..." -ForegroundColor Gray
    Write-Host ""
} catch {
    Write-Host "✗ Token refresh failed!" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
}

# Test 6: Invalid Login
Write-Host "Test 6: Invalid Login (Should Fail)" -ForegroundColor Yellow
Write-Host "POST $baseUrl/auth/login" -ForegroundColor Gray
$invalidLoginBody = @{
    email = $testEmail
    password = "wrongpassword"
} | ConvertTo-Json

try {
    $invalidLoginResponse = Invoke-RestMethod -Uri "$baseUrl/auth/login" `
        -Method Post `
        -Body $invalidLoginBody `
        -ContentType "application/json"
    
    Write-Host "✗ Invalid login should have failed!" -ForegroundColor Red
} catch {
    Write-Host "✓ Invalid login correctly rejected!" -ForegroundColor Green
    Write-Host ""
}

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "All Tests Completed!" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Summary:" -ForegroundColor Yellow
Write-Host "  Test User Email: $testEmail" -ForegroundColor Gray
Write-Host "  Test User Password: $testPassword" -ForegroundColor Gray
Write-Host ""
Write-Host "Next Steps:" -ForegroundColor Yellow
Write-Host "  1. Update your React Native .env file with:" -ForegroundColor Gray
Write-Host "     EXPO_PUBLIC_API_URL=http://YOUR_IP:8000/api" -ForegroundColor Gray
Write-Host "  2. Start Laravel server: php artisan serve --host=0.0.0.0 --port=8000" -ForegroundColor Gray
Write-Host "  3. Start React Native app: npm start" -ForegroundColor Gray
Write-Host ""
