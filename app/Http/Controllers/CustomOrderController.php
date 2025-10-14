<?php

namespace App\Http\Controllers;

use App\Models\BranchStock;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
 use App\Models\JournalEntry;
 use App\Models\Account;
use App\Models\JournalEntryItem;

class CustomOrderController extends Controller
{
    /**
     * =================================================================
     *                  القسم الرئيسي: عرض وإدارة الطلبات
     * =================================================================
     */

    /**
     * عرض قائمة كل الطلبات مع البحث والفلترة.
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'branch', 'payment'])->latest();

        // فلترة بناءً على الفرع الخاص بالموظف (إذا لم يكن مدير عام)
        $user = Auth::user();
        if (!$user->hasRole('super-admin')) { // افتراض أن لديك نظام صلاحيات
            $query->where('branch_id', $user->employee->branch_id);
        }

        // البحث بالاسم، رقم الهاتف، أو رقم الطلب
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($q) => 
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone_primary', 'like', "%{$search}%")
                  );
            });
        }

        // الفلترة بالحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(200)->withQueryString(); // للحفاظ على الفلاتر عند التنقل بين الصفحات
           
        return view('custom-orders.index', compact('orders'));
    }

    /**
     * عرض تفاصيل طلب معين.
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'branch', 'payment', 'measurements', 'products.variant.product', 'installments.user']);
        return view('custom-orders.show', compact('order'));
    }

    

    /**
     * عرض قائمة الطلبات التي تم تسليمها فقط (الأرشيف).
     */
    public function deliveredIndex(Request $request)
    {
        $query = Order::with(['customer', 'branch', 'payment'])
                      ->where('status', 'تم التسليم') // <-- الفلتر الحاسم هنا
                      ->latest();

        // نفس منطق البحث القوي
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('phone_primary', 'like', "%{$search}%"));
            });
        }

        $orders = $query->paginate(20);

        return view('custom-orders.delivered', compact('orders'));
    }

    
     /**
     * =================================================================
     *                  القسم الثاني: تحديث الطلبات
     * =================================================================
     */

    /**
     * تحديث حالة الطلب بذكاء.
     */
    public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required|string|in:جاهز للتسليم,تم التسليم',
    ]);

    $newStatus = $request->status;

    try {
        DB::transaction(function () use ($order, $newStatus) {
            if ($newStatus === 'جاهز للتسليم') {
                $allProductsReady = $order->products()->where('status', '!=', 'جاهز')->doesntExist();
                if (!$allProductsReady) {
                    throw new Exception('لا يمكن تحديث الحالة، بعض الأقمشة ما زالت غير جاهزة.');
                }

                // توليد رقم تسليم إذا ما موجود
                if (is_null($order->delivery_code)) {
                    $latestCode = Order::whereNotNull('delivery_code')
                         ->lockForUpdate()
                        ->orderBy('delivery_code', 'desc')
                        ->first();
                    $nextNumber = $latestCode ? (int) substr($latestCode->delivery_code, 4) + 1 : 1;
                    $order->delivery_code = 'DLV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                }
            }

            if ($newStatus === 'تم التسليم') {
                if ($order->payment->remaining_amount > 0.01) {
                    throw new Exception('لا يمكن تسليم الطلب، ما زال هناك مبلغ متبقي.');
                }
            }

            $order->status = $newStatus;
            $order->save();
        });

        // هنا الفرق 👇
        if ($newStatus === 'جاهز للتسليم') {
            return redirect()
                ->route('custom-orders.show', $order)
                ->with('success', "تم تحديث حالة الطلب إلى '{$newStatus}' بنجاح.")
                ->with('print_receipt', true);
        }

        return redirect()
            ->route('custom-orders.show', $order)
            ->with('success', "تم تحديث حالة الطلب إلى '{$newStatus}' بنجاح.");

    } catch (Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}


    /**
     * تسجيل دفعة جديدة للطلب.
     */
    public function recordPayment(Request $request, Order $order)
{
    $validated = $request->validate([
        'amount' => ['required', 'numeric', 'min:0.01', function ($attribute, $value, $fail) use ($order) {
            if ($value > $order->payment->remaining_amount + 0.001) {
                $fail('المبلغ المدفوع أكبر من المطلوب.');
            }
        }],
        'payment_method' => 'required|string',
        'transaction_number' => 'nullable|string',
    ]);

    try {
        DB::transaction(function () use ($order, $validated) {
            $payment = $order->payment;

            // 1. تسجيل الدفعة
            $order->installments()->create([
                'user_id' => Auth::id(),
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'transaction_number' => $validated['transaction_number'],
            ]);

            $payment->total_paid += $validated['amount'];
            $payment->remaining_amount -= $validated['amount'];
            $payment->save();

            // 2. تسجيل القيد المحاسبي
            $revenueAccount = \App\Models\Account::where('code', '4200')->firstOrFail();
            $cashAccount = $validated['payment_method'] === 'cash' 
                ? \App\Models\Account::where('code', '1100')->firstOrFail()
                : \App\Models\Account::where('code', '1200')->firstOrFail();

            $journalEntry = \App\Models\JournalEntry::create([
                'entry_date' => now(),
                'description' => "دفعة طلب رقم {$order->order_number}",
                'created_by' => Auth::id(),
            ]);

            $journalEntry->items()->createMany([
                [
                    'account_id' => $cashAccount->id,
                    'debit' => $validated['amount'],
                    'credit' => 0,
                    'notes' => "دفعة {$validated['payment_method']} من العميل {$order->customer->name}",
                ],
                [
                    'account_id' => $revenueAccount->id,
                    'debit' => 0,
                    'credit' => $validated['amount'],
                    'notes' => "إيراد مبيعات تفصيل للطلب رقم {$order->order_number}",
                ],
            ]);
        });

        return back()->with('success', 'تم تسجيل الدفعة بنجاح.');
    } catch (\Exception $e) {
        return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
    }
}


    /**
     * =================================================================
     *                  القسم الثالث: الطباعة والحذف
     * =================================================================
     */

    /**
     * عرض صفحة طباعة بطاقة المقاسات للمشغل.
     */
    public function printMeasurements(Order $order)
    {
        $order->load(['customer', 'measurements', 'products.variant.product']);
        return view('print.measurements', compact('order'));
    }

    /**
     * عرض صفحة طباعة الفاتورة المالية للعميل.
     */
    public function printInvoice(Order $order)
    {
        $order->load(['customer', 'branch', 'payment', 'measurements', 'products.variant.product']);
        return view('print.invoice', compact('order'));
    }

    /**
     * حذف الطلب وإرجاع الكميات للمخزون.
     */
    public function destroy(Order $order)
    {
        try {
            DB::transaction(function () use ($order) {
                foreach ($order->products as $orderProduct) {
                    BranchStock::where('branch_id', $order->branch_id)
                               ->where('variant_id', $orderProduct->product_variant_id)
                               ->increment('quantity', $orderProduct->quantity);
                }
                $order->delete();
            });
            return redirect()->route('custom-orders.index')->with('success', 'تم حذف الطلب بنجاح.');
        } catch (Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }

    public function printReceipt(Order $order)
        {
            return view('custom-orders.print.receipt', compact('order'));
        }

}
