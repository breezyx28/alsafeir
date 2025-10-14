<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Employee;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\BranchStock;
use App\Models\Measurement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\JournalEntry;
use App\Models\Account;
use App\Models\JournalEntryItem;

class OrderWizardController extends Controller
{
    // ... (الدالتان showStep1 و processStep1 تبقى كما هي) ...
    public function showStep1()
    {
        return view('custom-orders.wizard.step1');

    }

    public function processStep1(Request $request)
    {
        $rules = [
                'new_customer.name' => 'required_without:customer_id|string|max:255',
                'new_customer.phone_primary' => 'required_without:customer_id|string|max:20|unique:customers,phone_primary',
                'new_customer.phone_secondary' => 'nullable|string|max:20',
                'new_customer.customer_level' => 'required_without:customer_id|string',
            ];

            // أضف قاعدة التحقق لـ customer_id فقط إذا لم يكن هناك عميل جديد
            if (!$request->filled('new_customer.name')) {
                $rules['customer_id'] = 'required|exists:customers,id';
            }

             $validated = $request->validate($rules);

        $customerId = $request->customer_id;
        if (is_null($customerId)) {
            $customer = Customer::create($validated['new_customer']);
            $customerId = $customer->id;
        }

        $order = Order::create([
            'customer_id' => $customerId,
            'branch_id' => Auth::user()->employee->branch_id,
            'user_id' => Auth::id(),
            'status' => 'draft',
        ]);

        $request->session()->put('order.wizard.order_id', $order->id);

        return redirect()->route('order.wizard.step2', $order); 
    }


    // --- الدوال  للخطوة الثانية ---

    public function showStep2(Order $order)
    {
        // التأكد من أن الطلب الذي يتم تعديله هو نفسه الموجود في الـ Session
        if (session('order.wizard.order_id') !== $order->id) {
            return redirect()->route('order.wizard.step1')->with('error', 'حدث خطأ، يرجى البدء من جديد.');
        }

        return view('custom-orders.wizard.step2', compact('order'));
    }

