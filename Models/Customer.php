<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','first_name','last_name','email','phone','date_of_birth','gender','billing_address','billing_city','billing_state','billing_country','billing_postal_code','shipping_address','shipping_city','shipping_state','shipping_country','shipping_postal_code','customer_type','credit_limit','total_spent','total_orders','last_order_date','status','notes'
    ];

    protected $casts = [
        'last_order_date' => 'datetime',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function orders(): HasMany { return $this->hasMany(Order::class); }
}
