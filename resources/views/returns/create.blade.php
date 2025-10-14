<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            إنشاء عملية إرجاع - فاتورة رقم {{ $sale->invoice_number }}
        </h2>
    </x-slot>

    <div class="container py-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('returns.store', $sale->id) }}">
            @csrf

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <strong>تفاصيل المنتجات المباعة</strong>
                </div>
                <div class="card-body p-0">
                    <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%"></th>
                                <th>المنتج</th>
                                <th>المقاس</th>
                                <th>اللون</th>
                                <th>الكمية المباعة</th>
                                <th>سعر الوحدة</th>
                                <th>كمية الإرجاع</th>
                                <th>حالة المنتج</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sale->items as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="items[{{ $loop->index }}][variant_id]"
                                               value="{{ $item->variant_id }}">
                                    </td>
                                    <td>{{ $item->product->name ?? '-' }}</td>
                                    <td>{{ $item->variant->size ?? '-' }}</td>
                                    <td>{{ $item->variant->color ?? '-' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->unit_price, 2) }}</td>
                                    <td>
                                        <input type="number" name="items[{{ $loop->index }}][quantity]"
                                               class="form-control form-control-sm" min="1" max="{{ $item->quantity }}">
                                    </td>
                                    <td>
                                        <select name="items[{{ $loop->index }}][condition]" class="form-select form-select-sm">
                                            <option value="new">جديد</option>
                                            <option value="used">مستعمل</option>
                                            <option value="damaged">تالف</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                            @if($sale->items->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center text-muted">لا توجد منتجات في هذه الفاتورة.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <strong>معلومات إضافية</strong>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">سبب الإرجاع</label>
                        <textarea name="reason" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('ready_sales.show', $sale->id) }}" class="btn btn-secondary me-2">
                    إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    حفظ الإرجاع
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
