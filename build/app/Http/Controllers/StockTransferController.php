<?php

namespace App\Http\Controllers;

use App\Models\StockTransfer;
use App\Models\Branch;
use App\Models\BranchStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockTransferController extends Controller
{
   public function index(Request $request)
    {
        $query = StockTransfer::with(['fromBranch', 'toBranch']);

        if ($request->has('from_branch_id') && !empty($request->from_branch_id)) {
            $query->where('from_branch_id', $request->from_branch_id);
        }

        if ($request->has('to_branch_id') && !empty($request->to_branch_id)) {
            $query->where('to_branch_id', $request->to_branch_id);
        }

        if ($request->has('reference') && trim($request->reference) !== '') {
            $query->where('reference', 'like', '%' . $request->reference . '%');
        }

        if ($request->has('transfer_date') && !empty($request->transfer_date)) {
            $query->whereDate('transfer_date', $request->transfer_date);
        }

        $transfers = $query->paginate(15)->withQueryString();

        $branches = Branch::all();

        return view('admin.stock_transfers.index', compact('transfers', 'branches'));
    }
     public function create()
        {
            $branches = Branch::all();
            $products = \App\Models\Product::with('variants')->get(); // لو بتجيب المتغيرات مباشرة، غير كده شيل with('variants')

            return view('admin.stock_transfers.create', compact('branches', 'products'));
        }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_branch_id' => 'required|exists:branches,id|different:to_branch_id',
            'to_branch_id' => 'required|exists:branches,id',
            'reference' => 'nullable|string|max:255',
            'transfer_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // التفاف العملية في معاملة قاعدة بيانات atomic transaction
        DB::transaction(function () use ($validated) {
            $transfer = StockTransfer::create([
                'from_branch_id' => $validated['from_branch_id'],
                'to_branch_id' => $validated['to_branch_id'],
                'reference' => $validated['reference'] ?? null,
                'transfer_date' => $validated['transfer_date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $productId = $item['product_id'];
                $variantId = $item['variant_id'] ?? null;
                $quantity = $item['quantity'];

                // تحقق من كمية المخزون في فرع المصدر
                $branchStockFrom = BranchStock::where('branch_id', $validated['from_branch_id'])
                    ->where('product_id', $productId)
                    ->when($variantId, fn($q) => $q->where('variant_id', $variantId))
                    ->first();

                if (!$branchStockFrom || $branchStockFrom->quantity < $quantity) {
                    abort(400, "الكمية المطلوبة ($quantity) غير متوفرة في فرع المصدر.");
                }

                // خصم الكمية من فرع المصدر
                $branchStockFrom->decrement('quantity', $quantity);

                // إضافة أو تحديث الكمية في فرع الوجهة
                $branchStockTo = BranchStock::firstOrNew([
                    'branch_id' => $validated['to_branch_id'],
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                ]);
                $branchStockTo->quantity = ($branchStockTo->quantity ?? 0) + $quantity;
                $branchStockTo->save();

                // تسجيل الأصناف المحولة
                $transfer->items()->create([
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'quantity' => $quantity,
                ]);
            }
        });

        return redirect()->route('admin.stock_transfers.index')->with('success', 'تم تنفيذ التحويل بنجاح.');
    }

    // شاشة عرض تفاصيل التحويل
    public function show(StockTransfer $stockTransfer)
    {
        $stockTransfer->load(['fromBranch', 'toBranch', 'items.product', 'items.variant']);
        return view('admin.stock_transfers.show', compact('stockTransfer'));
    }

    // حذف تحويل (اختياري)
   public function destroy(StockTransfer $stockTransfer)
    {
        DB::transaction(function () use ($stockTransfer) {
            foreach ($stockTransfer->items as $item) {
                $productId = $item->product_id;
                $variantId = $item->variant_id;
                $quantity = $item->quantity;

                // خصم من فرع الوجهة
                $toStock = BranchStock::firstOrCreate([
                    'branch_id' => $stockTransfer->to_branch_id,
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                ]);

                $toStock->quantity = max(0, $toStock->quantity - $quantity);
                if ($toStock->quantity == 0) {
                    $toStock->delete();
                } else {
                    $toStock->save();
                }

                // إضافة إلى فرع المصدر
                $fromStock = BranchStock::firstOrCreate([
                    'branch_id' => $stockTransfer->from_branch_id,
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                ]);

                $fromStock->quantity += $quantity;
                $fromStock->save();
            }

            // حذف الأصناف ثم عملية التحويل
            $stockTransfer->items()->delete();
            $stockTransfer->delete();
        });

        return redirect()->route('admin.stock_transfers.index')->with('success', 'تم حذف التحويل واسترجاع الكميات بنجاح');
    }


}