    /**
     * معالجة الخطوة الثانية: تحديث تفاصيل الطلب.
     */
    public function processStep2(Request $request, Order $order)
    {
        if (session('order.wizard.order_id') !== $order->id) {
            return redirect()->route('order.wizard.step1')->with('error', 'حدث خطأ، يرجى البدء من جديد.');
        }

        $validated = $request->validate([
            'order_date' => 'required|date',
            'expected_delivery_date' => 'required|date|after_or_equal:order_date',
            'operator_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'status' => 'required|string|in:جاري التنفيذ,جاهز للتسليم,تم التسليم', // تحديد الحالات المسموح بها
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        // سيتم إنشاء هذه الواجهة والراوت في الخطوة التالية
        return redirect()->route('order.wizard.step3', $order);
    }


public function showStep3(Order $order)
{
    if (session('order.wizard.order_id') !== $order->id) {
        return redirect()->route('order.wizard.step1')->with('error', 'حدث خطأ، يرجى البدء من جديد.');
    }
     $employees = Employee::all();
    $fabrics = Product::where('type', 'raw_material')->with('variants.product')->get();

    // تمرير البيانات بشكل آمن
    return view('custom-orders.wizard.step3', [
        'order' => $order,
        'employees' => $employees,
        'fabrics_json' => $fabrics->toJson(),
        'old_data_json' => json_encode([
            'measurements' => old('measurements', []),
            'products' => old('products', []),
        ]),
         'customer_id' => $order->customer_id,
    ]);
}

public function processStep3(Request $request, Order $order)
{
    // ... (منطق الحفظ يبقى كما هو، فهو سليم) ...
    if (session('order.wizard.order_id') !== $order->id) {
        return redirect()->route('order.wizard.step1')->with('error', 'حدث خطأ، يرجى البدء من جديد.');
    }

    $validated = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'measurements' => 'required|array|min:1',
        'measurements.*.detail_type' => 'required|string',
        'measurements.*.fabric_source' => 'required|string',
        'measurements.*.quantity' => 'required|integer|min:1',
        'measurements.*.length' => 'nullable|string',
        'measurements.*.shoulder_width' => 'nullable|string',
        'measurements.*.arm_length' => 'nullable|string',
        'measurements.*.arm_width' => 'nullable|string',
        'measurements.*.sides' => 'nullable|string',
        'measurements.*.neck' => 'nullable|string',
        'measurements.*.cuff_type' => 'nullable|string',
        'measurements.*.fabric_detail' => 'nullable|string',
        'measurements.*.pants_length' => 'nullable|string',
        'measurements.*.pants_type' => 'nullable|string',
        'measurements.*.pants_size' => 'nullable|string',
        'measurements.*.buttons' => 'nullable|string',
        'measurements.*.qitan' => 'nullable|string',
        'products' => 'nullable|array',
        'products.*.variant_id' => 'required_with:products|exists:product_variants,id',
        'products.*.quantity' => 'required_with:products|numeric|min:0.1',
        'products.*.status' => 'required_with:products|string',
        'products.*.notes' => 'nullable|string',
        
    ]);

    try {
        DB::transaction(function () use ($validated, $order) {
             // تحديث الموظف المسؤول عن الطلب
            $order->employee_id = $validated['employee_id'];
            $order->save();
            // $order->measurements()->delete();
            $order->products()->delete();
            foreach ($validated['measurements'] as $m) {
                $m['customer_id'] = $order->customer_id;
                 $m['order_id'] = $order->id;
                 Measurement::updateOrCreate(
                [
                    'customer_id' => $order->customer_id,
                    'detail_type' => $m['detail_type'],
                    'order_id' => $order->id,
                ],
                $m
            );
            }
            if (isset($validated['products'])) {
                foreach ($validated['products'] as $p) {
                    $variant = ProductVariant::with('product')->find($p['variant_id']);
                    $stock = BranchStock::where('branch_id', $order->branch_id)->where('variant_id', $variant->id)->first();
                    if (!$stock || $stock->quantity < $p['quantity']) {
                        throw new Exception("كمية القماش '{$variant->product->name} - {$variant->color}' غير كافية.");
                    }
                    $order->products()->create([
                        'product_variant_id' => $variant->id,
                        'quantity' => $p['quantity'],
                        'price_per_meter' => $variant->price_override ?? $variant->product->price,
                        'status' => $p['status'] ?? 'جاري التنفيذ',
                        'notes' => $p['notes'] ?? null,
                    ]);
                    $stock->decrement('quantity', $p['quantity']);
                }
            }
        });
    } catch (Exception $e) {
        return back()->with('error', $e->getMessage())->withInput();
    }
    return redirect()->route('order.wizard.step4', $order);
}

     public function showStep4(Order $order)
    {
        if (session('order.wizard.order_id') !== $order->id) {
            return redirect()->route('order.wizard.step1')->with('error', 'حدث خطأ، يرجى البدء من جديد.');
        }

        // جلب كل البيانات المرتبطة بالطلب لعرضها
        $order->load(['customer', 'branch', 'measurements', 'products.variant.product']);

        return view('custom-orders.wizard.step4', compact('order'));
    }

