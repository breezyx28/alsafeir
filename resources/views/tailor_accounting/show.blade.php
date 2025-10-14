<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("محاسبة الترزي: ") }}{{ $tailor->name }}
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

                    <h3 class="mb-3">ملخص الفترة ({{ $startDate }} إلى {{ $endDate }})</h3>

                    <form action="{{ route("tailor_accounting.show", $tailor->id) }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="start_date" class="form-label">تاريخ البدء:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-4">
                                <label for="end_date" class="form-label">تاريخ الانتهاء:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">تصفية</button>
                            </div>
                        </div>
                    </form>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-header">إجمالي الأجر المستحق</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ number_format($totalEarnings, 2) }} SAR</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-header">إجمالي السلفيات</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ number_format($totalAdvances, 2) }} SAR</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-info mb-3">
                                <div class="card-header">إجمالي المدفوعات</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ number_format($totalPayments, 2) }} SAR</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card text-white {{ $netPayable >= 0 ? 'bg-primary' : 'bg-danger' }} mb-4">
                        <div class="card-header">صافي المستحق للترزي</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ number_format($netPayable, 2) }} SAR</h5>
                        </div>
                    </div>

                    <hr>

                    <h4 class="mt-4">تفاصيل الإنتاج المكتمل</h4>
                    @if ($productionLogs->isEmpty())
                        <p>لا يوجد سجلات إنتاج مكتملة في هذه الفترة.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>تاريخ</th>
                                    <th>نوع القطعة</th>
                                    <th>الكمية</th>
                                    <th>أجر القطعة</th>
                                    <th>الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productionLogs as $log)
                                <tr>
                                    <td>{{ $log->production_date->format("Y-m-d") }}</td>
                                    <td>{{ $log->pieceRateDefinition->item_type }}</td>
                                    <td>{{ $log->quantity }}</td>
                                    <td>{{ $log->pieceRateDefinition->rate }}</td>
                                    <td>{{ number_format($log->quantity * $log->pieceRateDefinition->rate, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    <h4 class="mt-4">تفاصيل السلفيات</h4>
                    @if ($advances->isEmpty())
                        <p>لا يوجد سلفيات في هذه الفترة.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>تاريخ السلفة</th>
                                    <th>المبلغ</th>
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($advances as $advance)
                                <tr>
                                    <td>{{ $advance->advance_date->format("Y-m-d") }}</td>
                                    <td>{{ number_format($advance->amount, 2) }}</td>
                                    <td>{{ $advance->notes }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    <h4 class="mt-4">تفاصيل المدفوعات</h4>
                    @if ($payments->isEmpty())
                        <p>لا يوجد مدفوعات في هذه الفترة.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>تاريخ الدفعة</th>
                                    <th>المبلغ</th>
                                    <th>طريقة الدفع</th>
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format("Y-m-d") }}</td>
                                    <td>{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->payment_method }}</td>
                                    <td>{{ $payment->notes }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    <hr>

                    <h4 class="mt-4">تسجيل دفعة جديدة</h4>
                    <form action="{{ route("tailor_accounting.process_payment", $tailor->id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="amount">المبلغ:</label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="payment_method">طريقة الدفع:</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="cash">نقداً</option>
                                <option value="bank_transfer">تحويل بنكي</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="notes">ملاحظات (اختياري):</label>
                            <textarea class="form-control" id="notes" name="notes"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">تسجيل الدفعة</button>
                    </form>

                    <a href="{{ route("tailor_accounting.index") }}" class="btn btn-secondary mt-3">العودة إلى قائمة الترزية</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>