<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">مخزون الفروع</h2>
    </x-slot>

    <div class="container mt-4">

        <form method="GET" action="{{ route('admin.branch_stocks.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="branch_id" class="form-label">اختر الفرع</label>
                <select name="branch_id" id="branch_id" class="form-control">
                    <option value="">كل الفروع</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="product_id" class="form-label">اختر المنتج</label>
                <select name="product_id" id="product_id" class="form-control">
                    <option value="">كل المنتجات</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">تصفية</button>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>الفرع</th>
                    <th>المنتج</th>
                    <th>المتغير</th>
                    <th>الكمية</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stocks as $stock)
                    <tr>
                        <td>{{ $stock->branch->name }}</td>
                        <td>{{ $stock->product->name ?? '-' }}</td>
                        <td>
                            @if ($stock->variant)
                                {{ $stock->variant->color ?? '' }} {{ $stock->variant->size ? '/ ' . $stock->variant->size : '' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $stock->quantity }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">لا توجد نتائج مطابقة.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
