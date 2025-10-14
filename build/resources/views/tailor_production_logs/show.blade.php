<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("تفاصيل سجل الإنتاج") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">الترزي: {{ $tailorProductionLog->tailor->name }}</h5>
                            <p class="card-text"><strong>نوع القطعة:</strong> {{ $tailorProductionLog->pieceRateDefinition->item_type }}</p>
                            <p class="card-text"><strong>الكمية:</strong> {{ $tailorProductionLog->quantity }}</p>
                            <p class="card-text"><strong>تاريخ الإنتاج:</strong> {{ $tailorProductionLog->production_date->format("Y-m-d") }}</p>
                            <p class="card-text"><strong>الحالة:</strong> {{ $tailorProductionLog->status }}</p>
                            <p class="card-text"><strong>ملاحظات:</strong> {{ $tailorProductionLog->notes }}</p>
                        </div>
                    </div>

                    <a href="{{ route("tailor_production_logs.index") }}" class="btn btn-primary mt-3">العودة إلى سجلات الإنتاج</a>
                    <a href="{{ route("tailor_production_logs.edit", $tailorProductionLog->id) }}" class="btn btn-warning mt-3">تعديل</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
