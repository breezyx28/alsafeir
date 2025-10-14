<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">قائمة الدخل من {{ $from }} إلى {{ $to }}</h2>
    </x-slot>

    <div class="container py-4">

        <!-- نموذج اختيار الفترة -->
        <form method="GET" action="{{ route('income_statement.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="from_date" class="form-label">من تاريخ</label>
                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $from }}">
            </div>
            <div class="col-md-3">
                <label for="to_date" class="form-label">إلى تاريخ</label>
                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $to }}">
            </div>
            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary">عرض</button>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead class="table-dark text-center">
                <tr>
                    <th>الحساب</th>
                    <th>مدين</th>
                    <th>دائن</th>
                    <th>صافي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr class="text-center">
                    <td>{{ $row['account'] }}</td>
                    <td>{{ number_format($row['debit'], 2) }}</td>
                    <td>{{ number_format($row['credit'], 2) }}</td>
                    <td>{{ number_format($row['balance'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="table-secondary text-center">
                    <td><strong>الإجمالي</strong></td>
                    <td>{{ number_format($totalDebit, 2) }}</td>
                    <td>{{ number_format($totalCredit, 2) }}</td>
                    <td>{{ number_format($netProfit, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-app-layout>
