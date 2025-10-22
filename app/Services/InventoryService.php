<?php

namespace App\Services;

use App\Models\{Product, ProductVariant, InventoryMovement};
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function adjust(Product $product, ?ProductVariant $variant, int $quantity, string $type, ?string $reason = null, ?int $userId = null): InventoryMovement
    {
        return DB::transaction(function () use ($product, $variant, $quantity, $type, $reason, $userId) {
            $previous = $variant ? $variant->stock_quantity : $product->stock_quantity;
            $new = match($type) {
                'in' => $previous + $quantity,
                'out' => $previous - $quantity,
                'adjustment' => $quantity,
                default => $previous,
            };

            if ($variant) {
                $variant->update(['stock_quantity' => $new]);
            } else {
                $product->update(['stock_quantity' => $new]);
            }

            return InventoryMovement::create([
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'type' => $type,
                'quantity' => $quantity,
                'previous_quantity' => $previous,
                'new_quantity' => $new,
                'reason' => $reason,
                'user_id' => $userId,
            ]);
        });
    }
}
