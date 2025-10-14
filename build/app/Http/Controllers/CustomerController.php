<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
     public function index(Request $request)
    {
        $query = Customer::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('phone_primary', 'like', "%{$search}%");
        }

        $customers = $query->paginate(10);

        return view('customers.index', compact('customers'));
    }

    /**
     * عرض فورم إنشاء عميل جديد.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * تخزين العميل الجديد.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_primary' => 'required|string|max:20|unique:customers,phone_primary',
            'phone_secondary' => 'nullable|string|max:20',
            'customer_level' => 'required|string',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'تم إضافة العميل بنجاح.');
    }

    /**
     * عرض تفاصيل عميل معين (سنستخدمها لاحقاً للمقاسات).
     */
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * عرض فورم تعديل بيانات العميل.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * تحديث بيانات العميل.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_primary' => 'required|string|max:20|unique:customers,phone_primary,' . $customer->id,
            'phone_secondary' => 'nullable|string|max:20',
            'customer_level' => 'required|string',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'تم تعديل بيانات العميل بنجاح.');
    }

    /**
     * حذف العميل.
     */
    public function destroy(Customer $customer)
    {
        // الحذف سيقوم بحذف الطلبات والمقاسات المرتبطة بسبب cascadeOnDelete
        $customer->delete();
        
        return redirect()->route('customers.index')->with('success', 'تم حذف العميل وكل بياناته المرتبطة بنجاح.');
    }

    
    public function search(Request $request)
    {
        $term = $request->input('term');
        if (empty($term)) {
            return response()->json([]);
        }

        $customers = Customer::where('name', 'LIKE', "%{$term}%")
                             ->orWhere('phone_primary', 'LIKE', "%{$term}%")
                             ->select('id', 'name', 'phone_primary')
                             ->take(10)
                             ->get();
                             
        return response()->json($customers);
    }

    /**
     * جلب مقاسات عميل معين لنوع تفصيل محدد.
     */
        public function getMeasurementsForType(Customer $customer, $type)
        {
            $measurement = $customer->measurements()->where('detail_type', $type)->latest()->first();
            if ($measurement) {
                return response()->json($measurement);
            }
            return response()->json(null, 204);
        }


    
}
