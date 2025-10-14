<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">إضافة منصرف جديد</h2>
    </x-slot>

    <div class="container py-3">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="expense_date" class="form-label">تاريخ المنصرف</label>
                <input type="date" name="expense_date" id="expense_date" class="form-control"
                       value="{{ old('expense_date', date('Y-m-d')) }}" required>
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">المبلغ</label>
                <input type="number" name="amount" id="amount" class="form-control"
                       min="0.01" step="0.01" value="{{ old('amount') }}" required>
            </div>

            <div class="mb-3">
                <label for="expense_account_id" class="form-label">حساب المصروف</label>
                <select name="expense_account_id" id="expense_account_id" class="form-select" required>
                    <option value="">اختر حساب المصروف</option>
                    @foreach($expenseAccounts as $acc)
                        <option value="{{ $acc->id }}" {{ old('expense_account_id') == $acc->id ? 'selected' : '' }}>
                            {{ $acc->code }} - {{ $acc->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="cash_account_id" class="form-label">حساب الدفع (صندوق/بنك)</label>
                <select name="cash_account_id" id="cash_account_id" class="form-select" required>
                    <option value="">اختر حساب الدفع</option>
                    @foreach($cashAccounts as $acc)
                        <option value="{{ $acc->id }}" {{ old('cash_account_id') == $acc->id ? 'selected' : '' }}>
                            {{ $acc->code }} - {{ $acc->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted d-block mt-1">
                    اختَر حساب الصندوق أو البنك المناسب (الحسابات من نوع "أصول").
                </small>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">ملاحظات</label>
                <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">حفظ المنصرف</button>
        </form>
    </div>
</x-app-layout>
