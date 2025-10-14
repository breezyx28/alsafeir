<?php

// app/Models/PurchaseOrder.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'supplier_id',
        'order_date',
        'reference',
        'notes',
        'status',
        'total', // <-- 1. إضافة الحقل هنا
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total' => 'decimal:2', // <-- 2. إضافة خاصية التحويل هنا
        'order_date' => 'date',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }

    public function purchaseReturns()
    {
        return $this->hasMany(PurchaseReturn::class);
    }
}
