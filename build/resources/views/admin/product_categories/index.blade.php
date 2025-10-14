<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">إدارة التصنيفات</h2>
    </x-slot>

    <div class="container mt-4">
        <a href="{{ route('admin.product-categories.create') }}" class="btn btn-success mb-3">تصنيف جديد</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>التصنيف الأب</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $cat)
                    <tr>
                        <td>{{ $cat->name }}</td>
                        <td>{{ $cat->parent?->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.product-categories.edit', $cat) }}" class="btn btn-sm btn-warning">تعديل</a>

                            <form action="{{ route('admin.product-categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('متأكد من الحذف؟')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
