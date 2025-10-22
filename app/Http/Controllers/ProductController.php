<?php

namespace App\Http\Controllers;

use App\Models\{Product, ProductVariant};
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $service) {}

    public function publicIndex(Request $request)
    {
        $query = Product::with(['category', 'variants'])
            ->where('is_active', true);
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('is_featured')) {
            $query->where('is_featured', true);
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $products = $query->paginate($request->get('per_page', 20));
        
        return response()->json($products);
    }

    public function publicShow($id)
    {
        $product = Product::with(['category', 'variants', 'supplier'])
            ->where('is_active', true)
            ->findOrFail($id);
            
        return response()->json($product);
    }

    public function index(Request $request)
    {
        return response()->json($this->service->list($request->all()));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'selling_price' => 'required|numeric',
        ]);
        $product = $this->service->create($request->all());
        return response()->json($product, 201);
    }

    public function show($id)
    {
        return response()->json(Product::with('variants')->findOrFail($id));
    }

    public function update(Request $request, Product $id)
    {
        $updated = $this->service->update($id, $request->all());
        return response()->json($updated);
    }

    public function destroy(Product $id)
    {
        $id->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function uploadImage(Request $request, Product $id)
    {
        return response()->json(['message' => 'Image uploaded (stub)']);
    }

    public function getVariants(Product $id)
    {
        return response()->json($id->variants);
    }

    public function addVariant(Request $request, Product $id)
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
        ]);
        $variant = $id->variants()->create($request->all());
        return response()->json($variant, 201);
    }

    public function updateVariant(Request $request, $variantId)
    {
        $variant = ProductVariant::findOrFail($variantId);
        $variant->update($request->all());
        return response()->json($variant);
    }

    public function deleteVariant($variantId)
    {
        ProductVariant::findOrFail($variantId)->delete();
        return response()->json(['message' => 'Variant deleted']);
    }
}
