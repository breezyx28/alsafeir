<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">تفاصيل قيد اليومية</h2>
    </x-slot>

    <div class="container py-3">
        <div class="mb-3">
            <strong>التاريخ:</strong> {{ $entry->entry_date }}
        </div>
        <div class="mb-3">
            <strong>الوصف:</strong> {{ $entry->description }}
        </div>
        <div class="mb-3">
            <strong>أنشأ بواسطة:</strong> {{ $entry->creator->name ?? 'غير محدد' }}
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>الحساب</th>
                    <th>مدين</th>
                    <th>دائن</th>
                    <th>ملاحظات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entry->items as $item)
                    <tr>
                        <td>{{ $item->account->code }} - {{ $item->account->name }}</td>
                        <td>{{ number_format($item->debit, 2) }}</td>
                        <td>{{ number_format($item->credit, 2) }}</td>
                        <td>{{ $item->notes }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('journal_entries.index') }}" class="btn btn-secondary">عودة</a>
    </div>
</x-app-layout>
