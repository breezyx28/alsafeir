<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">إدارة العملاء</h2>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>قائمة العملاء ({{ $customers->total() }})</span>
                <a href="{{ route('customers.create') }}" class="btn btn-success">+ إضافة عميل جديد</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="GET" action="{{ route('customers.index') }}" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو رقم الهاتف..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">بحث</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>الهاتف الأساسي</th>
                                <th>درجة العميل</th>
                                <th>تاريخ الانضمام</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->phone_primary }}</td>
                                    <td><span class="badge bg-info">{{ $customer->customer_level }}</span></td>
                                    <td>{{ $customer->created_at }}</td>
                                    <td>
                                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning">تعديل</a>
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline" onsubmit="return confirm('تحذير! سيتم حذف العميل وكل طلباته ومقاساته. هل أنت متأكد؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                        </form>
                                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-primary">عرض</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">لا يوجد عملاء.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
