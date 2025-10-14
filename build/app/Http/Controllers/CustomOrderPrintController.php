<?php

namespace App\Http\Controllers;

use App\Models\CustomOrder;
use Illuminate\Http\Request;

class CustomOrderPrintController extends Controller
{
    public function index()
    {
        return view('custom-orders.print.filter'); // صفحة الفلاتر
    }

    public function print(Request $request)
    {
        $query = CustomOrder::with('payment', 'customer');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date) {
            $query->whereDate('order_date', $request->date);
        }

        $orders = $query->get();

        return view('custom-orders.print.receipt', compact('orders', 'request'));
    }
}
