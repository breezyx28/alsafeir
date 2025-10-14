<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'name', 'sku', 'type', 'category_id', 'base_price', 'cost_price', 'unit', 'status',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function purchaseItems()
     {
       return $this->hasMany(PurchaseOrderItem::class);
     }
     public function distributions()
    {
        return $this->hasMany(Distribution::class);
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

