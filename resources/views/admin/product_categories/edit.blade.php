<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">تعديل تصنيف</h2>
    </x-slot>

    <div class="container mt-4">
        <form action="{{ route('admin.product-categories.update', $productCategory) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">اسم التصنيف</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $productCategory->name) }}" required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="parent_id" class="form-label">التصنيف الأب (اختياري)</label>
                <select name="parent_id" id="parent_id" class="form-control">
                    <option value="">-- لا يوجد --</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $productCategory->parent_id) == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">تحديث</button>
            <a href="{{ route('admin.product-categories.index') }}" class="btn btn-secondary">رجوع</a>
        </form>
    </div>
</x-app-layout>
