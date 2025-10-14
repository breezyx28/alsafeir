<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadySale extends Model
{
    protected $fillable = [
        'invoice_number',
        'sale_date',
        'customer_id',
        'total_amount',
        'discount_amount',
        'net_amount',
        'payment_method',
        'payment_status',
        'user_id',
        'branch_id',
        'notes'
    ];

    public function items()
    {
        return $this->hasMany(ReadySaleItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
