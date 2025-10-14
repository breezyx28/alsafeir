<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">قيود اليومية</h2>
    </x-slot>

    <div class="container py-3">
        <a href="{{ route('journal_entries.create') }}" class="btn btn-primary mb-3">إضافة قيد جديد</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>الوصف</th>
                    <th>المستخدم</th>
                    <th>إجمالي المدين</th>
                    <th>إجمالي الدائن</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($entries as $entry)
                    <tr>
                        <td>{{ $entry->entry_date }}</td>
                        <td>{{ $entry->description }}</td>
                        <td>{{ $entry->creator->name ?? 'غير محدد' }}</td>
                        <td>{{ number_format($entry->items->sum('debit'), 2) }}</td>
                        <td>{{ number_format($entry->items->sum('credit'), 2) }}</td>
                        <td>
                            <a href="{{ route('journal_entries.show', $entry->id) }}" class="btn btn-sm btn-info">عرض</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $entries->links() }}
    </div>
</x-app-layout>
