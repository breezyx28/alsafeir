<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("إدارة أجور القطع") }}
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

                    <a href="{{ route("piece_rate_definitions.create") }}" class="btn btn-primary mb-3">إضافة تعريف أجر جديد</a>

                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>نوع القطعة</th>
                                <th>الأجر</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pieceRateDefinitions as $definition)
                            <tr>
                                <td>{{ $definition->item_type }}</td>
                                <td>{{ $definition->rate }}</td>
                                <td>
                                    <a href="{{ route("piece_rate_definitions.show", $definition->id) }}" class="btn btn-info btn-sm">عرض</a>
                                    <a href="{{ route("piece_rate_definitions.edit", $definition->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                                    <form action="{{ route("piece_rate_definitions.destroy", $definition->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3">لا يوجد تعريفات أجور مسجلة بعد.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>