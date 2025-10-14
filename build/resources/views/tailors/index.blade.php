<x-app-layout> {{-- استخدام مكون x-app-layout --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("إدارة الترزية") }}
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

                    <a href="{{ route("tailors.create") }}" class="btn btn-primary mb-3">إضافة ترزي جديد</a>

                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>الهاتف</th>
                                <th>الحالة</th>
                                <th>تاريخ الانضمام</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tailors as $tailor)
                            <tr>
                                <td>{{ $tailor->name }}</td>
                                <td>{{ $tailor->phone }}</td>
                                <td>{{ $tailor->status == 'active' ? 'نشط' : 'غير نشط' }}</td>
                                <td>{{ $tailor->join_date->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route("tailors.show", $tailor->id) }}" class="btn btn-info btn-sm">عرض</a>
                                    <a href="{{ route("tailors.edit", $tailor->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                                    <form action="{{ route("tailors.destroy", $tailor->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">لا يوجد ترزية مسجلين بعد.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>