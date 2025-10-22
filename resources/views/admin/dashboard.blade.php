@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <div class="text-sm text-gray-600">
            {{ now()->format('l, F j, Y') }}
        </div>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">₦{{ number_format($stats['total_revenue'], 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">All time</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Monthly Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">₦{{ number_format($stats['monthly_revenue'], 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">This month</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_orders']) }}</p>
                    <p class="text-xs text-yellow-600 mt-1">{{ $stats['pending_orders'] }} pending</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Total Customers -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Customers</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_customers']) }}</p>
                    <p class="text-xs text-green-600 mt-1">+{{ $stats['new_customers_today'] }} today</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Products</h3>
                <a href="{{ route('admin.products.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Products</span>
                    <span class="font-semibold text-gray-900">{{ number_format($stats['total_products']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Active Products</span>
                    <span class="font-semibold text-green-600">{{ number_format($stats['active_products']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Low Stock</span>
                    <span class="font-semibold text-yellow-600">{{ number_format($stats['low_stock_count']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Out of Stock</span>
                    <span class="font-semibold text-red-600">{{ number_format($stats['out_of_stock_count']) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow p-6 md:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 px-3 text-xs font-medium text-gray-600">Order #</th>
                            <th class="text-left py-2 px-3 text-xs font-medium text-gray-600">Customer</th>
                            <th class="text-left py-2 px-3 text-xs font-medium text-gray-600">Amount</th>
                            <th class="text-left py-2 px-3 text-xs font-medium text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders->take(5) as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-3 text-sm">{{ $order->order_number }}</td>
                            <td class="py-3 px-3 text-sm">{{ $order->customer->name ?? 'N/A' }}</td>
                            <td class="py-3 px-3 text-sm font-semibold">₦{{ number_format($order->total_amount, 2) }}</td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($order->status ?? 'pending') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">No orders yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
    
    <!-- Low Stock Alert -->
    @if($lowStockProducts->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                Low Stock Alert
            </h3>
            <a href="{{ route('admin.products.index') }}?stock=low" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2 px-3 text-xs font-medium text-gray-600">Product</th>
                        <th class="text-left py-2 px-3 text-xs font-medium text-gray-600">SKU</th>
                        <th class="text-left py-2 px-3 text-xs font-medium text-gray-600">Stock</th>
                        <th class="text-left py-2 px-3 text-xs font-medium text-gray-600">Min Level</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts->take(5) as $product)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-3 text-sm font-medium">{{ $product->name }}</td>
                        <td class="py-3 px-3 text-sm text-gray-600">{{ $product->sku }}</td>
                        <td class="py-3 px-3 text-sm">
                            <span class="text-red-600 font-semibold">{{ $product->stock_quantity }}</span>
                        </td>
                        <td class="py-3 px-3 text-sm text-gray-600">{{ $product->min_stock_level }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    
</div>
@endsection
