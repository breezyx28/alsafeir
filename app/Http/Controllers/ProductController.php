<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
        {
            $query = Product::with('category');

            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $products = $query->latest()->paginate(20)->appends($request->query());

            $categories = ProductCategory::all();

            return view('admin.products.index', compact('products', 'categories'));
        }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'sku'          => 'required|string|max:100|unique:products',
            'type'         => 'required|in:ready,raw_material',
            'category_id'  => 'required|exists:product_categories,id',
            'base_price'   => 'required|numeric|min:0',
            'cost_price'   => 'nullable|numeric|min:0',
            'unit'         => 'required|in:piece,meter',
            'status'       => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();

        try {
            $validated['id'] = Str::uuid();
            $product = Product::create($validated);

            foreach ($request->variants ?? [] as $variant) {
                ProductVariant::create([
                    'id' => Str::uuid(),
                    'product_id' => $product->id,
                    'color' => $variant['color'] ?? null,
                    'size' => $variant['size'] ?? null,
                    'barcode' => $variant['barcode'] ?? null,
                    'price_override' => $variant['price_override'] ?? null,
                    'quantity' => $variant['quantity'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'تم إنشاء المنتج والمتغيرات بنجاح');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        $product->load('variants');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'sku'          => 'required|string|max:100|unique:products,sku,' . $product->id,
            'type'         => 'required|in:ready,raw_material',
            'category_id'  => 'required|exists:product_categories,id',
            'base_price'   => 'required|numeric|min:0',
            'cost_price'   => 'nullable|numeric|min:0',
            'unit'         => 'required|in:piece,meter',
            'status'       => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();

        try {
            $product->update($validated);

            // حذف المتغيرات القديمة
            $product->variants()->delete();

            // إنشاء المتغيرات الجديدة
            foreach ($request->variants ?? [] as $variant) {
                ProductVariant::create([
                    'id' => Str::uuid(),
                    'product_id' => $product->id,
                    'color' => $variant['color'] ?? null,
                    'size' => $variant['size'] ?? null,
                    'barcode' => $variant['barcode'] ?? null,
                    'price_override' => $variant['price_override'] ?? null,
                    'quantity' => $variant['quantity'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'تم تعديل المنتج والمتغيرات بنجاح');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'فشل التعديل: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج');
    }

    public function getVariants(Product $product)
        {
            $variants = $product->variants()->get(['id', 'color', 'size']);

            return response()->json($variants);
        }

}
