<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_order_id',
        'detail_type',
        'quantity',
        'height',
        'weight',
        'suggested_colors',
        'budget',
    ];

    // العلاقة مع الطلب
    public function order()
    {
        return $this->belongsTo(ExternalOrder::class, 'external_order_id');
    }
}
