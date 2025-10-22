<?php

namespace App\Http\Controllers;

use App\Models\{Product, ProductVariant};
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(private InventoryService $svc) {}

    public function index() { return response()->json(['message' => 'Inventory overview (stub)']); }
    public function stockIn(Request $request) { return $this->adjust($request, 'in'); }
    public function stockOut(Request $request) { return $this->adjust($request, 'out'); }
    public function lowStock() { return response()->json(Product::whereColumn('stock_quantity','<=','min_stock_level')->get()); }
    public function movements() { return response()->json(['message' => 'List movements (stub)']); }
    public function valuation() { return response()->json(['message' => 'Inventory valuation (stub)']); }

    public function adjustStock(Request $request) { return $this->adjust($request, 'adjustment'); }

    private function adjust(Request $request, string $type)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer',
            'reason' => 'nullable|string',
        ]);
        $product = Product::findOrFail($data['product_id']);
        $variant = !empty($data['product_variant_id']) ? ProductVariant::find($data['product_variant_id']) : null;
        $movement = $this->svc->adjust($product, $variant, $data['quantity'], $type, $data['reason'] ?? null, $request->user()->id ?? null);
        return response()->json($movement, 201);
    }
}
