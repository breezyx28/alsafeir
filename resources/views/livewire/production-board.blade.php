<div class="container-fluid py-4">

    <!-- شريط البحث -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <input wire:model.debounce.200ms="search" type="text" class="form-control form-control-lg" placeholder="ابحث برقم الطلب أو اسم العميل...">
        </div>
    </div>

    <div class="row" wire:poll.15s>
        {{-- العمود الأول: قيد الانتظار --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-secondary text-white"><h5 class="mb-0">قيد الانتظار ({{ $pending->count() }})</h5></div>
                <div class="card-body kanban-column">
                    @forelse ($pending as $item)
                        <div class="kanban-card alert alert-secondary">
                            <strong>{{ $item->detail_type }} (x{{ $item->quantity }})</strong>
                            <p class="mb-1">طلب: {{ $item->order->order_number }}</p>
                            <p class="mb-1 small text-muted">العميل: {{ $item->order->customer->name }}</p>
                            <div class="mt-2">
                                <button wire:click="showDetails({{ $item->id }})" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#detailsModal">عرض التفاصيل</button>
                                <button wire:click="updateStatus({{ $item->id }}, 'تحت التنفيذ')" class="btn btn-sm btn-warning">بدء التنفيذ</button>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">لا توجد قطع قيد الانتظار.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- العمود الثاني: تحت التنفيذ --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-warning text-dark"><h5 class="mb-0">تحت التنفيذ ({{ $inProgress->count() }})</h5></div>
                <div class="card-body kanban-column">
                    @forelse ($inProgress as $item)
                        <div class="kanban-card alert alert-warning">
                            <strong>{{ $item->detail_type }} (x{{ $item->quantity }})</strong>
                            <p class="mb-1">طلب: {{ $item->order->order_number }}</p>
                            <p class="mb-1 small text-muted">العميل: {{ $item->order->customer->name }}</p>
                            <div class="mt-2">
                                <button wire:click="showDetails({{ $item->id }})" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#detailsModal">عرض التفاصيل</button>
                                <button wire:click="updateStatus({{ $item->id }}, 'جاهزة في المشغل')" class="btn btn-sm btn-success">إنهاء وجاهزة</button>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">لا توجد قطع تحت التنفيذ.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- العمود الثالث: جاهزة في المشغل --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white"><h5 class="mb-0">جاهزة في المشغل ({{ $ready->count() }})</h5></div>
                <div class="card-body kanban-column">
                    @forelse ($ready as $item)
                        <div class="kanban-card alert alert-success">
                            <strong>{{ $item->detail_type }} (x{{ $item->quantity }})</strong>
                            <p class="mb-1">طلب: {{ $item->order->order_number }}</p>
                            <p class="mb-1 small text-muted">العميل: {{ $item->order->customer->name }}</p>
                            <div class="mt-2">
                                <button wire:click="showDetails({{ $item->id }})" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#detailsModal">عرض التفاصيل</button>
                                {{-- يمكن إضافة زر لإرجاعها "تحت التنفيذ" إذا أردنا --}}
                                <button wire:click="updateStatus({{ $item->id }}, 'تحت التنفيذ')" class="btn btn-sm btn-outline-dark">إرجاع</button>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">لا توجد قطع جاهزة.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لعرض التفاصيل -->
    <div wire:ignore.self class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">تفاصيل بطاقة العمل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($selectedMeasurement)
                        @include('print.measurements-card-content', ['item' => $selectedMeasurement])
                    @else
                        <p>جاري تحميل التفاصيل...</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .kanban-column { min-height: 400px; }
    </style>
</div>
