<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">بيان التدفقات النقدية من {{ $from }} إلى {{ $to }}</h2>
    </x-slot>

    <div class="container py-4">
        <form method="GET" class="row mb-4">
            <div class="col-md-3">
                <input type="date" name="from_date" class="form-control" value="{{ $from }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="to_date" class="form-control" value="{{ $to }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">عرض</button>
            </div>
        </form>

        <table class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>الحساب</th>
                    <th>وارد</th>
                    <th>صادر</th>
                    <th>الرصيد</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>{{ $row['account'] }}</td>
                    <td>{{ number_format($row['inflow'], 2) }}</td>
                    <td>{{ number_format($row['outflow'], 2) }}</td>
                    <td>{{ number_format($row['balance'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="table-secondary">
                    <td><strong>الإجمالي</strong></td>
                    <td>{{ number_format($totalIn, 2) }}</td>
                    <td>{{ number_format($totalOut, 2) }}</td>
                    <td>{{ number_format($netCash, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-app-layout>
