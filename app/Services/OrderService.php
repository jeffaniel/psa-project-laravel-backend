<?php

namespace App\Services;

use App\Models\{Order, OrderItem, Product, ProductVariant, Customer};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function create(Customer $customer, array $payload): Order
    {
        return DB::transaction(function () use ($customer, $payload) {
            $order = Order::create([
                'order_number' => strtoupper(Str::random(12)),
                'customer_id' => $customer->id,
                'status' => 'pending',
                'subtotal' => 0,
                'tax_amount' => $payload['tax_amount'] ?? 0,
                'shipping_amount' => $payload['shipping_amount'] ?? 0,
                'discount_amount' => $payload['discount_amount'] ?? 0,
                'total_amount' => 0,
                'billing_address' => $payload['billing_address'] ?? [],
                'shipping_address' => $payload['shipping_address'] ?? [],
            ]);

            $subtotal = 0;
            foreach ($payload['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $variant = !empty($item['product_variant_id']) ? ProductVariant::find($item['product_variant_id']) : null;
                $unitPrice = $variant->price ?? $product->selling_price;
                $total = $unitPrice * $item['quantity'];
                $subtotal += $total;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'product_name' => $product->name,
                    'product_sku' => $variant->sku ?? $product->sku,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $total,
                    'product_details' => $variant?->attributes ?? [],
                ]);

                if ($variant) {
                    $variant->decrement('stock_quantity', $item['quantity']);
                } else {
                    $product->decrement('stock_quantity', $item['quantity']);
                }
            }

            $order->update([
                'subtotal' => $subtotal,
                'total_amount' => $subtotal + ($order->tax_amount) + ($order->shipping_amount) - ($order->discount_amount),
            ]);

            return $order->refresh();
        });
    }
}
