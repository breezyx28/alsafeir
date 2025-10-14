<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'tailoring_price',
        'fabrics_total',
        'total_before_discount',
        'discount_percentage',
        'total_after_discount',
        'total_paid',
        'remaining_amount',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
