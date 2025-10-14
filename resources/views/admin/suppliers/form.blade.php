@php
    $supplier = $supplier ?? null;
@endphp

<div class="mb-4">
    <label class="form-label">الاسم</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $supplier->name ?? '') }}" required>
</div>

<div class="mb-4">
    <label class="form-label">الهاتف</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone ?? '') }}">
</div>

<div class="mb-4">
    <label class="form-label">البريد</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email ?? '') }}">
</div>

<div class="mb-4">
    <label class="form-label">اسم الشركة</label>
    <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $supplier->company_name ?? '') }}">
</div>

<div class="mb-4">
    <label class="form-label">الرصيد (دائن)</label>
    <input type="number" step="0.01" name="credit_balance" class="form-control" value="{{ old('credit_balance', $supplier->credit_balance ?? 0) }}">
</div>
