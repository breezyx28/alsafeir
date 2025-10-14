<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">تعديل منصرف</h2>
    </x-slot>

    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('expenses.update', $expense) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">التاريخ</label>
                        <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', $expense->expense_date) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الحساب</label>
                        <select name="account_id" class="form-control" required>
                            @foreach($accounts as $id => $name)
                                <option value="{{ $id }}" @selected($expense->account_id == $id)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الحساب النقدي</label>
                        <select name="cash_account_id" class="form-control" required>
                            @foreach($cashAccounts as $id => $name)
                                <option value="{{ $id }}" @selected($expense->cash_account_id == $id)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">المبلغ</label>
                        <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $expense->amount) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control">{{ old('notes', $expense->notes) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success">تحديث</button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">إلغاء</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
