<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchStock extends Model
{
    protected $fillable = [
        'branch_id',
        'product_id',
        'variant_id',
        'quantity',
    ];

    // الفرع الذي يمتلك هذا المخزون
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // المنتج
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // المتغير (لون/مقاس) - اختياري
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}

