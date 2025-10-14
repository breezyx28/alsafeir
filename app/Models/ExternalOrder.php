<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'status',
        'total_budget',
        'notes',
    ];

    // العلاقة مع العميل
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // العلاقة مع العناصر (الأنواع المختارة)
    public function items()
    {
        return $this->hasMany(ExternalOrderItem::class);
    }

    // العلاقة مع سجلات الحالة
    public function statusLogs()
    {
        return $this->hasMany(ExternalOrderStatusLog::class);
    }
}
