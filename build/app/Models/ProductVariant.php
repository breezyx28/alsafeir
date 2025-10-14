<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'product_id', 'color', 'size', 'barcode', 'price_override','quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseItems()
        {
            return $this->hasMany(PurchaseOrderItem::class, 'variant_id');
        }
    public function branchStocks()
    {
        return $this->hasMany(BranchStock::class);
    }

    public function stockTransfers()
    {
        return $this->hasMany(StockTransfer::class);
    }



}

