<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'supplier_id',
        'return_date',
        'total_amount',
        'refund_type',
        'notes',
    ];

    // الفاتورة المرتبطة
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    // المورد المرتبط
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // تفاصيل الأصناف المرتجعة
    public function items()
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }
}