    /**
     * معالجة الخطوة الرابعة: حفظ الماليات وتأكيد الطلب.
     */
  public function processStep4(Request $request, Order $order)
{
    if (session('order.wizard.order_id') !== $order->id) {
        return redirect()->route('order.wizard.step1')->with('error', 'انتهت جلسة الطلب، يرجى البدء من جديد.');
    }

    $validated = $request->validate([
        'payment.tailoring_price' => 'required|numeric|min:0',
        'payment.discount_percentage' => 'required|numeric|min:0|max:100',
        'payment.paid' => 'required|numeric|min:0',
        'payment.payment_method' => 'required|string',
        'payment.transaction_number' => 'nullable|string',
    ]);

    try {
        DB::transaction(function () use ($validated, $order, $request) {
            // 1. إجمالي الأقمشة
            $fabricsTotal = $order->products()->sum(DB::raw('price_per_meter * quantity'));

            // 2. حساب الماليات
            $paymentData = $validated['payment'];
            $tailoringPrice = (float)$paymentData['tailoring_price'];
            $totalBeforeDiscount = $fabricsTotal + $tailoringPrice;
            $discountPercentage = (float)$paymentData['discount_percentage'];
            $totalAfterDiscount = $totalBeforeDiscount * (1 - ($discountPercentage / 100));
            $paidAmount = (float)$paymentData['paid'];

            if ($paidAmount > $totalAfterDiscount + 0.001) {
                throw new Exception('المبلغ المدفوع لا يمكن أن يكون أكبر من الإجمالي بعد الخصم.');
            }

            // 3. حفظ سجل المدفوعات
            $order->payment()->updateOrCreate([], [
                'tailoring_price' => $tailoringPrice,
                'fabrics_total' => $fabricsTotal,
                'total_before_discount' => $totalBeforeDiscount,
                'discount_percentage' => $discountPercentage,
                'total_after_discount' => $totalAfterDiscount,
                'total_paid' => $paidAmount,
                'remaining_amount' => $totalAfterDiscount - $paidAmount,
            ]);

            // 4. تحديد الحسابات المحاسبية
            $fabricsAccount   = Account::where('code', '4200')->firstOrFail(); // مبيعات الأقمشة
            $tailoringAccount = Account::where('code', '4300')->firstOrFail(); // إيرادات مشغل
            $cashAccount      = Account::where('code', $paymentData['payment_method'] == 'cash' ? '1100' : '1200')->firstOrFail();

            // 5. توزيع المدفوع فقط
            $fabricsShare   = $fabricsTotal > 0 ? ($fabricsTotal / $totalAfterDiscount) * $paidAmount : 0;
            $tailoringShare = $tailoringPrice > 0 ? ($tailoringPrice / $totalAfterDiscount) * $paidAmount : 0;

            // 6. إنشاء القيد المحاسبي للمدفوع فقط
            if ($paidAmount > 0) {
                $journal = JournalEntry::create([
                    'entry_date' => now(),
                    'description' => "دفعة على الطلب رقم {$order->order_number}",
                    'created_by' => Auth::id(),
                ]);

                $items = [];

                if ($fabricsShare > 0) {
                    $items[] = [
                        'account_id' => $cashAccount->id,
                        'debit' => $fabricsShare,
                        'credit' => 0,
                        'notes' => "دفعة نقدية/بنك عن الأقمشة للعميل {$order->customer->name}",
                    ];
                    $items[] = [
                        'account_id' => $fabricsAccount->id,
                        'debit' => 0,
                        'credit' => $fabricsShare,
                        'notes' => "إيراد مبيعات أقمشة للطلب رقم {$order->order_number}",
                    ];
                }

                if ($tailoringShare > 0) {
                    $items[] = [
                        'account_id' => $cashAccount->id,
                        'debit' => $tailoringShare,
                        'credit' => 0,
                        'notes' => "دفعة نقدية/بنك عن التفصيل للعميل {$order->customer->name}",
                    ];
                    $items[] = [
                        'account_id' => $tailoringAccount->id,
                        'debit' => 0,
                        'credit' => $tailoringShare,
                        'notes' => "إيراد مشغل للطلب رقم {$order->order_number}",
                    ];
                }

                $journal->items()->createMany($items);
            }

            // 7. حذف الدفعات القديمة وتسجيل الدفعة الجديدة
            $order->installments()->delete();
            if ($paidAmount > 0) {
                $order->installments()->create([
                    'user_id' => Auth::id(),
                    'amount' => $paidAmount,
                    'payment_method' => $paymentData['payment_method'],
                    'transaction_number' => $paymentData['transaction_number'],
                ]);
            }

            // 8. تحديث حالة الطلب
            $order->status = 'جاري التنفيذ';
            $order->save();
        });

        // مسح الجلسة بعد نجاح العملية
        session()->forget('order.wizard');

        return redirect()->route('custom-orders.show', $order)->with('success', "تم إنشاء الطلب رقم {$order->order_number} بنجاح!");

    } catch (Exception $e) {
        return back()->with('error', $e->getMessage())->withInput();
    }
}



    }
