<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Product, Order, Customer, Payment};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getStats();
        $recentOrders = Order::with(['customer', 'orderItems.product'])
            ->latest()
            ->take(10)
            ->get();
        
        $lowStockProducts = Product::where('stock_quantity', '<=', DB::raw('min_stock_level'))
            ->where('track_inventory', true)
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts'));
    }

    private function getStats()
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        return [
            'total_revenue' => Order::where('payment_status', 'paid')->sum('amount'),
            'monthly_revenue' => Order::where('payment_status', 'paid')
                ->where('created_at', '>=', $thisMonth)
                ->sum('amount'),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_customers' => Customer::count(),
            'new_customers_today' => Customer::whereDate('created_at', $today)->count(),
            'low_stock_count' => Product::where('stock_quantity', '<=', DB::raw('min_stock_level'))
                ->where('track_inventory', true)
                ->count(),
            'out_of_stock_count' => Product::where('stock_quantity', 0)
                ->where('track_inventory', true)
                ->count(),
        ];
    }
}
