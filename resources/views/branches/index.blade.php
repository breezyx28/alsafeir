<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-4">
            {{ __('قائمة الفروع') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-3 text-end">
                <a href="{{ route('branches.create') }}" class="btn btn-primary">
                    + إضافة فرع
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0 text-end">
                            <thead class="table-light">
                                <tr>
                                    <th>الاسم</th>
                                    <th>العنوان</th>
                                    <th>المدير</th>
                                    <th>الهاتف</th>
                                    <th>الحالة</th>
                                    <th>الخيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($branches as $branch)
                                    <tr>
                                        <td>{{ $branch->name }}</td>
                                        <td>{{ $branch->address }}</td>
                                        <td>{{ $branch->manager_name }}</td>
                                        <td>{{ $branch->phone }}</td>
                                        <td>
                                            @if($branch->status)
                                                <span class="badge bg-success">نشط</span>
                                            @else
                                                <span class="badge bg-danger">موقوف</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('branches.edit', $branch) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                                            <form action="{{ route('branches.destroy', $branch) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3">
                        {{ $branches->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
