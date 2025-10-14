<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class ProductionOrder extends Model
{
    use HasUuids;

    protected $fillable = [
        'reference', 'production_date', 'notes', 'quantity',
        'product_id', 'variant_id', 'branch_id',
    ];

    

    public function product()      { return $this->belongsTo(Product::class); }
    public function variant()      { return $this->belongsTo(ProductVariant::class); }
    public function branch()       { return $this->belongsTo(Branch::class); }
    public function materials()    { return $this->hasMany(ProductionMaterial::class); }




    
}

