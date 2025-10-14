<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalOrderStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_order_id',
        'status',
        'user_id',
    ];

    // العلاقة مع الطلب
    public function order()
    {
        return $this->belongsTo(ExternalOrder::class, 'external_order_id');
    }

    // العلاقة مع المستخدم (الذي قام بتحديث الحالة)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
