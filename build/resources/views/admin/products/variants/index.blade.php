<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            متغيرات المنتج: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="container mt-4">

        <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">رجوع للمنتجات</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- إضافة متغير جديد --}}
        <form method="POST" action="{{ route('variants.store', $product) }}" class="row g-3 mb-4">
            @csrf

            <div class="col-md-2">
                <input type="text" name="color" class="form-control" placeholder="اللون" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="size" class="form-control" placeholder="المقاس" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="sku" class="form-control" placeholder="SKU" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="price" class="form-control" placeholder="السعر" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="quantity" class="form-control" placeholder="الكمية" required>
            </div>
            <div class="col-md-2">
                <button class="btn btn-success w-100">إضافة</button>
            </div>
        </form>

        {{-- جدول المتغيرات --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>اللون</th>
                    <th>المقاس</th>
                    <th>SKU</th>
                    <th>السعر</th>
                    <th>الكمية</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($variants as $variant)
                    <tr>
                        <td>{{ $variant->color }}</td>
                        <td>{{ $variant->size }}</td>
                        <td>{{ $variant->sku }}</td>
                        <td>{{ $variant->price }}</td>
                        <td>{{ $variant->quantity }}</td>
                        <td>
                            <form action="{{ route('variants.destroy', [$product, $variant]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">لا توجد متغيرات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
