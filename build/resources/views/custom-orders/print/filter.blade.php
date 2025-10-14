<x-app-layout>
    <div class="container py-4">

        <div class="card">
            <div class="card-header">🖨 إعدادات الطباعة</div>
            <div class="card-body">
                <form action="{{ route('custom-orders.print.generate') }}" method="GET" target="_blank">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-select">
                                <option value="">كل الحالات</option>
                                <option value="جاري التنفيذ">جاري التنفيذ</option>
                                <option value="جاهز للتسليم">جاهز للتسليم</option>
                                <option value="تم التسليم">تم التسليم</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">تاريخ معين (اختياري)</label>
                            <input type="date" name="date" class="form-control">
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary w-100">🔍 عرض للطباعة</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
