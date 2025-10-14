<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة بيع - السفير جلابية</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* إعدادات عامة للشاشة */
        html, body {
            margin: 0;
            padding: 0;
            background: #f9f9f9;
            font-family: 'Tajawal', sans-serif;
            font-size: 11px;
        }
        .page {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 10px 0;
        }
        /* العرض الآمن 72mm لأن معظم طابعات 80mm تطبع فعليًا ~72mm */
        .invoice {
            background: #fff;
            width: 78mm; /* Increased width for 80mm printer */
            max-width: 78mm; /* Increased max-width for 80mm printer */
            padding: 5mm; /* Adjusted padding to prevent cutting */
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            color: #000;
            margin: 0 auto; /* لتمركز الفاتورة في الصفحة */
            text-align: center; /* يجعل كل النصوص في الوسط */
        }

        .item {
            display: flex;
            justify-content: space-between;
            gap: 3px; /* Reduced gap */
            margin: 1px 0; /* Reduced margin */
            white-space: normal;
            word-break: break-word;
            text-align: right; /* جدول العناصر على اليمين */
            line-height: 1.2; /* Adjusted line height for better spacing */
        }

        .item .name { flex: 1; }
        .item .sum  { white-space: nowrap; }

        /* بقية الأكواد تبقى كما هي */

        .header, .footer { text-align: center; }
        .header h1 { margin: 2px 0; font-size: 13px; }
        .header p { margin: 1px 0; font-size: 8px; }

        .section {
            margin: 6px 0; /* Reduced margin */
            border-top: 1px dashed #000;
            padding-top: 4px; /* Reduced padding */
        }
        .section, .item, .totals p {
            page-break-inside: avoid;
            break-inside: avoid;
        }
       
        .highlight { font-weight: bold; }

        .footer {
            margin-top: 8px; /* Reduced margin */
            font-size: 8px; /* Reduced font size */
            line-height: 1.3; /* Adjusted line height */
        }
        .footer p { margin: 1px 0; }

        .actions {
            text-align: center;
            margin-top: 10px; /* Reduced margin */
        }
        .actions button {
            padding: 5px 10px; /* Reduced padding */
            margin: 4px; /* Reduced margin */
            font-size: 11px; /* Reduced font size */
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .print-btn { background: #007bff; color: #fff; }
        .close-btn { background: #dc3545; color: #fff; }

        /* إعدادات الطباعة */
        @page {
            size: 80mm auto; /* ارتفاع تلقائي حسب المحتوى */
            margin: 0;       /* بدون هوامش من المتصفح */
        }
        @media print {
            html, body {
                width: 80mm;   /* عرض الصفحة للطباعة */
                margin: 0;
                padding: 0;
                background: #fff !important;
            }
            .page {
                display: block;
                padding: 0;
            }
            .invoice {
                width: 80mm;    /* تمكين أقصى عرض للطباعة */
                max-width: 80mm;
                box-shadow: none;
                margin: 0;
                padding: 2mm;   /* Adjusted padding to prevent cutting */
            }
            .actions { display: none !important; }
            /* تحسين الوضوح في كروم */
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
<div class="page">
    <div class="invoice">
        <div class="header">
            <h1>السفير جلابية</h1>
            <p>امدرمان - شارع الوادي - السودان | 0915056159</p>
            <p>عطبرة - شارع كسلا - السودان | 0915675899</p>
            <p>102 شارع السودان - القاهرة - مصر | 01066725370</p>
            <p>فاتورة بيع نقدية / بيع جملة</p>
        </div>

        <div class="section">
            <p>رقم الفاتورة: <span class="highlight">{{ $sale->invoice_number }}</span></p>
            <p>تاريخ البيع: {{ $sale->sale_date }}</p>
            <p>الفرع: {{ $sale->branch->name ?? '-' }}</p>
            <p>العميل: {{ $sale->customer->name ?? 'عميل نقدي' }}</p>
        </div>

        <div class="section">
            @foreach($sale->items as $item)
            <div class="item">
                <span class="name">
                    {{ $item->product->name }}
                    @if($item->variant)
                        - {{ $item->variant->name ?? '' }}
                        @if($item->variant->color) - {{ $item->variant->color }} @endif
                        @if($item->variant->size)  - {{ $item->variant->size }} @endif
                    @endif
                    • {{ $item->quantity }} × {{ number_format($item->unit_price, 2) }}
                </span>
                {{-- <span class="sum"><b>{{ number_format($item->sub_total, 2) }}</b></span> --}}
            </div>
            @endforeach
        </div>

        <div class="section totals">
            <p>المجموع: {{ number_format($sale->total_amount, 2) }} جنيه</p>
            <p>الخصم: {{ number_format($sale->discount_amount, 2) }} جنيه</p>
            <p class="highlight">الصافي: {{ number_format($sale->net_amount, 2) }} جنيه</p>
            <p>طريقة الدفع: {{ $sale->payment_method }}</p>
            <p>حالة الدفع: {{ $sale->payment_status }}</p>
        </div>

       <div class="footer">
            <p>شكرًا لاختياركم السفير جلابية 🌟</p>
            <p>الاسترجاع أو الاستبدال متاح خلال 24 ساعة من تاريخ الشراء فقط.</p>
            <p>يرجى الاحتفاظ بالفاتورة لتسهيل العملية.</p>
            <p>نحن ملتزمون بتقديم أفضل جودة وأرقى تجربة تسوق.</p>
            <p>زيارة أخرى ستنال إعجابكم ✨</p>
        </div>

        <div class="actions">
            <button class="print-btn" onclick="window.print()">🖨️ طباعة</button>
            <button class="close-btn" onclick="window.close()">❌ إغلاق</button>
        </div>
    </div>
</div>
</body>
</html>


