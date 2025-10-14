<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">تفاصيل أمر التصنيع</h2>
    </x-slot>

    <div class="container mt-4">

        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">معلومات أساسية</div>
            <div class="card-body">
                <p><strong>المرجع:</strong> {{ $productionOrder->reference }}</p>
                <p><strong>التاريخ:</strong> {{ $productionOrder->production_date }}</p>
                <p><strong>الفرع الوجهة:</strong> {{ $productionOrder->branch->name }}</p>
                <p><strong>المنتج النهائي:</strong> {{ $productionOrder->product->name }} 
                    @if($productionOrder->variant) - {{ $productionOrder->variant->name }} @endif
                </p>
                <p><strong>الكمية المنتجة:</strong> {{ $productionOrder->quantity }}</p>
                <p><strong>ملاحظات:</strong> {{ $productionOrder->notes ?: '—' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-light fw-bold">المواد الخام المستخدمة</div>
            <div class="card-body">
                @if($productionOrder->materials->isEmpty())
                    <p class="text-muted">لا توجد مواد مسجلة.</p>
                @else
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>المادة</th>
                                <th>الكمية</th>
                                <th>الوحدة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productionOrder->materials as $material)
                                <tr>
                                    <td>{{ $material->product->name }} @if($material->variant) - {{ $material->variant->name }} @endif</td>
                                    <td>{{ $material->quantity }}</td>
                                    <td>{{ $material->unit }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.production_orders.index') }}" class="btn btn-light">رجوع</a>
        </div>

    </div>
</x-app-layout>
