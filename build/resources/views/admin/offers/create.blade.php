<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">إضافة عرض جديد</h2>
    </x-slot>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.offers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">عنوان العرض</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">وصف العرض</label>
                    <textarea name="description" class="form-control" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">صورة العرض</label>
                    <input type="file" name="image" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label class="form-check-label" for="is_active">
                        تفعيل العرض (جعله مرئياً للعملاء)
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">حفظ العرض</button>
            </form>
        </div>
    </div>
</x-app-layout>
