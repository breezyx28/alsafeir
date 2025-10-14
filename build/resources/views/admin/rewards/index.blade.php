<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            قائمة الحوافز
        </h2>
    </x-slot>

    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success text-right">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3 text-end">
            <a href="{{ route('admin.rewards.create') }}" class="btn btn-primary">+ إضافة حافز</a>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>الموظف</th>
                            <th>التاريخ</th>
                            <th>المبلغ</th>
                            <th>السبب</th>
                            <th>الخيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rewards as $reward)
                            <tr>
                                <td>{{ $reward->employee->name ?? 'غير معروف' }}</td>
                                <td>{{ $reward->date }}</td>
                                <td>{{ number_format($reward->amount) }}</td>
                                <td>{{ $reward->reason ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.rewards.edit', $reward) }}" class="btn btn-sm btn-warning">تعديل</a>
                                    <form action="{{ route('admin.rewards.destroy', $reward) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">لا توجد حوافز مسجلة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $rewards->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
