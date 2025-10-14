<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("إدارة سلفيات الترزية") }}
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

                    <a href="{{ route("tailor_advances.create") }}" class="btn btn-primary mb-3">إضافة سلفة جديدة</a>

                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>الترزي</th>
                                <th>المبلغ</th>
                                <th>تاريخ السلفة</th>
                                <th>ملاحظات</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tailorAdvances as $advance)
                            <tr>
                                <td>{{ $advance->tailor->name }}</td>
                                <td>{{ $advance->amount }}</td>
                                <td>{{ $advance->advance_date->format("Y-m-d") }}</td>
                                <td>{{ $advance->notes }}</td>
                                <td>
                                    <a href="{{ route("tailor_advances.show", $advance->id) }}" class="btn btn-info btn-sm">عرض</a>
                                    <a href="{{ route("tailor_advances.edit", $advance->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                                    <form action="{{ route("tailor_advances.destroy", $advance->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">لا يوجد سلفيات مسجلة بعد.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>