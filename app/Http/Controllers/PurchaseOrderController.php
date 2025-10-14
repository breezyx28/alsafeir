<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('supplier');

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(15);
        $suppliers = Supplier::select('id', 'name')->get();

        return view('admin.purchases.index', compact('orders', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();

        return view('admin.purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,received,canceled',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|uuid',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $order = PurchaseOrder::create([
                'id' => Str::uuid(),
                'supplier_id' => $validated['supplier_id'],
                'order_date' => $validated['order_date'],
                'reference' => $validated['reference'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => $validated['status'],
                'total' => collect($validated['items'])->sum(fn($item) => $item['cost_price'] * $item['quantity']),
            ]);

            foreach ($validated['items'] as $item) {
                $order->items()->create([
                    'id' => Str::uuid(),
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'cost_price' => $item['cost_price'],
                    'total_cost' => $item['quantity'] * $item['cost_price'],
                ]);
            }

            // --- تسجيل القيد المحاسبي ---
            $journalEntry = JournalEntry::create([
                'entry_date' => $validated['order_date'],
                'description' => 'فاتورة شراء رقم ' . $order->id . ' من المورد ' . $order->supplier->name,
                'created_by' => Auth::id(),
            ]);

            $inventoryAccount = Account::where('code', '1420')->first(); // مخزون خام
            $supplierAccount = Account::where('code', '2100')->first();  // الموردين

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $inventoryAccount->id,
                'debit' => $order->total,
                'credit' => 0,
                'notes' => 'فاتورة شراء',
            ]);

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $supplierAccount->id,
                'debit' => 0,
                'credit' => $order->total,
                'notes' => 'فاتورة شراء',
            ]);

            DB::commit();
            return redirect()->route('admin.purchases.index')->with('success', 'تم تسجيل المشتريات والقيد المحاسبي بنجاح');
        } catch (\Throwable $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'حدث خطأ: ' . $e->getMessage()]);
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('items.product', 'items.variant', 'supplier');
        return view('admin.purchases.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('items');
        $suppliers = Supplier::all();
        $products = Product::all();

        return view('admin.purchases.edit', compact('purchaseOrder', 'suppliers', 'products'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,received,canceled',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|uuid',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $purchaseOrder->update([
                'supplier_id' => $validated['supplier_id'],
                'order_date' => $validated['order_date'],
                'reference' => $validated['reference'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => $validated['status'],
                'total' => collect($validated['items'])->sum(fn($item) => $item['cost_price'] * $item['quantity']),
            ]);

            $purchaseOrder->items()->delete();

            foreach ($validated['items'] as $item) {
                $purchaseOrder->items()->create([
                    'id' => Str::uuid(),
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'cost_price' => $item['cost_price'],
                    'total_cost' => $item['quantity'] * $item['cost_price'],
                ]);
            }

            // --- تسجيل القيد المحاسبي للتحديث ---
            $journalEntry = JournalEntry::create([
                'entry_date' => $validated['order_date'],
                'description' => 'تحديث فاتورة شراء رقم ' . $purchaseOrder->id . ' من المورد ' . $purchaseOrder->supplier->name,
                'created_by' => Auth::id(),
            ]);

            $inventoryAccount = Account::where('code', '1420')->first();
            $supplierAccount = Account::where('code', '2100')->first();

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $inventoryAccount->id,
                'debit' => $purchaseOrder->total,
                'credit' => 0,
                'notes' => 'فاتورة شراء تعديل',
            ]);

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $supplierAccount->id,
                'debit' => 0,
                'credit' => $purchaseOrder->total,
                'notes' => 'فاتورة شراء تعديل',
            ]);

            DB::commit();
            return redirect()->route('admin.purchases.index')->with('success', 'تم تحديث الفاتورة والقيد المحاسبي بنجاح');
        } catch (\Throwable $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'حدث خطأ: ' . $e->getMessage()]);
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        try {
            $purchaseOrder->items()->delete();
            $purchaseOrder->delete();

            return redirect()->route('admin.purchases.index')->with('success', 'تم حذف الفاتورة بنجاح');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء الحذف: ' . $e->getMessage()]);
        }
    }

    public function getVariants(Product $product)
    {
        $variants = $product->variants()->get(['id', 'color', 'size']);
        return response()->json($variants);
    }
}
