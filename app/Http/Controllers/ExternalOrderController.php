<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ExternalOrder;
use App\Models\ExternalOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExternalOrderController extends Controller
{
    // --- الخطوة 1: إنشاء أو تحديث العميل ---
    public function showStep1()
    {
        return view('external-orders.step1');
    }

    public function processStep1(Request $request)
    {
        $rules = [
            'new_customer.name' => 'required_without:customer_id|string|max:255',
            'new_customer.phone_primary' => 'required_without:customer_id|string|max:20|unique:customers,phone_primary',
            'new_customer.phone_secondary' => 'nullable|string|max:20',
        ];

        if (!$request->filled('new_customer.name')) {
            $rules['customer_id'] = 'required|exists:customers,id';
        }

        $validated = $request->validate($rules);

        $customerId = $request->customer_id;
        if (is_null($customerId)) {
            $customer = Customer::create($validated['new_customer']);
            $customerId = $customer->id;
        } else {
            $customer = Customer::find($customerId);
            $customer->update($validated['new_customer'] ?? []);
        }

        // إنشاء الطلب
        $order = ExternalOrder::create([
            'customer_id' => $customerId,
            'status' => 'جاري التنفيذ',
        ]);

        $request->session()->put('external_order.wizard.order_id', $order->id);

        return redirect()->route('external-orders.step2', $order);
    }

    // --- الخطوة 2: إدخال تفاصيل الطلب (المقاسات، الألوان، الكمية) ---
    public function showStep2(ExternalOrder $order)
    {
        if (session('external_order.wizard.order_id') !== $order->id) {
            return redirect()->route('external-orders.step1')->with('error', 'جلسة الطلب انتهت، يرجى البدء من جديد.');
        }

        return view('external-orders.step2', compact('order'));
    }

    public function processStep2(Request $request, ExternalOrder $order)
    {
        if (session('external_order.wizard.order_id') !== $order->id) {
            return redirect()->route('external-orders.step1')->with('error', 'جلسة الطلب انتهت، يرجى البدء من جديد.');
        }

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.detail_type' => 'required|string|in:جلابية,سروال,على الله,عراقي',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.height' => 'required|numeric|min:50|max:250',
            'items.*.weight' => 'required|numeric|min:20|max:200',
            'items.*.budget' => 'nullable|numeric|min:0',
            'items.*.suggested_colors' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($order, $validated) {
            // مسح العناصر القديمة
            $order->items()->delete();

            // إضافة العناصر الجديدة
            foreach ($validated['items'] as $item) {
                $order->items()->create($item);
            }
        });

        return redirect()->route('external-orders.show', $order)->with('success', 'تم حفظ تفاصيل الطلب بنجاح.');
    }

    // --- عرض الطلب للعميل ---
    public function show(ExternalOrder $order)
    {
        $order->load('items', 'customer');
        return view('external-orders.show', compact('order'));
    }
}
