<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            ملف العميل: {{ $customer->name }}
        </h2>
    </x-slot>

    <div class="container-fluid py-4" x-data="measurementsHandler()" x-init="init()">

        
        {{-- بطاقة بيانات العميل الأساسية --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5>بيانات العميل</h5>
            </div>
            <div class="card-body">
                <p><strong>الاسم:</strong> {{ $customer->name }}</p>
                <p><strong>الهاتف:</strong> {{ $customer->phone_primary }}</p>
                <p><strong>درجة العميل:</strong> {{ $customer->customer_level }}</p>
            </div>
        </div>

        {{-- بطاقة المقاسات المسجلة --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">المقاسات المسجلة</h5>
                <button type="button" class="btn btn-primary" @click="openAddModal()">+ إضافة مقاس جديد</button>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>نوع التفصيل</th>
                                <th>تاريخ آخر تحديث</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customer->measurements as $measurement)
                                <tr>
                                    <td>{{ $measurement->detail_type }}</td>
                                    <td>{{ $measurement->updated_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning" @click="openEditModal({{ json_encode($measurement) }})">تعديل</button>
                                        <form action="{{ route('measurements.destroy', $measurement) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المقاس؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">لا توجد مقاسات مسجلة لهذا العميل.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Add/Edit Modal --}}
        <div class="modal fade" id="measurementModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" x-text="isEditMode ? 'تعديل المقاس' : 'إضافة مقاس جديد'"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form :action="formUrl" method="POST">
                        @csrf
                        <template x-if="isEditMode">
                            @method('PUT')
                        </template>
                        
                        <div class="modal-body">
                            {{-- Include the shared measurements form --}}
                            @include('customers.partials.measurements_form')
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-primary" x-text="isEditMode ? 'تحديث' : 'حفظ'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function measurementsHandler() {
            return {
                isEditMode: false,
                formUrl: '',
                measurement: {},
                modal: null,

                init() {
                    this.modal = new bootstrap.Modal(document.getElementById('measurementModal'));
                },

                openAddModal() {
                    this.isEditMode = false;
                    this.formUrl = `{{ route('measurements.store', $customer) }}`;
                    this.measurement = {}; // Clear form
                    this.modal.show();
                },

                openEditModal(measurementData) {
                    this.isEditMode = true;
                    this.formUrl = `/measurements/${measurementData.id}`;
                    this.measurement = measurementData;
                    this.modal.show();
                }
            }
        }
    </script>
</x-app-layout>
