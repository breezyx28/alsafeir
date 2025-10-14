<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_id',
        'new_ready_sale_id',
        'exchange_date',
        'amount_paid_by_customer',
        'amount_refunded_to_customer',
        'user_id',
        'notes',
    ];

    protected $dates = ['exchange_date'];

    // العلاقات
    public function return()
    {
        return $this->belongsTo(SaleReturn::class, 'return_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function newSale()
    {
        return $this->belongsTo(ReadySale::class, 'new_ready_sale_id');
    }

    public function items()
    {
        return $this->hasMany(ExchangeItem::class);
    }
}
