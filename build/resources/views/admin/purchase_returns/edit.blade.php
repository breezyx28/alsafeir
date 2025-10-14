<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">تعديل عملية الإرجاع</h2>
    </x-slot>

    <div class="container mt-4">
        <form method="POST" action="{{ route('admin.purchase_returns.update', $purchaseReturn) }}">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="return_date">تاريخ الإرجاع</label>
                <input type="date" name="return_date" class="form-control" value="{{ old('return_date', $purchaseReturn->return_date) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="notes">السبب</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $purchaseReturn->notes) }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">تحديث</button>
            <a href="{{ route('admin.purchase_returns.index') }}" class="btn btn-secondary">رجوع</a>
        </form>
    </div>
</x-app-layout>
