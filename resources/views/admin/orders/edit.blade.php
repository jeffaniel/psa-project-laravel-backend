@extends('admin.layouts.app')

@section('title', 'Edit Order - ' . $order->order_number)

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Order #{{ $order->order_number }}</h1>
            <p class="text-gray-600">Update order status and delivery information</p>
        </div>
        <a href="{{ route('admin.orders.show', $order) }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
            ← Back to Order
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Edit Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-6">Order Information</h2>
                
                <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Order Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <!-- Payment Status -->
                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                            <select name="payment_status" id="payment_status" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="success" {{ $order->payment_status === 'success' ? 'selected' : '' }}>Success</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="mt-6">
                        <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">Delivery Address</label>
                        <textarea name="delivery_address" id="delivery_address" rows="4" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Enter delivery address">{{ old('delivery_address', $order->delivery_address) }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex gap-4">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                            💾 Update Order
                        </button>
                        <a href="{{ route('admin.orders.show', $order) }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="space-y-6">
            
            <!-- Current Order Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Current Order Info</h2>
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
                        <span class="text-gray-600">Current Status:</span>
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment Status:</span>
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium
                            @if($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->payment_status === 'success') bg-green-100 text-green-800
                            @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->payment_status) }}
                        </span>
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

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    
                    @if($order->payment_receipt && $order->payment_status === 'pending')
                        <form method="POST" action="{{ route('admin.orders.approve-payment', $order) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors"
                                    onclick="return confirm('Approve this payment?')">
                                ✅ Approve Payment
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.orders.reject-payment', $order) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors"
                                    onclick="return confirm('Reject this payment?')">
                                ❌ Reject Payment
                            </button>
                        </form>
                    @endif

                    @if($order->status === 'processing')
                        <form method="POST" action="{{ route('admin.orders.mark-delivered', $order) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
                                    onclick="return confirm('Mark this order as delivered?')">
                                🚚 Mark as Delivered
                            </button>
                        </form>
                    @endif

                    @if($order->payment_receipt)
                        <a href="{{ Storage::url($order->payment_receipt) }}" 
                           target="_blank" 
                           class="block w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors text-center">
                            📄 View Receipt
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
