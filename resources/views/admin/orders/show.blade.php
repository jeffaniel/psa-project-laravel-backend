@extends('admin.layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
            <p class="text-gray-600">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
            ← Back to Orders
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <strong>Success!</strong> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong>Error!</strong> {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Order Status & Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Order Status & Actions</h2>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                            @if($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->payment_status === 'success') bg-green-100 text-green-800
                            @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    @if($order->payment_status === 'pending')
                        <form method="POST" action="{{ route('admin.orders.approve-payment', $order) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors"
                                    onclick="return confirm('Approve payment for order {{ $order->order_number }}?')">
                                ✅ Approve Payment
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.orders.reject-payment', $order) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors"
                                    onclick="return confirm('Reject payment for order {{ $order->order_number }}?')">
                                ❌ Reject Payment
                            </button>
                        </form>
                    @elseif($order->payment_status === 'success')
                        <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg">
                            ✅ Payment Approved
                        </div>
                    @endif

                    @if($order->status === 'processing')
                        <form method="POST" action="{{ route('admin.orders.mark-delivered', $order) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
                                    onclick="return confirm('Mark this order as delivered?')">
                                🚚 Mark as Delivered
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Payment Receipt -->
            @if($order->payment_receipt)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Payment Receipt</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Customer uploaded payment receipt:</p>
                        <a href="{{ Storage::url($order->payment_receipt) }}" 
                           target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            📄 View Receipt
                        </a>
                    </div>
                    <div class="border rounded-lg p-4">
                        <img src="{{ Storage::url($order->payment_receipt) }}" 
                             alt="Payment Receipt" 
                             class="max-w-full h-auto max-h-96 mx-auto rounded-lg shadow">
                    </div>
                </div>
            </div>
            @endif

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Order Items</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($order->orderItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->product_sku }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ₦{{ number_format($item->unit_price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    ₦{{ number_format($item->total_price, 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No items found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="space-y-6">
            
            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order Number:</span>
                        <span class="font-medium">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Amount:</span>
                        <span class="font-bold text-lg">₦{{ number_format($order->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order Date:</span>
                        <span>{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($order->delivered_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Delivered:</span>
                        <span>{{ $order->delivered_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Customer Information</h2>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <p class="text-gray-900">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="text-gray-900">{{ $order->user->email }}</p>
                    </div>
                    @if($order->user->phone)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <p class="text-gray-900">{{ $order->user->phone }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Delivery Information</h2>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Address</label>
                    <p class="text-gray-900 bg-gray-50 p-3 rounded border">{{ $order->delivery_address }}</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
