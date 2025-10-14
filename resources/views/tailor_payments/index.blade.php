<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("إدارة دفعات الترزية") }}
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

                    <a href="{{ route("tailor_payments.create") }}" class="btn btn-primary mb-3">إضافة دفعة جديدة</a>

                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>الترزي</th>
                                <th>المبلغ</th>
                                <th>تاريخ الدفعة</th>
                                <th>طريقة الدفع</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tailorPayments as $payment)
                            <tr>
                                <td>{{ $payment->tailor->name }}</td>
                                <td>{{ $payment->amount }}</td>
                                <td>{{ $payment->payment_date->format("Y-m-d") }}</td>
                                <td>{{ $payment->payment_method }}</td>
                                <td>
                                    <a href="{{ route("tailor_payments.show", $payment->id) }}" class="btn btn-info btn-sm">عرض</a>
                                    <a href="{{ route("tailor_payments.edit", $payment->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                                    <form action="{{ route("tailor_payments.destroy", $payment->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل انت متاكد ؟')">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">لا يوجد دفعات مسجلة بعد.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>