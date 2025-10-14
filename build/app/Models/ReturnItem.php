<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    protected $fillable = [
        'return_id',
        'product_id',
        'variant_id',
        'quantity',
        'refund_amount',
        'condition',
    ];

    public function saleReturn()
    {
        return $this->belongsTo(SaleReturn::class, 'return_id');
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
