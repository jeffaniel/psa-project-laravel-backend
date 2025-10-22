<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','product_variant_id','type','quantity','previous_quantity','new_quantity','unit_cost','reference_type','reference_id','reason','user_id','notes'];

    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
