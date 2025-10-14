<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">دفتر الأستاذ</h2>
    </x-slot>

    <div class="container py-4">

        <form method="GET" action="{{ route('ledger.index') }}" class="mb-4 row g-3">
            <div class="col-md-6">
                <select name="account_id" class="form-select" required>
                    <option value="">-- اختر الحساب --</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>
                            {{ $acc->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">عرض</button>
            </div>
        </form>

        @if($account)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">الحساب: {{ $account->name }}</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="table-light">
                            <tr>
                                <th>التاريخ</th>
                                <th>الوصف</th>
                                <th>مدين</th>
                                <th>دائن</th>
                                <th>الرصيد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($entries as $item)
                                <tr>
                                    <td>{{ $item->journalEntry->entry_date }}</td>
                                    <td>{{ $item->journalEntry->description }}</td>
                                    <td>{{ number_format($item->debit, 2) }}</td>
                                    <td>{{ number_format($item->credit, 2) }}</td>
                                    <td>{{ number_format($item->running_balance, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">لا توجد حركات لهذا الحساب.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
