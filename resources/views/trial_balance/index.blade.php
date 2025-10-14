<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">ميزان المراجعة</h2>
    </x-slot>

    <div class="container py-4">

        <form class="mb-4" method="GET" action="{{ route('trial_balance.index') }}">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">تصفية</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>الحساب</th>
                    <th>مدين</th>
                    <th>دائن</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trialBalance as $item)
                <tr>
                    <td>{{ $item['account_name'] }}</td>
                    <td>{{ number_format($item['debit'], 2) }}</td>
                    <td>{{ number_format($item['credit'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="fw-bold">
                    <td>الإجمالي</td>
                    <td>{{ number_format($totalDebit, 2) }}</td>
                    <td>{{ number_format($totalCredit, 2) }}</td>
                </tr>
            </tbody>
        </table>

    </div>
</x-app-layout>
