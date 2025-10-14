<?php

namespace App\Http\Controllers;

use App\Models\SaleReturn; 
use App\Models\ReturnItem;
use App\Models\ReadySale;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Account;
use App\Models\BranchStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = SaleReturn::with('sale', 'user');

        if ($request->filled('invoice_number')) {
            $query->whereHas('sale', function($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
            });
        }

        if ($request->filled('return_date')) {
            $query->whereDate('return_date', $request->return_date);
        }

        $returns = $query->latest()->paginate(20);
        return view('returns.index', compact('returns'));
    }

    public function show($id)
    {
        $return = SaleReturn::with(
            'sale.items.product',
            'sale.items.variant',
            'items.product',
            'items.variant',
            'user'
        )->findOrFail($id);

        return view('returns.show', compact('return'));
    }

    public function create($saleId)
    {
        $sale = ReadySale::with('items.product', 'items.variant', 'customer')->findOrFail($saleId);
        return view('returns.create', compact('sale'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_id'            => 'required|exists:ready_sales,id',
            'items'              => 'required|array|min:1',
            'items.*.variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'reason'             => 'nullable|string|max:500',
            'notes'              => 'nullable|string|max:500',
        ]);

        $sale   = ReadySale::with('items', 'customer')->findOrFail($request->sale_id);
        $user   = Auth::user();

        if (!$user || !$user->employee || !$user->employee->branch_id) {
            return back()->withErrors(['branch' => 'الفرع الخاص بالمستخدم غير محدد.']);
        }
        $branchId = $user->employee->branch_id;

        try {
            DB::transaction(function () use ($request, $sale, $branchId, $user) {
                // إنشاء سجل الإرجاع الأساسي
                $return = SaleReturn::create([
                    'ready_sale_id'       => $sale->id,
                    'return_date'         => Carbon::now(),
                    'total_refund_amount' => 0,
                    'reason'              => $request->reason,
                    'status'              => 'completed',
                    'user_id'             => $user->id,
                    'notes'               => $request->notes,
                ]);

                $totalRefund = 0;

                foreach ($request->items as $item) {
                    $originalItem = $sale->items->where('variant_id', $item['variant_id'])->first();

                    if (!$originalItem || $originalItem->quantity < $item['quantity']) {
                        throw new \Exception("الكمية المرجعة غير صحيحة للمنتج (Variant ID: {$item['variant_id']})");
                    }

                    // تحديث المخزون
                    $stock = BranchStock::where('branch_id', $branchId)
                        ->where('variant_id', $item['variant_id'])
                        ->lockForUpdate()
                        ->first();

                    if ($stock) {
                        $stock->increment('quantity', $item['quantity']);
                    }

                    // حساب سعر الوحدة الصافي
                    $totalSaleQuantity = $sale->items->sum('quantity');
                    $unitNetPrice = $totalSaleQuantity > 0 ? ($sale->net_amount / $totalSaleQuantity) : 0;

                    $refundAmount = $item['quantity'] * $unitNetPrice;
                    $totalRefund += $refundAmount;

                    // حفظ تفاصيل الإرجاع
                    ReturnItem::create([
                        'return_id'    => $return->id,
                        'product_id'   => $originalItem->product_id,
                        'variant_id'   => $item['variant_id'],
                        'quantity'     => $item['quantity'],
                        'refund_amount'=> $refundAmount,
                        'condition'    => $item['condition'] ?? 'new',
                    ]);
                }

                $return->update(['total_refund_amount' => $totalRefund]);

                // ===== إنشاء القيد المحاسبي =====
                $customerName = $sale->customer ? $sale->customer->name : 'عميل نقدي';

                // جلب الـ id الصحيح من جدول الحسابات
                $returnsAccountId = Account::where('code', 5700)->value('id'); // مردودات المبيعات
                $cashAccountId    = Account::where('code', 1000)->value('id'); // كاش
                $receivableId     = Account::where('code', 1100)->value('id'); // ذمم مدينة

                // إنشاء القيد الرئيسي
                $journalEntry = JournalEntry::create([
                    'entry_date'  => Carbon::now(),
                    'description' => "إرجاع مبيعات - فاتورة رقم {$sale->invoice_number}",
                    'created_by'  => $user->id,
                ]);

                // طرف مدين: مردودات المبيعات
                JournalEntryItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id'       => $returnsAccountId,
                    'debit'            => $totalRefund,
                    'credit'           => 0,
                    'notes'            => "إرجاع من العميل {$customerName}",
                ]);

                // طرف دائن: العميل أو الصندوق
                JournalEntryItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id'       => $sale->payment_method === 'cash' ? $cashAccountId : $receivableId,
                    'debit'            => 0,
                    'credit'           => $totalRefund,
                    'notes'            => "تخفيض رصيد بسبب الإرجاع",
                ]);
            });

            return redirect()->route('returns.index')->with('success', 'تم تسجيل عملية الإرجاع بنجاح.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
