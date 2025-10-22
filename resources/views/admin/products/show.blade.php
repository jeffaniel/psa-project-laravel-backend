@extends('admin.layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Product Details</h1>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Product
            </a>
            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Product Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Product Images -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Product Images</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @if($product->images && count($product->images) > 0)
                        @foreach($product->images as $image)
                            <div class="aspect-square rounded-lg overflow-hidden bg-gray-100 relative group">
                                <img src="{{ asset('storage/' . $image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-red-50\'><div class=\'text-center p-2\'><svg class=\'w-12 h-12 text-red-400 mx-auto mb-2\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z\'/></svg><p class=\'text-xs text-red-600\'>Image not found</p></div></div>';">
                                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    {{ basename($image) }}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="aspect-square rounded-lg bg-gray-100 flex flex-col items-center justify-center p-4">
                            <svg class="w-16 h-16 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-gray-500 text-center">No images uploaded</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Product Name</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $product->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">SKU</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $product->sku }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Barcode</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $product->barcode ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $product->category->name ?? 'N/A' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Short Description</dt>
                        <dd class="mt-1 text-gray-900">{{ $product->short_description ?? 'N/A' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-gray-900">{{ $product->description ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Pricing -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Pricing</h2>
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Cost Price</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">₦{{ number_format($product->cost_price, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Selling Price</dt>
                        <dd class="mt-1 text-2xl font-bold text-green-600">₦{{ number_format($product->selling_price, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Discount Price</dt>
                        <dd class="mt-1 text-2xl font-bold text-red-600">
                            @if($product->discount_price)
                                ₦{{ number_format($product->discount_price, 2) }}
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>
                    @if($product->discount_price)
                    <div class="md:col-span-3">
                        <dt class="text-sm font-medium text-gray-500">Profit Margin</dt>
                        <dd class="mt-1 text-lg text-gray-900">
                            ₦{{ number_format($product->discount_price - $product->cost_price, 2) }}
                            ({{ number_format((($product->discount_price - $product->cost_price) / $product->cost_price) * 100, 2) }}%)
                        </dd>
                    </div>
                    @else
                    <div class="md:col-span-3">
                        <dt class="text-sm font-medium text-gray-500">Profit Margin</dt>
                        <dd class="mt-1 text-lg text-gray-900">
                            ₦{{ number_format($product->selling_price - $product->cost_price, 2) }}
                            ({{ number_format((($product->selling_price - $product->cost_price) / $product->cost_price) * 100, 2) }}%)
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Product Variants -->
            @if($product->variants && $product->variants->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Product Variants</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($product->variants as $variant)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $variant->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $variant->sku }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">₦{{ number_format($variant->price, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $variant->stock_quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Status & Stock -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Status & Stock</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 text-sm rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Stock Status</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 text-sm rounded-full 
                                {{ $product->stock_status === 'in_stock' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $product->stock_status === 'low_stock' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $product->stock_status === 'out_of_stock' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Current Stock</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $product->stock_quantity }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Min Stock Level</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $product->min_stock_level }}</dd>
                    </div>
                    @if($product->max_stock_level)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Max Stock Level</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $product->max_stock_level }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Track Inventory</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $product->track_inventory ? 'Yes' : 'No' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Additional Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Additional Info</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Featured</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $product->is_featured ? 'Yes' : 'No' }}</dd>
                    </div>
                    @if($product->weight)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Weight</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $product->weight }}</dd>
                    </div>
                    @endif
                    @if($product->dimensions)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dimensions</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $product->dimensions }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Views</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ number_format($product->views_count) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Rating</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $product->rating }} / 5.0 ({{ $product->reviews_count }} reviews)</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $product->updated_at->format('M d, Y h:i A') }}</dd>
                    </div>
                </dl>
            </div>

        </div>

    </div>

</div>
@endsection
