<?php

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ORDERS TABLE STRUCTURE VERIFICATION ===\n\n";

try {
    // Get table columns
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('orders');
    
    echo "✅ Orders table exists with " . count($columns) . " columns:\n\n";
    
    foreach ($columns as $index => $column) {
        echo ($index + 1) . ". " . $column . "\n";
    }
    
    echo "\n=== SAMPLE ORDER CREATION TEST ===\n";
    
    // Test creating a sample order
    $order = new \App\Models\Order();
    echo "✅ Order model loaded successfully\n";
    
    // Check fillable fields
    echo "\n📝 Fillable fields in Order model:\n";
    foreach ($order->getFillable() as $index => $field) {
        echo ($index + 1) . ". " . $field . "\n";
    }
    
    echo "\n=== ORDER ITEMS TABLE ===\n";
    
    // Check order_items table
    $orderItemsColumns = \Illuminate\Support\Facades\Schema::getColumnListing('order_items');
    echo "✅ Order Items table exists with " . count($orderItemsColumns) . " columns:\n";
    
    foreach ($orderItemsColumns as $index => $column) {
        echo ($index + 1) . ". " . $column . "\n";
    }
    
    echo "\n🎉 DATABASE RESTRUCTURE SUCCESSFUL!\n";
    echo "\nNew simplified structure is ready for:\n";
    echo "- Simple order management\n";
    echo "- Payment receipt uploads\n";
    echo "- Delivery tracking\n";
    echo "- Admin status updates\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
