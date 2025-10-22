<?php

namespace App\Services;

use App\Models\{Order, Product, Customer, InventoryMovement};
use Illuminate\Support\Carbon;

class ReportService
{
    public function sales(array $filters = [])
    {
        $q = Order::query();
        if (!empty($filters['from'])) $q->whereDate('created_at', '>=', $filters['from']);
        if (!empty($filters['to'])) $q->whereDate('created_at', '<=', $filters['to']);
        return [
            'total_orders' => (clone $q)->count(),
            'total_revenue' => (clone $q)->sum('total_amount'),
        ];
    }

    public function inventory(array $filters = [])
    {
        return [
            'low_stock' => Product::whereColumn('stock_quantity', '<=', 'min_stock_level')->count(),
            'movements_last_30_days' => InventoryMovement::whereDate('created_at', '>=', now()->subDays(30))->count(),
        ];
    }
}
