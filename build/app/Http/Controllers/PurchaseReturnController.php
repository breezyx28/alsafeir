<?php

namespace App\Http\Controllers;

use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\BranchStock;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Supplier;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseReturn::with('supplier', 'purchaseOrder');

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('return_date')) {
            $query->whereDate('return_date', $request->return_date);
        }

        if ($request->filled('purchase_order_id')) {
            $query->where('purchase_order_id', $request->purchase_order_id);
        }

        $returns = $query->latest()->paginate(15)->withQueryString();
        $suppliers = Supplier::all();
        $purchaseOrders = PurchaseOrder::all();

        return view('admin.purchase_returns.index', compact('returns', 'suppliers', 'purchaseOrders'));
    }

    public function create()
    {
        $products = Product::all();
        $variants = ProductVariant::all();
        $suppliers = Supplier::all();
        $purchaseOrders = PurchaseOrder::all();

        return view('admin.purchase_returns.create', compact('products', 'variants', 'suppliers', 'purchaseOrders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'return_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            $purchaseOrder = PurchaseOrder::with('items')->findOrFail($validated['purchase_order_id']);
            $branchId = $purchaseOrder->branch_id;

            $return = PurchaseReturn::create([
                'purchase_order_id' => $purchaseOrder->id,
                'supplier_id' => $purchaseOrder->supplier_id,
                'return_date' => $validated['return_date'],
                'notes' => $validated['notes'] ?? null,
                'total_items' => count($validated['items']),
            ]);

            $totalReturnValue = 0;

            foreach ($validated['items'] as $item) {
                $productId = $item['product_id'];
                $variantId = $item['variant_id'] ?? null;
                $quantity = $item['quantity'];

                $purchaseItem = $purchaseOrder->items()
                    ->where('product_id', $productId)
                    ->when($variantId, fn($q) => $q->where('variant_id', $variantId))
                    ->first();

                if (!$purchaseItem || $quantity > $purchaseItem->quantity) {
                    throw new \Exception("الكمية المرتجعة أكبر من الكمية الأصلية أو الصنف غير موجود.");
                }

                $purchaseItem->decrement('quantity', $quantity);

                $return->items()->create([
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'quantity' => $quantity,
                ]);

                $stock = BranchStock::where([
                    'branch_id' => $branchId,
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                ])->first();

                if ($stock && $stock->quantity >= $quantity) {
                    $stock->decrement('quantity', $quantity);
                }

                $unitPrice = $this->getProductCost($productId, $variantId);
                $purchaseOrder->supplier->increment('credit_balance', $quantity * $unitPrice);

                $totalReturnValue += $quantity * $unitPrice;
            }

            // تسجيل القيد المحاسبي
            $journalEntry = JournalEntry::create([
                'entry_date' => $validated['return_date'],
                'description' => 'إرجاع مشتريات فاتورة ' . $purchaseOrder->id,
                'created_by' => Auth::id(),
            ]);

            $supplierAccount = Account::where('code', '2100')->first(); // الموردين
            $inventoryAccount = Account::where('code', '1420')->first(); // مخزون خام

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $supplierAccount->id,
                'debit' => $totalReturnValue,
                'credit' => 0,
                'notes' => 'إرجاع مشتريات',
            ]);

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $inventoryAccount->id,
                'debit' => 0,
                'credit' => $totalReturnValue,
                'notes' => 'إرجاع مشتريات',
            ]);
        });

        return redirect()->route('admin.purchase_returns.index')->with('success', 'تم تسجيل الإرجاع بنجاح');
    }

    public function show(PurchaseReturn $purchaseReturn)
    {
        $purchaseReturn->load('supplier', 'purchaseOrder', 'items.product', 'items.variant');
        return view('admin.purchase_returns.show', compact('purchaseReturn'));
    }

    public function edit(PurchaseReturn $purchaseReturn)
    {
        $suppliers = Supplier::all();
        $purchaseOrders = PurchaseOrder::all();
        $purchaseReturn->load('items');

        return view('admin.purchase_returns.edit', compact('purchaseReturn', 'suppliers', 'purchaseOrders'));
    }

    public function update(Request $request, PurchaseReturn $purchaseReturn)
    {
        $validated = $request->validate([
            'return_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);

        $purchaseReturn->update($validated);

        return redirect()->route('admin.purchase_returns.index')->with('success', 'تم تحديث بيانات الإرجاع');
    }

    public function destroy(PurchaseReturn $purchaseReturn)
    {
        DB::transaction(function () use ($purchaseReturn) {
            $supplier = $purchaseReturn->supplier;
            $purchaseOrder = $purchaseReturn->purchaseOrder;

            $totalReturnValue = 0;

            foreach ($purchaseReturn->items as $item) {
                $productId = $item->product_id;
                $variantId = $item->variant_id;
                $quantity = $item->quantity;

                $purchaseItem = $purchaseOrder->items()
                    ->where('product_id', $productId)
                    ->when($variantId, fn($q) => $q->where('variant_id', $variantId))
                    ->first();

                if ($purchaseItem) {
                    $purchaseItem->increment('quantity', $quantity);
                }

                $unitPrice = $this->getProductCost($productId, $variantId);
                $totalReturnValue += $quantity * $unitPrice;

                $supplier->decrement('credit_balance', $quantity * $unitPrice);
            }

            // حذف العناصر المرتجعة
            $purchaseReturn->items()->delete();

            // حذف الإرجاع نفسه
            $purchaseReturn->delete();

            // حذف القيد المحاسبي المتعلق بالإرجاع
            $journalEntry = JournalEntry::where('description', 'إرجاع مشتريات فاتورة ' . $purchaseOrder->id)->latest()->first();
            if ($journalEntry) {
                $journalEntry->items()->delete();
                $journalEntry->delete();
            }
        });

        return redirect()->route('admin.purchase_returns.index')->with('success', 'تم حذف عملية الإرجاع واسترجاع الكميات إلى المخزون المركزي');
    }

    private function getProductCost($productId, $variantId = null)
    {
        return DB::table('purchase_order_items')
            ->where('product_id', $productId)
            ->when($variantId, fn($q) => $q->where('variant_id', $variantId))
            ->orderByDesc('created_at')
            ->value('cost_price') ?? 0;
    }
}
