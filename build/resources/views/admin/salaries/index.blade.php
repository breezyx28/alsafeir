<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">قائمة المرتبات</h2>
    </x-slot>

    <div class="container py-4">
        <a href="{{ route('admin.salaries.create') }}" class="btn btn-success mb-3">+ احتساب مرتب جديد</a>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>الموظف</th>
                            <th>الشهر</th>
                            <th>الراتب الأساسي</th>
                            <th>الحوافز</th>
                            <th>الخصومات</th>
                            <th>خصم الغياب</th>
                            <th>الصافي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($salaries as $salary)
                            <tr>
                                <td>{{ $salary->employee->name ?? '—' }}</td>
                                <td>{{ $salary->month }}/{{ $salary->year }}</td>
                                <td>{{ number_format($salary->basic_salary) }}</td>
                                <td>{{ number_format($salary->bonuses_total) }}</td>
                                <td>{{ number_format($salary->penalties_total) }}</td>
                                <td>{{ number_format($salary->absence_deduction) }}</td>
                                <td class="fw-bold text-success">{{ number_format($salary->net_salary) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">لا توجد مرتبات محسوبة بعد</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $salaries->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
