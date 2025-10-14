<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("تفاصيل الدفعة") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">الترزي: {{ $tailorPayment->tailor->name }}</h5>
                            <p class="card-text"><strong>المبلغ:</strong> {{ $tailorPayment->amount }}</p>
                            <p class="card-text"><strong>تاريخ الدفعة:</strong> {{ $tailorPayment->payment_date->format("Y-m-d") }}</p>
                            <p class="card-text"><strong>طريقة الدفع:</strong> {{ $tailorPayment->payment_method }}</p>
                            <p class="card-text"><strong>ملاحظات:</strong> {{ $tailorPayment->notes }}</p>
                        </div>
                    </div>

                    <a href="{{ route("tailor_payments.index") }}" class="btn btn-primary mt-3">العودة إلى قائمة الدفعات</a>
                    <a href="{{ route("tailor_payments.edit", $tailorPayment->id) }}" class="btn btn-warning mt-3">تعديل</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>