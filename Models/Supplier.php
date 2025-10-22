<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','company_name','email','phone','address','city','state','country','postal_code','tax_number','contact_person','contact_phone','contact_email','status','credit_limit','payment_terms','bank_details','notes'
    ];

    protected $casts = [
        'bank_details' => 'array',
    ];

    public function products(): HasMany { return $this->hasMany(Product::class); }
}
