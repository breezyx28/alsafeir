<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_id',
        'detail_type',
        'quantity',
        'fabric_source',
        'length',
        'shoulder_width',
        'arm_length',
        'arm_width',
        'sides',
        'neck',
        'cuff_type',
        'fabric_detail',
        'pants_length',
        'pants_type',
        'pants_size',
        'buttons',
        'qitan',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

  public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
