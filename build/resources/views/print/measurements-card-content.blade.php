@props(['item'])

<div class="measurement-card p-3">
    <div class="text-center mb-4">
        <h3 class="fw-bold">السفير جلابية</h3>
        <h3 class="fw-bold">بطاقة عمل للمشغل</h3>
        <p class="lead">طلب رقم: {{ $item->order->order_number }}</p>
    </div>

    <div class="row border-bottom pb-2 mb-3">
        <div class="col-6"><strong>العميل:</strong> {{ $item->order->customer->name }}</div>
        <div class="col-6"><strong>تاريخ استلام المشغل:</strong> {{ $item->order->operator_delivery_date ?? 'غير محدد' }}</div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">{{ $item->detail_type }} (الكمية: {{ $item->quantity }})</h4>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @if(in_array($item->detail_type, ['جلابية', 'عراقي', 'على الله']))
                    <div class="col-md-4"><strong>الطول:</strong> {{ $item->length ?? '-' }}</div>
                    <div class="col-md-4"><strong>الكتف:</strong> {{ $item->shoulder_width ?? '-' }}</div>
                    <div class="col-md-4"><strong>طول الذراع:</strong> {{ $item->arm_length ?? '-' }}</div>
                    <div class="col-md-4"><strong>عرض اليد:</strong> {{ $item->arm_width ?? '-' }}</div>
                    <div class="col-md-4"><strong>الجوانب:</strong> {{ $item->sides ?? '-' }}</div>
                    <div class="col-md-4"><strong>القبة:</strong> {{ $item->neck ?? '-' }}</div>
                    <div class="col-md-4"><strong>تفصيل القماش:</strong> {{ $item->fabric_detail ?? '-' }}</div>
                @endif
                @if(in_array($item->detail_type, ['سروال', 'على الله']))
                    <div class="col-md-4"><strong>طول السروال:</strong> {{ $item->pants_length ?? '-' }}</div>
                    <div class="col-md-4"><strong>نوع السروال:</strong> {{ $item->pants_type ?? '-' }}</div>
                @endif
            </div>
        </div>
    </div>

    @if($item->order->products->count() > 0)
        <h5 class="mt-4">ملاحظات الأقمشة</h5>
        <ul class="list-group">
            @foreach($item->order->products as $product)
                @if($product->notes)
                    <li class="list-group-item">
                        <strong>{{ $product->variant->product->name }} ({{ $product->variant->color }}):</strong>
                        {{ $product->notes }}
                    </li>
                @endif
            @endforeach
        </ul>
    @endif

    @if($item->order->notes)
        <h5 class="mt-4">ملاحظات عامة على الطلب</h5>
        <p>{{ $item->order->notes }}</p>
    @endif
</div>
