<?php

namespace App\Http\Controllers;

use App\Models\{Order, Product, Customer};

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json($this->getStats());
    }

    public function getStats()
    {
        return [
            'total_orders' => Order::count(),
            'total_customers' => Customer::count(),
            'total_products' => Product::count(),
            'revenue' => Order::sum('amount'),
        ];
    }
}
