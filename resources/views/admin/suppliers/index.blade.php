<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">قائمة الموردين</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary mb-3">إضافة مورد</a>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الهاتف</th>
                            <th>البريد</th>
                            <th>الشركة</th>
                            <th>الرصيد</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ $supplier->email }}</td>
                            <td>{{ $supplier->company_name }}</td>
                            <td>{{ number_format($supplier->credit_balance, 2) }}</td>
                            <td>
                                <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning">تعديل</a>
                                <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">حذف</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
