<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderDeliveryController extends Controller
{


    public function today()
    {
        $today = Carbon::today();

        $orders = Order::with(['payment', 'customer'])
                    ->whereDate('expected_delivery_date', $today)
                    ->where('status', 'جاري التنفيذ') // ✅ شرط الحالة
                    ->orderBy('expected_delivery_date')
                    ->get();

        return view('custom-orders.print.delivery', [
            'orders' => $orders,
            'title' => 'طلبات اليوم (جاري التنفيذ)',
            'date' => $today->format('Y-m-d')
        ]);
    }

    public function tomorrow()
    {
        $tomorrow = Carbon::tomorrow();

        $orders = Order::with(['payment', 'customer'])
                    ->whereDate('expected_delivery_date', $tomorrow)
                    ->where('status', 'جاري التنفيذ') // ✅ شرط الحالة
                    ->orderBy('expected_delivery_date')
                    ->get();

        return view('custom-orders.print.delivery', [
            'orders' => $orders,
            'title' => 'طلبات الغد (جاري التنفيذ)',
            'date' => $tomorrow->format('Y-m-d')
        ]);
    }


public function operatorPrint(Request $request)
{
    $today = Carbon::today();
    $tomorrow = Carbon::tomorrow();

    $orders = Order::with('customer')
        ->where('status', 'جاري التنفيذ') // فقط الطلبات الجاري تنفيذها
        ->whereDate('expected_delivery_date', $today)
        ->orWhereDate('expected_delivery_date', $tomorrow)
        ->orderBy('expected_delivery_date')
        ->get();

    return view('custom-orders.print.operator', compact('orders'));
}

}
