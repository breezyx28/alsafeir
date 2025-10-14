<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants()->get();
        return view('products.variants.index', compact('product', 'variants'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'color'    => 'required|string|max:50',
            'size'     => 'required|string|max:50',
            'sku'      => 'required|string|max:100|unique:product_variants,sku',
            'price'    => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        $validated['id'] = Str::uuid();
        $validated['product_id'] = $product->id;

        ProductVariant::create($validated);

        return redirect()->route('variants.index', $product)->with('success', 'تمت إضافة المتغير بنجاح');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        $variant->delete();
        return redirect()->route('variants.index', $product)->with('success', 'تم حذف المتغير');
    }

    
}
