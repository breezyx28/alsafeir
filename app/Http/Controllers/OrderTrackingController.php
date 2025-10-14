<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'measurements'])
            // جلب الطلبات التي لم تكتمل بعد فقط
            ->whereIn('status', ['جاري التنفيذ', 'قيد المراجعة']);

        // فلتر البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        $orders = $query->latest()->paginate(20);

        // هنا نقوم بحساب نسبة الإنجاز لكل طلب
        $orders->getCollection()->transform(function ($order) {
            $totalItems = $order->measurements->count();
            if ($totalItems === 0) {
                $order->completion_percentage = 0;
                $order->completion_text = 'لا توجد بنود تفصيل';
                return $order;
            }

            $readyItems = $order->measurements->where('status', 'جاهزة في المشغل')->count();
            $order->completion_percentage = ($readyItems / $totalItems) * 100;
            $order->completion_text = "{$readyItems} من {$totalItems} قطعة جاهزة";

            return $order;
        });

        return view('tracking.index', compact('orders'));
    }
}
