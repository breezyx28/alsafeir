<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('قائمة الموظفين') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-3 text-end">
                <a href="{{ route('employees.create') }}" class="btn btn-primary">+ إضافة موظف</a>
            </div>

            <div class="table-responsive bg-white shadow rounded p-3">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>الاسم</th>
                            <th>البريد</th>
                            <th>الهاتف</th>
                            <th>الوظيفة</th>
                            <th>الفرع</th>
                            <th>الحالة</th>
                            <th>الخيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->phone }}</td>
                                <td>{{ $employee->position }}</td>
                                <td>{{ $employee->branch->name ?? '-' }}</td>
                                <td>
                                    @if($employee->status)
                                        <span class="text-success">نشط</span>
                                    @else
                                        <span class="text-danger">موقوف</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-info">تعديل</a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
