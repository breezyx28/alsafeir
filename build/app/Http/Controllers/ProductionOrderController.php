<?php

namespace App\Http\Controllers;

use App\Models\ProductionOrder;
use App\Models\ProductionMaterial;
use App\Models\PurchaseOrderItem;
use App\Models\BranchStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionOrderController extends Controller
{


public function index(Request $request)
        {
            $query = ProductionOrder::with(['product', 'variant', 'branch'])->latest();

            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->filled('reference')) {
                $query->where('reference', 'like', '%' . $request->reference . '%');
            }

            $productionOrders = $query->paginate(15);
            $branches = \App\Models\Branch::all(); // ← هذا مهم
          


            return view('admin.production_orders.index', compact('productionOrders', 'branches'));
        }





    public function create()
        {
            $products = \App\Models\Product::where('type', 'ready')->get();
            $rawMaterials = \App\Models\Product::where('type', 'raw_material')->get();
            $branches = \App\Models\Branch::all();
            $variants = \App\Models\ProductVariant::all();

            return view('admin.production_orders.create', compact('products', 'rawMaterials', 'branches', 'variants'));
        }



    public function store(Request $request)
{
    $validated = $request->validate([
        'reference' => 'nullable|string|max:255',
        'production_date' => 'required|date',
        'notes' => 'nullable|string',
        'branch_id' => 'required|exists:branches,id',

        'final_products' => 'required|array|min:1',
        'final_products.*.product_id' => 'required|exists:products,id',
        'final_products.*.variant_id' => 'nullable|exists:product_variants,id',
        'final_products.*.quantity' => 'required|integer|min:1',

        'materials' => 'required|array|min:1',
        'materials.*.product_id' => 'required|exists:products,id',
        'materials.*.variant_id' => 'nullable|exists:product_variants,id',
        'materials.*.quantity' => 'required|numeric|min:0.01',
        'materials.*.unit' => 'required|string|max:50',
    ]);

    DB::transaction(function () use ($validated) {
        foreach ($validated['final_products'] as $finalProduct) {
            // 1. إنشاء أمر تصنيع لكل منتج نهائي
            $order = ProductionOrder::create([
                'reference' => $validated['reference'] ?? null,
                'production_date' => $validated['production_date'],
                'notes' => $validated['notes'] ?? null,
                'product_id' => $finalProduct['product_id'],
                'variant_id' => $finalProduct['variant_id'] ?? null,
                'quantity' => $finalProduct['quantity'],
                'branch_id' => $validated['branch_id'],
            ]);

            // 2. خصم المواد الخام من المخزون وتسجيلها
            foreach ($validated['materials'] as $material) {
                $productId = $material['product_id'];
                $variantId = $material['variant_id'] ?? null;
                $requiredQty = $material['quantity'];

                $available = PurchaseOrderItem::where('product_id', $productId)
                    ->when($variantId, fn($q) => $q->where('variant_id', $variantId))
                    ->sum(DB::raw('quantity - quantity_used'));

                if ($available < $requiredQty) {
                    abort(400, "المخزون غير كافي للمادة الخام (Product ID: $productId)");
                }

                // خصم الكمية تدريجياً من purchase_order_items
                $items = PurchaseOrderItem::where('product_id', $productId)
                    ->when($variantId, fn($q) => $q->where('variant_id', $variantId))
                    ->orderBy('created_at')
                    ->get();

                $remaining = $requiredQty;
                foreach ($items as $item) {
                    $availableQty = $item->quantity - $item->quantity_used;
                    if ($availableQty <= 0) continue;

                    $toDeduct = min($availableQty, $remaining);
                    $item->increment('quantity_used', $toDeduct);
                    $remaining -= $toDeduct;

                    if ($remaining <= 0) break;
                }

                // تسجيل المادة الخام في جدول ProductionMaterial
                ProductionMaterial::create([
                    'production_order_id' => $order->id,
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'quantity' => $requiredQty,
                    'unit' => $material['unit'],
                ]);
            }

            // 3. إضافة المنتج النهائي إلى مخزون الفرع
            $stock = BranchStock::firstOrNew([
                'branch_id' => $validated['branch_id'],
                'product_id' => $finalProduct['product_id'],
                'variant_id' => $finalProduct['variant_id'] ?? null,
            ]);
            $stock->quantity = ($stock->quantity ?? 0) + $finalProduct['quantity'];
            $stock->save();
        }
    });

    return redirect()->route('admin.production_orders.index')->with('success', 'تم إنشاء أوامر التصنيع بنجاح.');
}


    public function show(ProductionOrder $productionOrder)
    {
        $productionOrder->load(['product', 'variant', 'materials.product', 'materials.variant']);
        return view('admin.production_orders.show', compact('productionOrder'));
    }

    public function destroy(ProductionOrder $productionOrder)
    {
        // ملاحظة: لا يتم التراجع عن خصم المواد أو إزالة الكميات المُضافة إلا إذا كان دا منطقي في حالتك
        DB::transaction(function () use ($productionOrder) {
            $productionOrder->materials()->delete();
            $productionOrder->delete();
        });

        return redirect()->route('admin.production_orders.index')->with('success', 'تم حذف أمر التصنيع.');
    }
}
