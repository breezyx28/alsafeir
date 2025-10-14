<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderPrintController extends Controller
{
    // صفحة اختيار الفلاتر
    public function filter()
    {
        return view('custom-orders.print.filter');
    }

    // توليد كشف الطباعة حسب الفلاتر
    public function generate(Request $request)
    {
        $query = Order::with(['payment', 'customer'])->orderBy('order_date');

        // فلترة حسب الحالة
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // فلترة حسب التاريخ
        if ($request->date) {
            $query->whereDate('order_date', $request->date);
        }

        $orders = $query->get();

        return view('custom-orders.print.receipt_filter', compact('orders'));
    }
}
