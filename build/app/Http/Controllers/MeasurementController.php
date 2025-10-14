<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Measurement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MeasurementController extends Controller
{
    /**
     * تخزين مقاس جديد لعميل معين.
     */
    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'detail_type' => [
                'required',
                'string',
                Rule::unique('measurements')->where('customer_id', $customer->id),
            ],
            // يمكنك إضافة باقي حقول التحقق هنا إذا أردت
        ]);

        $customer->measurements()->create($request->all());

        return back()->with('success', 'تم إضافة المقاس بنجاح.');
    }

    /**
     * تحديث مقاس موجود.
     */
    public function update(Request $request, Measurement $measurement)
    {
        $validated = $request->validate([
            'detail_type' => [
                'required',
                'string',
                Rule::unique('measurements')->where('customer_id', $measurement->customer_id)->ignore($measurement->id),
            ],
        ]);

        $measurement->update($request->all());

        return back()->with('success', 'تم تحديث المقاس بنجاح.');
    }

    /**
     * حذف مقاس.
     */
    public function destroy(Measurement $measurement)
    {
        $measurement->delete();
        return back()->with('success', 'تم حذف المقاس بنجاح.');
    }
}
