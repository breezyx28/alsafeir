<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Branch;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\BranchStock;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    public function index()
    {
        $distributions = Distribution::with(['branch', 'purchaseOrder', 'product', 'variant'])->paginate(15);
        return view('admin.distributions.index', compact('distributions'));
    }

    public function create()
    {
        $branches = Branch::all();
        $purchaseOrders = PurchaseOrder::with('items')->get();
        $products = Product::all();

        return view('admin.distributions.create', compact('branches', 'purchaseOrders', 'products'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'branch_id' => 'required|exists:branches,id',
        'purchase_order_id' => 'required|exists:purchase_orders,id',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.variant_id' => 'nullable|exists:product_variants,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    foreach ($validated['items'] as $index => $item) {
        $productId = $item['product_id'];
        $variantId = $item['variant_id'] ?? null;
        $quantity = $item['quantity'];

        // الكمية المشتراة من نفس الفاتورة
        $purchasedQty = PurchaseOrderItem::where('product_id', $productId)
            ->where('purchase_order_id', $validated['purchase_order_id'])
            ->when($variantId, fn($q) => $q->where('variant_id', $variantId))
            ->sum('quantity');

        // الكمية التي تم توزيعها
        $distributedQty = Distribution::where('product_id', $productId)
            ->where('purchase_order_id', $validated['purchase_order_id'])
            ->when($variantId, fn($q) => $q->where('variant_id', $variantId))
            ->sum('quantity');

        $availableQty = $purchasedQty - $distributedQty;

        if ($quantity > $availableQty) {
            return back()->withErrors([
                "items.$index.quantity" => "الكمية المطلوبة أكبر من المتاحة ($availableQty)"
            ])->withInput();
        }

        // أنشئ سجل التوزيع
        Distribution::create([
            'branch_id' => $validated['branch_id'],
            'purchase_order_id' => $validated['purchase_order_id'],
            'product_id' => $productId,
            'variant_id' => $variantId,
            'quantity' => $quantity,
        ]);

        $stock = BranchStock::firstOrNew([
            'branch_id' => $validated['branch_id'],
            'product_id' => $productId,
            'variant_id' => $variantId,
        ]);

        $stock->quantity += $quantity;
        $stock->save();
    }

    return redirect()->route('admin.distributions.index')->with('success', 'تم توزيع المنتجات بنجاح');
}


    public function edit(Distribution $distribution)
    {
        $branches = Branch::all();
        $purchaseOrders = PurchaseOrder::all();
        $products = Product::all();
        return view('admin.distributions.edit', compact('distribution', 'branches', 'purchaseOrders', 'products'));
    }

    public function update(Request $request, Distribution $distribution)
{
    $validated = $request->validate([
        'branch_id' => 'required|exists:branches,id',
        'purchase_order_id' => 'required|exists:purchase_orders,id',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.variant_id' => 'nullable|exists:product_variants,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    // احذف التوزيع الحالي (نفترض إنه يمثل عنصر واحد)
    $distribution->delete();

    foreach ($validated['items'] as $index => $item) {
        $productId = $item['product_id'];
        $variantId = $item['variant_id'] ?? null;
        $quantity = $item['quantity'];

        $purchasedQty = PurchaseOrderItem::where('product_id', $productId)
            ->where('purchase_order_id', $validated['purchase_order_id'])
            ->when($variantId, fn($q) => $q->where('variant_id', $variantId))
            ->sum('quantity');

        $distributedQty = Distribution::where('product_id', $productId)
            ->where('purchase_order_id', $validated['purchase_order_id'])
            ->when($variantId, fn($q) => $q->where('variant_id', $variantId))
            ->sum('quantity');

        $availableQty = $purchasedQty - $distributedQty;

        if ($quantity > $availableQty) {
            return back()->withErrors([
                "items.$index.quantity" => "الكمية المطلوبة أكبر من المتاحة ($availableQty)"
            ])->withInput();
        }

        // أنشئ توزيع جديد
        Distribution::create([
            'branch_id' => $validated['branch_id'],
            'purchase_order_id' => $validated['purchase_order_id'],
            'product_id' => $productId,
            'variant_id' => $variantId,
            'quantity' => $quantity,
        ]);
    }

    return redirect()->route('admin.distributions.index')->with('success', 'تم تعديل التوزيع بنجاح');
}


    public function destroy(Distribution $distribution)
    {
        $distribution->delete();

        return redirect()->route('admin.distributions.index')->with('success', 'تم حذف التوزيع بنجاح');
    }
}
