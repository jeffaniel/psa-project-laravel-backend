<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, User};
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,delivered,cancelled',
            'payment_status' => 'required|in:pending,success,failed',
            'delivery_address' => 'nullable|string',
        ]);

        // If payment is approved and status is still pending, move to processing
        if ($validated['payment_status'] === 'success' && $order->status === 'pending') {
            $validated['status'] = 'processing';
        }

        // If marking as delivered, set delivered_at timestamp
        if ($validated['status'] === 'delivered' && $order->status !== 'delivered') {
            $validated['delivered_at'] = now();
        }

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully');
    }

    /**
     * Approve payment receipt
     */
    public function approvePayment(Order $order)
    {
        $order->update([
            'payment_status' => 'success',
            'status' => 'processing' // Automatically move to processing when payment approved
        ]);

        return back()->with('success', 'Payment approved! Order moved to processing.');
    }

    /**
     * Reject payment receipt
     */
    public function rejectPayment(Request $request, Order $order)
    {
        $order->update([
            'payment_status' => 'failed'
        ]);

        return back()->with('error', 'Payment rejected. Customer will need to upload a new receipt.');
    }

    /**
     * Mark order as delivered
     */
    public function markDelivered(Order $order)
    {
        $order->update([
            'status' => 'delivered',
            'delivered_at' => now()
        ]);

        return back()->with('success', 'Order marked as delivered!');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
        ]);

        $order->update($validated);

        return back()->with('success', 'Order status updated successfully');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully');
    }

    public function create()
    {
        // Not typically used in admin, but included for completeness
        $customers = Customer::all();
        return view('admin.orders.create', compact('customers'));
    }

    public function store(Request $request)
    {
        // Implementation for manual order creation if needed
        return redirect()->route('admin.orders.index');
    }
}
