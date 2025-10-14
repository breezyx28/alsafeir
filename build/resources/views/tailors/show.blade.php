<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("تفاصيل الترزي: ") }}{{ $tailor->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">الاسم: {{ $tailor->name }}</h5>
                            <p class="card-text"><strong>الهاتف:</strong> {{ $tailor->phone }}</p>
                            <p class="card-text"><strong>العنوان:</strong> {{ $tailor->address }}</p>
                            <p class="card-text"><strong>تاريخ الانضمام:</strong> {{ $tailor->join_date }}</p>
                            <p class="card-text"><strong>الحالة:</strong> {{ $tailor->status == 'active' ? 'نشط' : 'غير نشط' }}</p>
                            <p class="card-text"><strong>ملاحظات:</strong> {{ $tailor->notes }}</p>
                            <p class="card-text"><strong>رقم الهوية:</strong> {{ $tailor->id_number }}</p>
                        </div>
                    </div>

                    <a href="{{ route("tailors.index") }}" class="btn btn-primary mt-3">العودة إلى قائمة الترزية</a>
                    <a href="{{ route("tailors.edit", $tailor->id) }}" class="btn btn-warning mt-3">تعديل</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>