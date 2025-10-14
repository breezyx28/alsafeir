<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("تفاصيل تقرير الجودة") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">الترزي: {{ $qualityReport->tailor->name }}</h5>
                            <p class="card-text"><strong>معرف الصنف/القطعة:</strong> {{ $qualityReport->order_item_identifier }}</p>
                            <p class="card-text"><strong>نوع المشكلة:</strong> {{ $qualityReport->issue_type }}</p>
                            <p class="card-text"><strong>وصف المشكلة:</strong> {{ $qualityReport->description }}</p>
                            <p class="card-text"><strong>الخطورة:</strong> {{ $qualityReport->severity }}</p>
                            <p class="card-text"><strong>الحالة:</strong> {{ $qualityReport->status }}</p>
                            <p class="card-text"><strong>المبلغ عنه بواسطة:</strong> {{ $qualityReport->reported_by }}</p>
                            <p class="card-text"><strong>تاريخ الإبلاغ:</strong> {{ $qualityReport->reported_date->format("Y-m-d") }}</p>
                        </div>
                    </div>

                    <a href="{{ route("quality_reports.index") }}" class="btn btn-primary mt-3">العودة إلى قائمة تقارير الجودة</a>
                    <a href="{{ route("quality_reports.edit", $qualityReport->id) }}" class="btn btn-warning mt-3">تعديل</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>