<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
 protected $table = 'sale_returns';

    protected $fillable = [
        'ready_sale_id',
        'return_date',
        'total_refund_amount',
        'reason',
        'status',
        'user_id',
        'notes',
    ];

    public function sale()
    {
        return $this->belongsTo(ReadySale::class, 'ready_sale_id');
    }

    public function items()
    {
        return $this->hasMany(ReturnItem::class, 'return_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}



