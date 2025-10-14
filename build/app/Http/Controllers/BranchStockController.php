<?php

namespace App\Http\Controllers;

use App\Models\BranchStock;
use Illuminate\Http\Request;

class BranchStockController extends Controller
{
    public function index(Request $request)
{
    $query = \App\Models\BranchStock::with(['branch', 'product', 'variant']);

    if ($request->filled('branch_id')) {
        $query->where('branch_id', $request->branch_id);
    }

    if ($request->filled('product_id')) {
        $query->where('product_id', $request->product_id);
    }

    $stocks = $query->get();

    $branches = \App\Models\Branch::all();
    $products = \App\Models\Product::all();

    return view('admin.branch_stocks.index', compact('stocks', 'branches', 'products'));
}

}

