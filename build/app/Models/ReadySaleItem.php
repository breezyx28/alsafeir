<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadySaleItem extends Model
{
    protected $fillable = [
        'ready_sale_id',
        'product_id',
        'variant_id',
        'quantity',
        'unit_price',
        'sub_total'
    ];

    public function sale()
    {
        return $this->belongsTo(ReadySale::class, 'ready_sale_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
