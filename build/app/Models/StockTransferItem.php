<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransferItem extends Model
{
    protected $fillable = [
        'stock_transfer_id',
        'product_id',
        'variant_id',
        'quantity',
    ];

    // التحويل المرتبط به
    public function stockTransfer()
    {
        return $this->belongsTo(StockTransfer::class);
    }

    // المنتج المرتبط
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // المتغير (إن وجد)
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
