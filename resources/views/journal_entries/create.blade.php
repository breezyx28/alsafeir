<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">إضافة قيد محاسبي جديد</h2>
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

        <form method="POST" action="{{ route('journal_entries.store') }}">
            @csrf

            <div class="mb-3">
                <label for="entry_date" class="form-label">تاريخ القيد</label>
                <input type="date" name="entry_date" id="entry_date" class="form-control" value="{{ old('entry_date', date('Y-m-d')) }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">الوصف</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            </div>

            <h5>عناصر القيد</h5>
            <div id="items-container">
                <div class="item-row row g-3 align-items-center mb-2">
                    <div class="col-md-5">
                        <label class="form-label">الحساب</label>
                        <select name="items[0][account_id]" class="form-select" required>
                            <option value="">اختر الحساب</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}" {{ old('items.0.account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->code }} - {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">مدين</label>
                        <input type="number" step="0.01" min="0" name="items[0][debit]" class="form-control debit" value="{{ old('items.0.debit', 0) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">دائن</label>
                        <input type="number" step="0.01" min="0" name="items[0][credit]" class="form-control credit" value="{{ old('items.0.credit', 0) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ملاحظات</label>
                        <input type="text" name="items[0][notes]" class="form-control" value="{{ old('items.0.notes') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-remove-item mt-4">حذف</button>
                    </div>
                </div>
            </div>

            <button type="button" id="add-item-btn" class="btn btn-primary mb-3">إضافة عنصر</button>

            <button type="submit" class="btn btn-success">حفظ القيد</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let itemsContainer = document.getElementById('items-container');
            let addItemBtn = document.getElementById('add-item-btn');

            addItemBtn.addEventListener('click', function () {
                let index = itemsContainer.querySelectorAll('.item-row').length;
                let newRow = document.createElement('div');
                newRow.classList.add('item-row', 'row', 'g-3', 'align-items-center', 'mb-2');
                newRow.innerHTML = `
                    <div class="col-md-5">
                        <label class="form-label">الحساب</label>
                        <select name="items[${index}][account_id]" class="form-select" required>
                            <option value="">اختر الحساب</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">مدين</label>
                        <input type="number" step="0.01" min="0" name="items[${index}][debit]" class="form-control debit" value="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">دائن</label>
                        <input type="number" step="0.01" min="0" name="items[${index}][credit]" class="form-control credit" value="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ملاحظات</label>
                        <input type="text" name="items[${index}][notes]" class="form-control">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-remove-item mt-4">حذف</button>
                    </div>
                `;
                itemsContainer.appendChild(newRow);
            });

            itemsContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-remove-item')) {
                    let rows = itemsContainer.querySelectorAll('.item-row');
                    if (rows.length > 1) {
                        e.target.closest('.item-row').remove();
                    } else {
                        alert('يجب وجود عنصر واحد على الأقل.');
                    }
                }
            });
        });
    </script>
</x-app-layout>
