<?php

namespace App\Http\Controllers;

use App\Models\OrderProduct;
use Illuminate\Http\Request;

class OrderProductController extends Controller
{
    /**
     * تحديث حالة قطعة قماش معينة في الطلب.
     */
    public function updateStatus(Request $request, OrderProduct $orderProduct)
    {
        $request->validate(['status' => 'required|string|in:جاري التنفيذ,جاهز']);

        $orderProduct->status = $request->status;
        $orderProduct->save();

        return back()->with('success', 'تم تحديث حالة قطعة القماش بنجاح.');
    }
}
