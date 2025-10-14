<?php

namespace App\Http\Controllers;

use App\Models\SaleReturn;
use App\Models\ReturnItem;
use App\Models\BranchStock;
use App\Models\Exchange;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Account;
use App\Models\ExchangeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExchangeController extends Controller
{
    // عرض قائمة الاستبدالات
    public function index(Request $request)
    {
        $query = Exchange::with('return.sale', 'user');

        if ($request->filled('invoice_number')) {
            $query->whereHas('return.sale', function($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
            });
        }

        if ($request->filled('exchange_date')) {
            $query->whereDate('exchange_date', $request->exchange_date);
        }

        $exchanges = $query->latest()->paginate(20);

        return view('exchanges.index', compact('exchanges'));
    }

    // عرض تفاصيل استبدال واحد
    public function show($id)
    {
        $exchange = Exchange::with(
            'return.items.variant',
            'items.variant',
            'user'
        )->findOrFail($id);

        return view('exchanges.show', compact('exchange'));
    }

    // عرض فورم الاستبدال بناءً على إرجاع موجود
    public function create($returnId)
    {
        $return = SaleReturn::with('items.variant', 'sale')->findOrFail($returnId);

        $user = Auth::user();
        $branchId = $user->employee->branch_id ?? null;

        // المتغيرات المتاحة في الفرع الحالي
        $availableVariants = BranchStock::with('variant.product')
            ->where('branch_id', $branchId)
            ->where('quantity', '>', 0)
            ->get()
            ->map(function($stock) {
                return $stock->variant; // الآن نحتفظ بالـ Variant model الكامل
            })
            ->filter();


        return view('exchanges.create', compact('return', 'availableVariants'));
    }

    // حفظ الاستبدال
    public function store(Request $request)
{
    $request->validate([
        'return_id' => 'required|exists:sale_returns,id',
        'items' => 'required|array|min:1',
        'items.*.new_variant_id' => 'required|exists:product_variants,id',
        'items.*.quantity' => 'required|integer|min:1',
        'amount_paid_by_customer' => 'nullable|numeric|min:0',
        'amount_refunded_to_customer' => 'nullable|numeric|min:0',
        'notes' => 'nullable|string|max:500',
    ]);

    $return = SaleReturn::with('items.variant', 'sale.customer')->findOrFail($request->return_id);
    $user = Auth::user();
    $branchId = $user->employee->branch_id ?? null;

    if (!$branchId) {
        return back()->withErrors(['branch' => 'الفرع الخاص بالمستخدم غير محدد.']);
    }

    try {
        DB::transaction(function () use ($request, $return, $branchId, $user) {

            $exchange = Exchange::create([
                'return_id' => $return->id,
                'exchange_date' => Carbon::now(),
                'amount_paid_by_customer' => $request->amount_paid_by_customer ?? 0,
                'amount_refunded_to_customer' => $request->amount_refunded_to_customer ?? 0,
                'user_id' => $user->id,
                'notes' => $request->notes,
            ]);

            $totalOriginalAmount = 0;

            foreach ($request->items as $index => $item) {

                $originalVariantId = $request->items[$index]['original_variant_id'];
                $quantity = $item['quantity'];
                $amount = $request->items[$index]['original_price'];
                $newVariantId = $item['new_variant_id'];

                // خصم الكمية من المخزون الفرعي (الفرع)
                $stock = BranchStock::where('branch_id', $branchId)
                    ->where('variant_id', $newVariantId)
                    ->lockForUpdate()
                    ->first();

                if (!$stock || $stock->quantity < $quantity) {
                    throw new \Exception("لا يوجد مخزون كافٍ للمتغير المختار (ID: {$newVariantId})");
                }

                $stock->decrement('quantity', $quantity);

                // حفظ تفاصيل الاستبدال
                ExchangeItem::create([
                    'exchange_id' => $exchange->id,
                    'product_id' => $return->items->where('variant_id', $originalVariantId)->first()->product_id,
                    'variant_id' => $newVariantId,
                    'quantity' => $quantity,
                    'amount' => $amount,
                ]);

                $totalOriginalAmount += $amount;
            }

            // ===== تسجيل القيد المحاسبي =====
            $customerName = $return->sale->customer ? $return->sale->customer->name : 'عميل نقدي';

            // جلب الحسابات حسب الكود
            $returnsAccountId = Account::where('code', 5700)->value('id'); // مردودات المبيعات
            $cashAccountId    = Account::where('code', 1000)->value('id'); // كاش
            $receivableId     = Account::where('code', 1100)->value('id'); // ذمم مدينة

            $journalEntry = JournalEntry::create([
                'entry_date' => Carbon::now(),
                'description' => "استبدال مبيعات - فاتورة رقم {$return->sale->invoice_number}",
                'created_by' => $user->id,
            ]);

            // طرف مدين: مردودات المبيعات (قيمته = القيمة الأصلية للمنتجات المستبدلة)
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $returnsAccountId,
                'debit' => $totalOriginalAmount,
                'credit' => 0,
                'notes' => "استبدال من العميل {$customerName}",
            ]);

            // طرف دائن: العميل أو الصندوق (حسب حالة الدفع أو أي مبالغ إضافية)
            $creditAmount = $totalOriginalAmount - ($request->amount_paid_by_customer ?? 0) + ($request->amount_refunded_to_customer ?? 0);

            if ($creditAmount > 0) {
                JournalEntryItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $return->sale->payment_method === 'cash' ? $cashAccountId : $receivableId,
                    'debit' => 0,
                    'credit' => $creditAmount,
                    'notes' => "تخفيض رصيد بسبب الاستبدال",
                ]);
            }

        });

        return redirect()->route('exchanges.index')->with('success', 'تم تسجيل عملية الاستبدال بنجاح.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}

}
