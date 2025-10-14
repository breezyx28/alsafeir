<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">قائمة تحويلات المخزون بين الفروع</h2>
    </x-slot>

    <div class="container mt-4">

        <a href="{{ route('admin.stock_transfers.create') }}" class="btn btn-primary mb-3">إنشاء تحويل جديد</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form method="GET" action="{{ route('admin.stock_transfers.index') }}" class="row g-3 mb-3">
            <div class="col-md-3">
                <label for="from_branch_id" class="form-label">فرع المصدر</label>
                <select name="from_branch_id" id="from_branch_id" class="form-control">
                    <option value="">الكل</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('from_branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="to_branch_id" class="form-label">فرع الوجهة</label>
                <select name="to_branch_id" id="to_branch_id" class="form-control">
                    <option value="">الكل</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('to_branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="reference" class="form-label">المرجع</label>
                <input type="text" name="reference" id="reference" class="form-control" value="{{ request('reference') }}" placeholder="بحث في المرجع">
            </div>

            <div class="col-md-3">
                <label for="transfer_date" class="form-label">تاريخ التحويل</label>
                <input type="date" name="transfer_date" id="transfer_date" class="form-control" value="{{ request('transfer_date') }}">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">بحث</button>
                <a href="{{ route('admin.stock_transfers.index') }}" class="btn btn-secondary">إعادة تعيين</a>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>الرقم</th>
                    <th>فرع المصدر</th>
                    <th>فرع الوجهة</th>
                    <th>تاريخ التحويل</th>
                    <th>المرجع</th>
                    <th>ملاحظات</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $transfer)
                    <tr>
                        <td>{{ $transfer->id }}</td>
                        <td>{{ $transfer->fromBranch->name }}</td>
                        <td>{{ $transfer->toBranch->name }}</td>
                        <td>{{ $transfer->transfer_date }}</td>
                        <td>{{ $transfer->reference ?? '-' }}</td>
                        <td>{{ $transfer->notes ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.stock_transfers.show', $transfer) }}" class="btn btn-sm btn-primary">عرض</a>
                            <form action="{{ route('admin.stock_transfers.destroy', $transfer) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">لا توجد تحويلات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">{{ $transfers->links() }}</div>

    </div>
</x-app-layout>
