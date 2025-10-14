<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> طلباتك - السفير جلابية</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #1a5f7a;
            --primary-dark: #134a5e;
            --primary-light: #2d7a96;
            --secondary-color: #c9a86a;
            --accent-color: #d4af37;
            --text-primary: #1a2332;
            --text-secondary: #5a6c7d;
            --border-color: #e1e8ed;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
        }

        /* ======================= Header المحسّن ======================= */
        .brand-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafb 100%);
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            position: relative;
            z-index: 1000;
            transition: var(--transition);
        }

        .brand-logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .brand-logo-circle {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 18px rgba(26, 95, 122, 0.25);
            transition: var(--transition);
            position: relative;
        }

        .brand-logo-circle::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid var(--secondary-color);
            opacity: 0;
            transform: scale(1.1);
            transition: var(--transition);
        }

        .brand-logo-section:hover .brand-logo-circle::after {
            opacity: 1;
            transform: scale(1.2);
        }

        .brand-logo-circle i {
            font-size: 30px;
            color: white;
        }

        .brand-logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .brand-name {
            margin: 0;
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--primary-color);
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .brand-tagline {
            margin: 0;
            font-size: 0.85rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .branches-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .branch-card {
            background: linear-gradient(135deg, #f8fafb 0%, #ffffff 100%);
            padding: 12px 18px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .branch-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            transform: scaleY(0);
            transition: var(--transition);
        }

        .branch-card:hover {
            transform: translateX(-3px);
            box-shadow: 0 4px 15px rgba(26, 95, 122, 0.12);
            border-color: var(--primary-color);
        }

        .branch-card:hover::before {
            transform: scaleY(1);
        }

        .branch-info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .branch-info-item:last-child {
            margin-bottom: 0;
        }

        .branch-info-item i {
            color: var(--secondary-color);
            font-size: 0.95rem;
            min-width: 18px;
        }

        .branch-info-item strong {
            color: var(--text-primary);
            font-weight: 600;
        }

        .branch-name {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .branch-name i {
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        /* Responsive للهيدر */
        @media (max-width: 991px) {
            .brand-logo-section {
                justify-content: center;
                margin-bottom: 1.5rem;
            }

            .brand-name {
                font-size: 1.5rem;
                text-align: center;
            }

            .branches-container {
                gap: 15px;
            }
        }

        @media (max-width: 767px) {
            .brand-header {
                padding: 1.2rem 0;
            }

            .brand-logo-circle {
                width: 55px;
                height: 55px;
            }

            .brand-logo-circle i {
                font-size: 25px;
            }

            .brand-name {
                font-size: 1.3rem;
            }

            .branch-card {
                padding: 10px 15px;
            }

            .branch-name {
                font-size: 0.95rem;
            }

            .branch-info-item {
                font-size: 0.85rem;
            }
        }

        /* ======================= باقي الأنماط الأصلية ======================= */
        .status-tracker {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 2rem;
        }
        .status-tracker::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #e9ecef;
            transform: translateY(-50%);
            z-index: 1;
        }
        .status-step {
            position: relative;
            z-index: 2;
            text-align: center;
        }
        .status-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #adb5bd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .status-step.active .status-icon {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
        .status-step.completed .status-icon {
            background-color: #198754;
            color: white;
            border-color: #198754;
        }
    </style>
</head>
<body>

    <!-- ======================= Header المحسّن ======================= -->
    <header class="brand-header">
        <div class="container">
            <div class="row align-items-center">
                <!-- قسم الشعار واسم المحل -->
                <div class="col-lg-4 col-md-12 text-center text-lg-start mb-3 mb-lg-0">
                    <a href="#" class="text-decoration-none">
                        <div class="brand-logo-section">
                            <div class="brand-logo-circle">
                                {{-- ضع الشعار هنا --}}
                                <img src="{{ asset('images/logo.jpeg') }}" alt="شعار المحل">
                                {{-- أو استخدم أيقونة إذا لم يكن هناك شعار --}}
                                {{-- <i class="bi bi-shop"></i> --}}
                            </div>
                            <div>
                                <h2 class="brand-name">السفير جلابية</h2>
                                <p class="brand-tagline">الأناقة والجودة في كل قطعة</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- قسم الفروع -->
                <div class="col-lg-8 col-md-12">
                    <div class="branches-container">
                        {{-- فرع امدرمان --}}
                        <div class="branch-card">
                            <div class="branch-name">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>فرع امدرمان</span>
                            </div>
                            <div class="branch-info-item">
                                <i class="bi bi-pin-map"></i>
                                <span>تقاطع الابراج مقابل بنك الخرطوم</span>
                            </div>
                        </div>

                        {{-- فرع عطبرة --}}
                        <div class="branch-card">
                            <div class="branch-name">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>فرع عطبرة</span>
                            </div>
                            <div class="branch-info-item">
                                <i class="bi bi-pin-map"></i>
                                <span>شارع كسلا مقابل البنك الاهلي المصري</span>
                            </div>
                        </div>

                        {{-- فرع مصر --}}
                        <div class="branch-card">
                            <div class="branch-name">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>فرع مصر</span>
                            </div>
                            <div class="branch-info-item">
                                <i class="bi bi-pin-map"></i>
                                <span>102 شارع السودان مع جامعة الدول</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- ======================= نهاية Header ======================= -->


    <main class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold">أهلاً بك، {{ $customer->name }}</h1>
            <p class="lead text-muted">هذه هي قائمة طلباتك الحالية والسابقة.</p>
        </div>

        @forelse ($orders as $order )
            @php
                $isReceived = true;
                $isInProgress = in_array($order->status, ['جاري التنفيذ', 'جاهز للتسليم', 'تم التسليم']);
                $isReady = in_array($order->status, ['جاهز للتسليم', 'تم التسليم']);
                $isDelivered = $order->status === 'تم التسليم';
            @endphp

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="mb-0 me-3">طلب رقم: {{ $order->order_number }}</h5>
                    <span class="badge bg-dark">{{ $order->order_date }}</span>
                </div>
                <div class="card-body p-4">
                    {{-- شريط تتبع الحالة --}}
                    <div class="status-tracker">
                        <div class="status-step {{ $isReceived ? 'completed' : '' }}">
                            <div class="status-icon"><i class="bi bi-journal-check fs-4"></i></div>
                            <p>تم الاستلام</p>
                        </div>
                        <div class="status-step {{ $isInProgress ? ($isReady || $isDelivered ? 'completed' : 'active') : '' }}">
                            <div class="status-icon"><i class="bi bi-palette-fill fs-4"></i></div>
                            <p>جاري التنفيذ</p>
                        </div>
                        <div class="status-step {{ $isReady ? ($isDelivered ? 'completed' : 'active') : '' }}">
                            <div class="status-icon"><i class="bi bi-bag-check-fill fs-4"></i></div>
                            <p>جاهز للتسليم</p>
                        </div>
                        <div class="status-step {{ $isDelivered ? 'completed' : '' }}">
                            <div class="status-icon"><i class="bi bi-house-check-fill fs-4"></i></div>
                            <p>تم التسليم</p>
                        </div>
                    </div>

                    {{-- التفاصيل المالية --}}
                    <div class="row border-top pt-3 align-items-center">
                        <div class="col-md-8">
                            <h6 class="mb-3">ملخص مالي:</h6>
                            <ul class="list-unstyled">
                                <li><strong>الإجمالي بعد الخصم:</strong> {{ number_format($order->payment->total_after_discount, 2) }} جنيه</li>
                                <li><strong>المدفوع:</strong> {{ number_format($order->payment->total_paid, 2) }} جنيه</li>
                                @if($order->payment->remaining_amount > 0.01)
                                    <li class="text-danger fs-5"><strong>المتبقي: {{ number_format($order->payment->remaining_amount, 2) }} جنيه</strong></li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-md-4 text-md-end">
                            @if($order->payment->remaining_amount > 0.01)
                                <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#paymentInfoModal-{{ $order->id }}">
                                    <i class="bi bi-whatsapp"></i> كيفية السداد
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal لكيفية السداد -->
            <div class="modal fade" id="paymentInfoModal-{{ $order->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">طرق السداد المتاحة</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>عزيزنا العميل، يمكنك سداد المبلغ المتبقي وقدره <strong>{{ number_format($order->payment->remaining_amount, 2) }} جنيه</strong> عبر إحدى الطرق التالية:</p>
                            <h6>1. تطبيق بنكك:</h6>
                            <ul class="list-unstyled ps-3">
                                <li><strong>اسم الحساب:</strong> السفير جلابية</li>
                                <li><strong>رقم الحساب:</strong> 1722024 </li>
                                <li><strong>البنك:</strong> بنك الخرطوم </li>
                            </ul>
                            <h6>2. تطبيق فوري:</h6>
                            <ul class="list-unstyled ps-3">
                                <li><strong>اسم الحساب:</strong> فوري </li>
                                <li><strong>رقم الحساب:</strong> قريبا ... </li>
                                <li><strong>البنك:</strong> بنك فيصل </li>
                            </ul>
                            <h6>3. تطبيق اوكاش:</h6>
                            <ul class="list-unstyled ps-3">
                                <li><strong>اسم الحساب:</strong> اوكاش</li>
                                <li><strong>رقم الحساب:</strong> قريبا ... </li>
                                <li><strong>البنك:</strong> بنك امدرمان الوطني </li>
                            </ul>
                            <h6>4. الدفع عند الاستلام.</h6>
                            <hr>
                            <p class="fw-bold">ملاحظة: بعد التحويل، يرجى إرسال إشعار الدفع عبر واتساب.</p>
                            <a href="https://api.whatsapp.com/send?phone=+249915056159&text={{ rawurlencode('مرحباً، أود تأكيد سداد دفعة للطلب رقم ' . $order->order_number) }}" 
                            class="btn btn-success w-100" target="_blank">
                            <i class="bi bi-whatsapp"></i> إرسال إشعار التحويل
                            </a>


                        </div>
                    </div>
                </div>
        @empty
            <div class="text-center card shadow-sm p-5">
                <p class="lead">لا توجد أي طلبات مسجلة بهذا الرقم.</p>
            </div>
        @endforelse
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

