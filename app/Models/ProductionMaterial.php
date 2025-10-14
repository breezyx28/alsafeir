<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class ProductionMaterial extends Model
{
    use HasUuids;

    protected $fillable = [
        'production_order_id', 'product_id', 'variant_id',
        'quantity', 'unit',
    ];

    public function productionOrder() { return $this->belongsTo(ProductionOrder::class); }
    public function product()         { return $this->belongsTo(Product::class); }
    public function variant()         { return $this->belongsTo(ProductVariant::class); }
}

