<?php


namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::with('parent')->latest()->get();
        return view('admin.product_categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = ProductCategory::all();
        return view('admin.product_categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:product_categories,id',
        ]);

        ProductCategory::create([
            'id' => Str::uuid(),
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('admin.product-categories.index')->with('success', 'تمت إضافة التصنيف');
    }

    public function edit(ProductCategory $productCategory)
    {
        $parents = ProductCategory::where('id', '!=', $productCategory->id)->get();
        return view('admin.product_categories.edit', compact('productCategory', 'parents'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:product_categories,id|not_in:' . $productCategory->id,
        ]);

        $productCategory->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('admin.product-categories.index')->with('success', 'تم تعديل التصنيف');
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return redirect()->route('admin.product-categories.index')->with('success', 'تم حذف التصنيف');
    }
}
