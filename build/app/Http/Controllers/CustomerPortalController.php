<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Offer;

class CustomerPortalController extends Controller
{
    /**
     * عرض صفحة إدخال رقم الهاتف.
     */
    public function showPhoneEntryForm()
    {
        return view('portal.phone-entry');
    }

    /**
     * التحقق من رقم الهاتف وعرض صفحة الطلبات.
     * (ملاحظة: سنبسط العملية الآن بدون OTP، ويمكن إضافته لاحقاً)
     */
    public function showOrders(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|exists:customers,phone_primary',
        ], [
            'phone.exists' => 'عفواً، هذا الرقم غير مسجل لدينا. يرجى التأكد من الرقم أو التواصل مع المحل.'
        ]);

        $customer = Customer::where('phone_primary', $request->phone)->firstOrFail();

        // جلب كل طلبات العميل مع العلاقات اللازمة
        $orders = $customer->orders()
            ->with(['measurements', 'payment'])
            ->latest()
            ->get();

        // حساب نسبة الإنجاز لكل طلب (نفس المنطق الذي استخدمناه سابقاً)
        $orders->transform(function ($order) {
            $totalItems = $order->measurements->count();
            if ($totalItems > 0) {
                $readyItems = $order->measurements->where('status', 'جاهزة في المشغل')->count();
                $order->completion_percentage = ($readyItems / $totalItems) * 100;
            } else {
                $order->completion_percentage = 0;
            }
            return $order;
        });

        return view('portal.show-orders', compact('customer', 'orders'));
    }



// ... (أضف هذه الدالة الجديدة)
    public function showOffersPage()
    {
        $offers = Offer::where('is_active', true)->latest()->get();
        return view('portal.offers', compact('offers'));
    }

}
