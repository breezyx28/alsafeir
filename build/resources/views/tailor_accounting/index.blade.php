<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("محاسبة الترزية") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1>اختر ترزي لعرض المحاسبة</h1>

                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>الهاتف</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tailors as $tailor)
                            <tr>
                                <td>{{ $tailor->name }}</td>
                                <td>{{ $tailor->phone }}</td>
                                <td>
                                    <a href="{{ route("tailor_accounting.show", $tailor->id) }}" class="btn btn-primary btn-sm">عرض المحاسبة</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3">لا يوجد ترزية مسجلين بعد.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>