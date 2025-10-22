<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check if logout columns exist
try {
    $users = \App\Models\User::select('id', 'name', 'email', 'last_login_at', 'last_logout_at', 'last_login_ip', 'last_logout_ip')
        ->limit(5)
        ->get();
    
    echo "=== LOGOUT TRACKING DATA CHECK ===\n\n";
    
    if ($users->isEmpty()) {
        echo "❌ No users found in database\n";
        exit;
    }
    
    echo "✅ Found " . $users->count() . " users\n\n";
    
    foreach ($users as $user) {
        echo "User: {$user->name} ({$user->email})\n";
        echo "  Last Login:  " . ($user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never') . "\n";
        echo "  Last Login IP: " . ($user->last_login_ip ?: 'N/A') . "\n";
        echo "  Last Logout: " . ($user->last_logout_at ? $user->last_logout_at->format('Y-m-d H:i:s') : 'Never') . "\n";
        echo "  Last Logout IP: " . ($user->last_logout_ip ?: 'N/A') . "\n";
        echo "  ---\n";
    }
    
    // Check if any user has logout data
    $usersWithLogout = \App\Models\User::whereNotNull('last_logout_at')->count();
    echo "\n📊 Users with logout data: {$usersWithLogout}\n";
    
    if ($usersWithLogout === 0) {
        echo "\n⚠️  NO LOGOUT DATA FOUND!\n";
        echo "This means either:\n";
        echo "1. No one has logged out since the feature was implemented\n";
        echo "2. The logout tracking is not working properly\n";
        echo "\nTo test: Login to admin panel, logout, then login again and check.\n";
    } else {
        echo "\n✅ Logout tracking is working!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "This might mean the columns don't exist yet.\n";
    echo "Run: php artisan migrate\n";
}
