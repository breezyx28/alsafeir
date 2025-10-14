<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عروضنا الخاصة - [اسم المحل]</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Tajawal', sans-serif;
        background-color: #f1f3f5;
    }

    .brand-header {
        background-color: #fff;
        padding: 20px 0;
        border-bottom: 2px solid #dee2e6;
    }

    .brand-header h2 {
        color: #1d3557;
        font-weight: bold;
    }

    .branch-info {
        font-size: 0.9rem;
        color: #444;
    }

    .offer-card-img {
        height: 240px;
        object-fit: cover;
        border-bottom: 1px solid #ddd;
    }

    .card {
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        color: #2c3e50;
    }

    .card-text {
        color: #555;
        font-size: 0.95rem;
    }
</style>

</head>
<body>
    {{-- يمكنك نسخ نفس الـ Header من صفحة عرض الطلبات ولصقه هنا --}}
     <!-- ======================= Header الجديد ======================= -->
    <header class="brand-header shadow-sm">
        <div class="container">
            <div class="row align-items-center">
                <!-- قسم الشعار واسم المحل -->
                <div class="col-lg-4 col-md-12 text-center text-lg-start mb-3 mb-lg-0">
                    <a href="#">
                        {{-- ضع رابط الشعار هنا --}}
                        {{-- <img src="https://via.placeholder.com/150x60.png?text=Your+Logo" alt="شعار المحل" class="brand-logo"> --}}
                        {{-- أو يمكنك استخدام اسم نصي إذا لم يكن هناك شعار --}}
                        <h2 class="d-inline-block align-middle ms-2 mb-0">السفير جلابية</h2>
                    </a>
                </div>

                <!-- قسم الفروع -->
                <div class="col-lg-8 col-md-12">
                    <div class="row">
                        {{-- الفرع الأول --}}
                        <div class="col-md-6 branch-info text-center text-md-start mb-2 mb-md-0">
                            <i class="bi bi-geo-alt-fill me-1"></i>
                            <strong>الفرع الرئيسي:</strong> [عنوان الفرع الرئيسي]
                              

                            <i class="bi bi-telephone-fill me-1"></i>
                            <strong>هاتف:</strong> [رقم هاتف الفرع الرئيسي]
                        </div>
                        {{-- الفرع الثاني --}}
                        <div class="col-md-6 branch-info text-center text-md-start">
                            <i class="bi bi-geo-alt-fill me-1"></i>
                            <strong>فرع الرياض:</strong> [عنوان فرع الرياض]
                              

                            <i class="bi bi-telephone-fill me-1"></i>
                            <strong>هاتف:</strong> [رقم هاتف فرع الرياض]
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- ======================= نهاية Header ======================= -->

    <main class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold">عروضنا الحصرية</h1>
            <p class="lead text-muted">اكتشف آخر العروض والتخفيضات المصممة خصيصاً لك.</p>
        </div>

        <div class="row g-4">
            @forelse($offers as $offer )
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm border-0 overflow-hidden">
                        <div class="offer-card-img-wrapper">
                            <img src="{{ asset('storage/' . $offer->image_path) }}" alt="{{ $offer->title }}">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $offer->title }}</h5>
                            <p class="card-text">{{ $offer->description }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center p-5 bg-light rounded">
                        <p class="lead">لا توجد عروض متاحة في الوقت الحالي. ترقبوا جديدنا!</p>
                    </div>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
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