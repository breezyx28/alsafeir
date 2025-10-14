<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">تعديل المديونية</h2>
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

        <form action="{{ route('debts.update', $debt->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">الاسم</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $debt->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">الهاتف</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $debt->phone) }}">
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">النوع</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="customer" {{ old('type', $debt->type)=='customer' ? 'selected':'' }}>عميل</option>
                    <option value="supplier" {{ old('type', $debt->type)=='supplier' ? 'selected':'' }}>مورد</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">الحالة</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="debit" {{ old('status', $debt->status)=='debit' ? 'selected':'' }}>مدين</option>
                    <option value="credit" {{ old('status', $debt->status)=='credit' ? 'selected':'' }}>دائن</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="total_amount" class="form-label">المبلغ الكلي</label>
                <input type="number" name="total_amount" id="total_amount" class="form-control" step="0.01" value="{{ old('total_amount', $debt->total_amount) }}" required>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">ملاحظات</label>
                <textarea name="notes" id="notes" class="form-control">{{ old('notes', $debt->notes) }}</textarea>
            </div>

            {{-- الأقساط --}}
            <div class="mb-3">
                <label for="installments_count" class="form-label">عدد الأقساط</label>
                <input type="number" name="installments_count" id="installments_count" class="form-control" value="{{ $debt->installments->count() }}" min="1">
            </div>

            <div class="mb-3" id="installment-dates-container">
                @foreach($debt->installments as $inst)
                    <input type="date" name="installment_dates[]" class="form-control mb-1" value="{{ $inst->installment_date }}">
                @endforeach
            </div>

            <button type="submit" class="btn btn-success">تحديث</button>
        </form>
    </div>

    <script>
        const installmentsInput = document.getElementById('installments_count');
        const container = document.getElementById('installment-dates-container');

        installmentsInput?.addEventListener('input', function() {
            container.innerHTML = '';
            const count = parseInt(this.value) || 0;
            for(let i=0; i<count; i++){
                const input = document.createElement('input');
                input.type = 'date';
                input.name = 'installment_dates[]';
                input.className = 'form-control mb-1';
                container.appendChild(input);
            }
        });
    </script>
</x-app-layout>
