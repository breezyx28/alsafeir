<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            قائمة المنتجات
        </h2>
    </x-slot>

    <div class="container mt-4">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">
            إضافة منتج جديد
        </a>

        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3 mb-3">
    <div class="col-md-3">
        <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="بحث بالاسم">
    </div>

        <div class="col-md-2">
            <select name="category_id" class="form-control">
                <option value="">كل التصنيفات</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <select name="type" class="form-control">
                <option value="">كل الأنواع</option>
                <option value="ready" {{ request('type') == 'ready' ? 'selected' : '' }}>منتج جاهز</option>
                <option value="raw_material" {{ request('type') == 'raw_material' ? 'selected' : '' }}>مادة خام</option>
            </select>
        </div>

        <div class="col-md-2">
            <select name="status" class="form-control">
                <option value="">كل الحالات</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
            </select>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">بحث</button>
        </div>
    </form>


        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>النوع</th>
                    <th>SKU</th>
                    <th>التصنيف</th>
                    <th>السعر</th>
                    <th>الحالة</th>
                    <th>المتغيرات</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->type }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>{{ $product->base_price }}</td>
                    <td>{{ $product->status }}</td>
                    <td>
                        @if ($product->variants->count())
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="collapse" data-bs-target="#variants-{{ $product->id }}">
                                {{ $product->variants->count() }} متغير
                            </button>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">تعديل</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">حذف</button>
                        </form>
                    </td>
                </tr>

                @if ($product->variants->count())
                <tr class="collapse" id="variants-{{ $product->id }}">
                    <td colspan="8">
                        <table class="table table-sm table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>اللون</th>
                                    <th>المقاس</th>
                                    <th>الباركود</th>
                                    <th>الكمية</th>
                                    <th>سعر خاص</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->variants as $variant)
                                <tr>
                                    <td>{{ $variant->color ?? '-' }}</td>
                                    <td>{{ $variant->size ?? '-' }}</td>
                                    <td>{{ $variant->barcode ?? '-' }}</td>
                                    <td>{{ $variant->quantity ?? '-' }}</td>
                                    <td>{{ $variant->price_override ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="8" class="text-center">لا توجد منتجات</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>
