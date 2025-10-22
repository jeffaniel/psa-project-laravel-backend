<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','slug','description','short_description','sku','barcode','category_id','supplier_id','cost_price','selling_price','discount_price','discount_type','stock_quantity','min_stock_level','max_stock_level','stock_status','track_inventory','weight','dimensions','images','attributes','is_featured','is_active','meta_title','meta_description','tags','views_count','rating','reviews_count'
    ];

    protected $casts = [
        'images' => 'array',
        'attributes' => 'array',
        'tags' => 'array',
        'track_inventory' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_urls', 'formatted_price', 'formatted_cost_price', 'formatted_discount_price'];

    public function getImageUrlsAttribute(): array
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }

        return array_map(function($image) {
            if (str_starts_with($image, 'http')) {
                return $image;
            }
            return url('storage/' . $image);
        }, $this->images);
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format((float)$this->selling_price, 2);
    }

    public function getFormattedCostPriceAttribute(): ?string
    {
        return $this->cost_price ? number_format((float)$this->cost_price, 2) : null;
    }

    public function getFormattedDiscountPriceAttribute(): ?string
    {
        return $this->discount_price ? number_format((float)$this->discount_price, 2) : null;
    }

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function variants(): HasMany { return $this->hasMany(ProductVariant::class); }


}
