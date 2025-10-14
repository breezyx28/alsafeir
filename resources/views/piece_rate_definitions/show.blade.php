<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("تفاصيل تعريف الأجر: ") }}{{ $pieceRateDefinition->item_type }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">نوع القطعة: {{ $pieceRateDefinition->item_type }}</h5>
                            <p class="card-text"><strong>الأجر:</strong> {{ $pieceRateDefinition->rate }}</p>
                        </div>
                    </div>

                    <a href="{{ route("piece_rate_definitions.index") }}" class="btn btn-primary mt-3">العودة إلى قائمة تعريفات الأجور</a>
                    <a href="{{ route("piece_rate_definitions.edit", $pieceRateDefinition->id) }}" class="btn btn-warning mt-3">تعديل</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>