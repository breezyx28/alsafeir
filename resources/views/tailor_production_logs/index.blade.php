<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("سجلات إنتاج الترزية") }}
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

                    <a href="{{ route("tailor_production_logs.create") }}" class="btn btn-primary mb-3">إضافة سجل إنتاج جديد</a>

                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>الترزي</th>
                                <th>نوع القطعة</th>
                                <th>الكمية</th>
                                <th>تاريخ الإنتاج</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($productionLogs as $log)
                            <tr>
                                <td>{{ $log->tailor->name }}</td>
                                <td>{{ $log->pieceRateDefinition->item_type }}</td>
                                <td>{{ $log->quantity }}</td>
                                <td>{{ $log->production_date->format("Y-m-d") }}</td>
                                <td>{{ $log->status }}</td>
                                <td>
                                    <a href="{{ route("tailor_production_logs.show", $log->id) }}" class="btn btn-info btn-sm">عرض</a>
                                    <a href="{{ route("tailor_production_logs.edit", $log->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                                    <form action="{{ route("tailor_production_logs.destroy", $log->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'هل أنت متأكد؟\')">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">لا يوجد سجلات إنتاج مسجلة بعد.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
