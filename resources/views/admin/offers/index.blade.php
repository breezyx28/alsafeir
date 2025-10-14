<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">إدارة العروض</h2>
    </x-slot>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span>قائمة العروض</span>
            <a href="{{ route('admin.offers.create') }}" class="btn btn-primary btn-sm">إضافة عرض جديد</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>العنوان</th>
                            <th>الحالة</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offers as $offer)
                        <tr>
                            <td><img src="{{ Storage::url($offer->image_path) }}" width="100"></td>
                            <td>{{ $offer->title }}</td>
                            <td>{!! $offer->is_active ? '<span class="badge bg-success">فعال</span>' : '<span class="badge bg-secondary">مخفي</span>' !!}</td>
                            <td>
                                <form action="{{ route('admin.offers.destroy', $offer) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
