<?php

namespace App\Http\Controllers;

use App\Models\ReadySale;
use App\Models\ReadySaleItem;
use App\Models\ProductVariant;
use App\Models\BranchStock;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReadySaleController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        if (!$user || !$user->employee || !$user->employee->branch_id) {
            abort(403, 'الفرع الخاص بالمستخدم غير محدد.');
        }

        $branchId = $user->employee->branch_id;
        $variants = ProductVariant::with('product')->get();

        return view('ready_sales.create', compact('variants', 'branchId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_date'         => 'required|date',
            'payment_method'    => 'required|string',
            'payment_status'    => 'required|string',
            'discount_percent'  => 'nullable|in:0,5,10,15',
            'items'             => 'required|array|min:1',
            'items.*.variant_id'=> 'required|exists:product_variants,id',
            'items.*.quantity'  => 'required|integer|min:1',
            'items.*.unit_price'=> 'required|numeric|min:0',
        ]);

        $user = Auth::user();

        if (!$user || !$user->employee || !$user->employee->branch_id) {
            return back()->withErrors(['branch' => 'الفرع الخاص بالمستخدم غير محدد.']);
        }

        $branchId = $user->employee->branch_id;

        $sale = DB::transaction(function () use ($request, $branchId, $user) {
            $lastId = ReadySale::max('id') + 1;
            $invoiceNumber = 'INV-' . str_pad($lastId, 5, '0', STR_PAD_LEFT);

            $totalAmount = collect($request->items)->sum(function ($item) {
                return $item['quantity'] * $item['unit_price'];
            });

            $discountPercent = $request->input('discount_percent', 0);
            $discountAmount  = ($totalAmount * $discountPercent) / 100;
            $netAmount       = $totalAmount - $discountAmount;

            $sale = ReadySale::create([
                'invoice_number'  => $invoiceNumber,
                'sale_date'       => $request->sale_date,
                'customer_id'     => $request->customer_id,
                'total_amount'    => $totalAmount,
                'discount_amount' => $discountAmount,
                'net_amount'      => $netAmount,
                'payment_method'  => $request->payment_method,
                'payment_status'  => $request->payment_status,
                'user_id'         => $user->id,
                'branch_id'       => $branchId,
                'notes'           => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $stock = BranchStock::where('branch_id', $branchId)
                    ->where('variant_id', $item['variant_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$stock || $stock->quantity < $item['quantity']) {
                    throw new \Exception("الكمية غير متوفرة للمخزون (Variant ID: {$item['variant_id']})");
                }

                $stock->decrement('quantity', $item['quantity']);

                ReadySaleItem::create([
                    'ready_sale_id' => $sale->id,
                    'product_id'    => $stock->product_id,
                    'variant_id'    => $item['variant_id'],
                    'quantity'      => $item['quantity'],
                    'unit_price'    => $item['unit_price'],
                    'sub_total'     => $item['quantity'] * $item['unit_price'],
                ]);
            }

            // تسجيل القيود المحاسبية
            $journalEntry = JournalEntry::create([
                'entry_date' => $request->sale_date,
                'description' => 'فاتورة مبيعات ' . $sale->invoice_number,
                'created_by' => $user->id,
            ]);

            // حسابات رئيسية
            $cashAccount     = Account::where('code', '1100')->first(); // الصندوق
            $receivableAccount = Account::where('code', '1300')->first(); // العملاء
            $salesAccount    = Account::where('code', '4100')->first(); // المبيعات

            if ($request->payment_status === 'مدفوع' && $request->payment_method === 'cash') {
                // نقدي
                JournalEntryItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $cashAccount->id,
                    'debit' => $netAmount,
                    'credit' => 0,
                    'notes' => 'تحصيل نقدي',
                ]);
            } else {
                // آجل
                JournalEntryItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $receivableAccount->id,
                    'debit' => $netAmount,
                    'credit' => 0,
                    'notes' => 'مبيعات آجل',
                ]);
            }

            // المبيعات دائن
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $salesAccount->id,
                'debit' => 0,
                'credit' => $netAmount,
                'notes' => 'إثبات المبيعات',
            ]);

            return $sale;
        });

        return redirect()->route('ready_sales.show', $sale->id)
            ->with('success', 'تم إنشاء الفاتورة بنجاح.');
    }

    public function index(Request $request)
    {
        $query = ReadySale::with('branch', 'customer', 'user');

        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }

        if ($request->filled('from_date')) {
            $query->whereDate('sale_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('sale_date', '<=', $request->to_date);
        }

        $sales = $query->latest()->paginate(20);

        return view('ready_sales.index', compact('sales'));
    }

    public function show($id)
    {
        $sale = ReadySale::with('items.product', 'items.variant', 'customer', 'branch', 'user')->findOrFail($id);
        return view('ready_sales.show', compact('sale'));
    }

    public function print($id)
    {
        $sale = ReadySale::with('items.product', 'items.variant', 'branch', 'customer')->findOrFail($id);
        return view('ready_sales.print', compact('sale'));
    }
}
