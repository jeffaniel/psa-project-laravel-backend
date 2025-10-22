<?php

namespace App\Services;

use App\Models\{Product, ProductVariant};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function list(array $filters = [])
    {
        $q = Product::query();
        if (!empty($filters['search'])) {
            $q->where('name', 'like', '%'.$filters['search'].'%');
        }
        if (!empty($filters['category_id'])) {
            $q->where('category_id', $filters['category_id']);
        }
        return $q->paginate(20);
    }

    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = $data['slug'] ?? Str::slug($data['name']).'-'.Str::random(5);
            $product = Product::create($data);
            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $variant) {
                    $variant['sku'] = $variant['sku'] ?? strtoupper(Str::random(10));
                    $product->variants()->create($variant);
                }
            }
            return $product;
        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $product->update($data);
            return $product->refresh();
        });
    }
}
