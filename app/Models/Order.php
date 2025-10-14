<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'customer_id',
        'branch_id',
        'user_id',
        'order_number',
        'delivery_code',
        'order_date',
        'expected_delivery_date',
        'operator_delivery_date',
        'status',
        'notes',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // توليد رقم الطلب تلقائياً عند الإنشاء
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $latestOrder = static::latest('id')->first();
                $nextNumber = $latestOrder ? ((int)substr($latestOrder->order_number, 4)) + 1 : 1;
                $order->order_number = 'ORD-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }
        });
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

    public function payment()
    {
        return $this->hasOne(OrderPayment::class);
    }


    public function measurements()
    {
        // هذا ليس جدولاً مباشراً، بل هو علاقة عبر العميل
        // لكن للسهولة، سنتركه هكذا ونقوم بالربط في الكنترولر
        // إذا أردنا علاقة مباشرة، يجب إضافة order_id لجدول المقاسات
        // بناءً على تصميمنا، الطلب لا يحتوي على مقاسات مباشرة، بل العميل هو من يملكها
        // سنقوم بحفظ نسخة من المقاسات في جدول order_details إذا لزم الأمر
        // لكن للتبسيط الآن، سنعتبر أن الطلب يستخدم مقاسات العميل المحفوظة
        // **تحديث:** بناءً على طلبك الأخير، سنقوم بإنشاء المقاسات لكل طلب
        // لذا سنحتاج لتعديل جدول المقاسات ليشمل order_id
        // سأفترض أننا سنقوم بذلك لاحقاً ونكمل الآن
        // **تحديث نهائي:** تم تعديل الجداول لتكون المقاسات مرتبطة بالطلب
        // لذا هذه العلاقة صحيحة
        return $this->hasMany(Measurement::class);
    }


    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function installments()
    {
        return $this->hasMany(PaymentInstallment::class);
    }

    public function employee()
{
    return $this->belongsTo(Employee::class);
}


    // app/Models/Order.php

public static function generateOrderNumber(): string
{
    $prefix = 'ORD-' . now()->format('Ymd') . '-';

    $lastOrder = self::where('order_number', 'like', $prefix . '%')
        ->orderBy('created_at', 'desc')
        ->first();

    if ($lastOrder) {
        $lastNumber = (int) str_replace($prefix, '', $lastOrder->order_number);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
}

}
