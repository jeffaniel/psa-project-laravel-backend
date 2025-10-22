<?php

namespace App\Http\Controllers;

use App\Models\{Order, OrderItem, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'delivery_address' => 'required|string|max:1000',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(
            Order::whereYear('created_at', date('Y'))->count() + 1, 
            6, '0', STR_PAD_LEFT
        );

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => auth()->id(),
            'status' => 'pending',
            'amount' => $validated['total_amount'],
            'payment_status' => 'pending',
            'delivery_address' => $validated['delivery_address'],
        ]);

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['product_variant_id'] ?? null,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['unit_price'] * $item['quantity'],
                'product_details' => json_encode([
                    'description' => $product->description,
                    'image' => $product->image,
                ])
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully',
            'order' => $order->load('orderItems')
        ], 201);
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->load('orderItems.product');

        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }

    public function uploadReceipt(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'payment_receipt' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        try {
            $file = $request->file('payment_receipt');
            $filename = $order->order_number . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('receipts', $filename, 'public');

            $order->update([
                'payment_receipt' => $path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Receipt uploaded successfully',
                'receipt_path' => $path
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload receipt'
            ], 500);
        }
    }
}
