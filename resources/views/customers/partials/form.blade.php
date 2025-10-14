@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row mb-3">
    <div class="col-md-6">
        <label for="name" class="form-label">اسم العميل</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $customer->name ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label for="phone_primary" class="form-label">رقم الهاتف الأساسي</label>
        <input type="text" id="phone_primary" name="phone_primary" class="form-control" value="{{ old('phone_primary', $customer->phone_primary ?? '') }}" required>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <label for="phone_secondary" class="form-label">رقم الهاتف الثانوي (اختياري)</label>
        <input type="text" id="phone_secondary" name="phone_secondary" class="form-control" value="{{ old('phone_secondary', $customer->phone_secondary ?? '') }}">
    </div>
    <div class="col-md-6">
        <label for="customer_level" class="form-label">درجة العميل</label>
        <select id="customer_level" name="customer_level" class="form-select" required>
            <option value="عابر" @selected(old('customer_level', $customer->customer_level ?? '') == 'عابر')>عابر</option>
            <option value="مميز" @selected(old('customer_level', $customer->customer_level ?? '') == 'مميز')>مميز</option>
            <option value="VIP" @selected(old('customer_level', $customer->customer_level ?? '') == 'VIP')>VIP</option>
            <option value="عضو" @selected(old('customer_level', $customer->customer_level ?? '') == 'عضو')>عضو</option>
        </select>
    </div>
</div>
