<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("إدارة تقارير الجودة") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session("success"))
                        <div class="alert alert-success">
                            {{ session("success") }}
                        </div>
                    @endif

                    <a href="{{ route("quality_reports.create") }}" class="btn btn-primary mb-3">إضافة تقرير جودة جديد</a>

                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>الترزي</th>
                                <th>معرف الصنف</th>
                                <th>نوع المشكلة</th>
                                <th>الخطورة</th>
                                <th>الحالة</th>
                                <th>تاريخ الإبلاغ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($qualityReports as $report)
                            <tr>
                                <td>{{ $report->tailor->name }}</td>
                                <td>{{ $report->order_item_identifier }}</td>
                                <td>{{ $report->issue_type }}</td>
                                <td>{{ $report->severity }}</td>
                                <td>{{ $report->status }}</td>
                                <td>{{ $report->reported_date->format("Y-m-d") }}</td>
                                <td>
                                    <a href="{{ route("quality_reports.show", $report->id) }}" class="btn btn-info btn-sm">عرض</a>
                                    <a href="{{ route("quality_reports.edit", $report->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                                    <form action="{{ route("quality_reports.destroy", $report->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل انت متاكد ؟')">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">لا يوجد تقارير جودة مسجلة بعد.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>