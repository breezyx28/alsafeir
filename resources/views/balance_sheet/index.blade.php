<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">الميزانية العمومية</h2>
    </x-slot>

    <div class="container py-4">

        

        <div class="row">
            <div class="col-md-6">
                <form method="GET" class="row g-3 mb-3">
            <div class="col-md-3">
                <label>من تاريخ</label>
                <input type="date" name="from_date" class="form-control" value="{{ $from }}">
            </div>
            <div class="col-md-3">
                <label>إلى تاريخ</label>
                <input type="date" name="to_date" class="form-control" value="{{ $to }}">
            </div>
            <div class="col-md-3 align-self-end">
                <button class="btn btn-primary">عرض</button>
            </div>
        </form>
                <h5 class="text-center">الأصول</h5>
                <table class="table table-bordered table-striped text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>الحساب</th>
                            <th>الرصيد</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assets as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ number_format($item['balance'], 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-secondary">
                            <td><strong>الإجمالي</strong></td>
                            <td>{{ number_format($totalAssets, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <h5 class="text-center">الخصوم وحقوق الملكية</h5>
                <table class="table table-bordered table-striped text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>الحساب</th>
                            <th>الرصيد</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($liabilities as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ number_format($item['balance'], 2) }}</td>
                        </tr>
                        @endforeach

                        @foreach($equity as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ number_format($item['balance'], 2) }}</td>
                        </tr>
                        @endforeach

                        <tr class="table-secondary">
                            <td><strong>الإجمالي</strong></td>
                            <td>{{ number_format($totalLiabilities + $totalEquity, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
