<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'exchange_id',
        'product_id',
        'variant_id',
        'quantity',
        'amount',
    ];

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
