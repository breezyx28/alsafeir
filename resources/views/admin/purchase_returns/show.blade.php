<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">تفاصيل الإرجاع</h2>
    </x-slot>

    <div class="container mt-4">
        <div class="mb-3"><strong>المورد:</strong> {{ $purchaseReturn->supplier->name }}</div>
        <div class="mb-3"><strong>الفاتورة:</strong> #{{ $purchaseReturn->purchaseOrder->id }}</div>
        <div class="mb-3"><strong>تاريخ الإرجاع:</strong> {{ $purchaseReturn->return_date }}</div>
        <div class="mb-3"><strong>السبب:</strong> {{ $purchaseReturn->notes ?? '-' }}</div>

        <h5>المنتجات المرجعة:</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>اللون و المقاس</th>
                    <th>الكمية</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseReturn->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>
                            @if($item->variant)
                                {{ $item->variant->color ?? '' }} {{ $item->variant->size ? ' / ' . $item->variant->size : '' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('admin.purchase_returns.index') }}" class="btn btn-secondary">رجوع</a>
    </div>
</x-app-layout>
