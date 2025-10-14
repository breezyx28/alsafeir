<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>بطاقات العمل - طلب {{ $order->order_number }}</title>
    <style>
        @media print {
            .no-print { display: none; }
            .card {
                page-break-after: always;
            }
            .note-checkbox { display: none; } /* نخفي checkbox عند الطباعة */
            .note-text { display: inline; }
            .note-text.hidden { display: none; }
        }

        body {
            font-family: 'Tajawal', sans-serif;
            width: 80mm;
            margin: 0 auto;
            padding: 0;
            color: #000;
            font-size: 12px;
            background: #fff;
        }

        .card {
            border: 1px solid #000;
            padding: 8px;
            margin: 8px auto;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            margin-bottom: 5px;
        }

        .header h3 {
            margin: 0;
            font-size: 14px;
        }

        .header p {
            margin: 2px 0;
            font-size: 12px;
        }

        .details, .notes {
            margin: 5px 0;
        }

        .details p, .notes p {
            margin: 2px 0;
        }

        .measurements-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3px 10px;
            font-size: 12px;
        }

        .section-title {
            margin-top: 6px;
            font-weight: bold;
            border-top: 1px dashed #000;
            padding-top: 4px;
            font-size: 13px;
        }

        ul {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .product-item {
            font-size: 12px;
            margin: 3px 0;
        }

        .text-center {
            text-align: center;
            margin-top: 10px;
        }

        button {
            padding: 5px 10px;
            font-size: 14px;
        }

        /* تنسيق الشيك بوكس عشان يظهر واضح */
        .note-checkbox {
            margin-left: 5px;
            transform: scale(1.3);
            cursor: pointer;
        }
    </style>
</head>
<body>

@foreach($order->measurements as $measurement)
    @for($i = 0; $i < $measurement->quantity; $i++)
        <div class="card">
            <div class="header">
                <h3>السفير جلابية</h3>
                <h3>بطاقة عمل للمشغل</h3>
                <p>طلب رقم: <strong>{{ $order->order_number }}</strong></p>
            </div>

            <div class="details">
                <p><strong>العميل:</strong> {{ $order->customer->name }}</p>
                <p><strong>نوع التفصيل:</strong> {{ $measurement->detail_type }}</p>
                <p><strong>تاريخ الاستلام :</strong> {{ $order->operator_delivery_date ?? '---' }}</p>
               @if($measurement->quantity)<div><strong>الكمية:</strong> {{ $measurement->quantity }}</div>@endif
            </div>

            <div class="section-title">القياسات</div>
                <div class="measurements-grid" style="grid-template-columns: repeat(2, 1fr); gap:3px 10px;">
                    @if(in_array($measurement->detail_type, ['جلابية','عراقي']))                        {{-- الجهة اليمنى: فقط المقاسات العلوية --}}
                        <div>
                            
                            @if($measurement->length)<div><strong>الطول:</strong> {{ $measurement->length }}</div>@endif
                            @if($measurement->shoulder_width)<div><strong>الكتف:</strong> {{ $measurement->shoulder_width }}</div>@endif
                            @if($measurement->arm_length)<div><strong>طول الذراع:</strong> {{ $measurement->arm_length }}</div>@endif
                            @if($measurement->arm_width)<div><strong>عرض اليد:</strong> {{ $measurement->arm_width }}</div>@endif
                            @if($measurement->sides)<div><strong>الجوانب:</strong> {{ $measurement->sides }}</div>@endif
                            @if($measurement->neck)<div><strong>القبة:</strong> {{ $measurement->neck }}</div>@endif
                            @if($measurement->cuff_type)<div><strong>نوع الكفة:</strong> {{ $measurement->cuff_type }}</div>@endif
                            @if($measurement->fabric_detail)<div><strong>تفصيل القماش:</strong> {{ $measurement->fabric_detail }}</div>@endif
                             @if($measurement->buttons)<div><strong>نوع الزراير:</strong> {{ $measurement->buttons }}</div>@endif
                            @if($measurement->qitan)<div><strong>القيطان :</strong> {{ $measurement->qitan }}</div>@endif
                        </div>
                    @elseif($measurement->detail_type === 'سروال')

                        {{-- الجهة الشمال: مقاسات السروال --}}
                        <div>
                            @if($measurement->pants_length)<div><strong>طول السروال:</strong> {{ $measurement->pants_length }}</div>@endif
                            @if($measurement->pants_type)<div><strong>نوع السروال:</strong> {{ $measurement->pants_type }} || {{ $measurement->pants_size }}</div>@endif
                            {{-- @if($measurement->pants_size)<div><strong>مقاس السروال:</strong> {{ $measurement->pants_size }}</div>@endif --}}
                        </div>
                    @elseif($measurement->detail_type === 'على الله')
                        {{-- كلا الجانبين: كل المقاسات --}}
                        <div>
                            @if($measurement->length)<div><strong>الطول:</strong> {{ $measurement->length }}</div>@endif
                            @if($measurement->shoulder_width)<div><strong>الكتف:</strong> {{ $measurement->shoulder_width }}</div>@endif
                            @if($measurement->arm_length)<div><strong>طول الذراع:</strong> {{ $measurement->arm_length }}</div>@endif
                            @if($measurement->arm_width)<div><strong>عرض اليد:</strong> {{ $measurement->arm_width }}</div>@endif
                            @if($measurement->sides)<div><strong>الجوانب:</strong> {{ $measurement->sides }}</div>@endif
                            @if($measurement->neck)<div><strong>القبة:</strong> {{ $measurement->neck }}</div>@endif
                            @if($measurement->cuff_type)<div><strong>نوع الكفة:</strong> {{ $measurement->cuff_type }}</div>@endif
                            @if($measurement->fabric_detail)<div><strong>تفصيل القماش:</strong> {{ $measurement->fabric_detail }}</div>@endif
                            @if($measurement->buttons)<div><strong>نوع الزراير:</strong> {{ $measurement->buttons }}</div>@endif
                            @if($measurement->qitan)<div><strong>القيطان :</strong> {{ $measurement->qitan }}</div>@endif
                        </div>
                        <div>
                            @if($measurement->pants_length)<div><strong>طول السروال:</strong> {{ $measurement->pants_length }}</div>@endif
                            @if($measurement->pants_type)<div><strong>نوع السروال:</strong> {{ $measurement->pants_type }} || {{ $measurement->pants_size }}</div>@endif
                        </div>
                    @endif
                </div>


           @if($order->products->count() > 0)
    <div class="section-title">الأقمشة</div>
    <ul>
        @foreach($order->products as $product)
            <li class="product-item">
                - {{ $product->variant->product->name }} ({{ $product->variant->color }})
                @if($product->notes)
                    <input type="checkbox" class="note-checkbox" checked="checked">
                    <div><small>ملاحظات: {{ $product->notes }}</small></div>
                @endif
            </li>
        @endforeach
    </ul>
@else
    <p>لا يوجد</p>
@endif


            <div class="notes">
                <p><strong>ملاحظات:</strong></p>
                @if($order->notes)
                    @foreach(explode("\n", $order->notes) as $note)
                        <div>
                            <input type="checkbox" class="note-checkbox" checked="checked">
                            <span class="note-text">{{ $note }}</span>
                        </div>
                    @endforeach
                @else
                    <p>لا يوجد</p>
                @endif
            </div>

        </div>
    @endfor
@endforeach

<div class="text-center no-print">
    <button onclick="window.print()">طباعة</button>
</div>

</body>
<script>
    window.addEventListener('beforeprint', function() {
        document.querySelectorAll('.note-checkbox').forEach(cb => {
            const text = cb.nextElementSibling;
            if (!cb.checked) {
                text.classList.add('hidden'); // نخفي الملاحظة الغير مختارة
            } else {
                text.classList.remove('hidden');
            }
        });
    });

    // إعادة عرض كل الملاحظات بعد الطباعة
    window.addEventListener('afterprint', function() {
        document.querySelectorAll('.note-text').forEach(text => {
            text.classList.remove('hidden');
        });
    });
</script>
</html>
