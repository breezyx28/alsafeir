<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متابعة طلبك - السفير جلابية</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary-color: #1a5f7a;
            --primary-dark: #134a5e;
            --primary-light: #2d7a96;
            --secondary-color: #c9a86a;
            --accent-color: #d4af37;
            --bg-gradient-start: #f0f4f8;
            --bg-gradient-end: #e8eef3;
            --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            --card-shadow-hover: 0 15px 50px rgba(0, 0, 0, 0.12);
            --text-primary: #1a2332;
            --text-secondary: #5a6c7d;
            --border-radius: 20px;
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* خلفية متحركة */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(26, 95, 122, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: moveBackground 20s linear infinite;
            z-index: 0;
        }

        @keyframes moveBackground {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .container-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1000px;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* بطاقة المحل المحسّنة */
        .brand-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafb 100%);
            padding: 35px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
            border: 1px solid rgba(26, 95, 122, 0.08);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .brand-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .brand-card:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-3px);
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 20px;
            flex: 1;
        }

        .brand-logo-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(26, 95, 122, 0.25);
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

        .brand-card:hover .brand-logo-circle::after {
            opacity: 1;
            transform: scale(1.2);
        }

        .brand-logo-circle i {
            font-size: 40px;
            color: white;
        }

        .brand-logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .brand-info {
            flex: 1;
        }

        .brand-info h3 {
            margin: 0 0 15px 0;
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--primary-color);
            letter-spacing: -0.5px;
        }

        .branch-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 10px;
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.6;
            transition: var(--transition);
        }

        .branch-item:hover {
            color: var(--primary-color);
            transform: translateX(-3px);
        }

        .branch-item i {
            color: var(--secondary-color);
            margin-top: 3px;
            font-size: 0.9rem;
            min-width: 16px;
        }

        /* بطاقة الطلب المحسّنة */
        .order-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 45px 40px;
            transition: var(--transition);
            border: 1px solid rgba(26, 95, 122, 0.06);
            position: relative;
            overflow: hidden;
        }

        .order-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--primary-color) 100%);
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .order-card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .order-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .order-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(26, 95, 122, 0.2);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 10px 30px rgba(26, 95, 122, 0.2);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 15px 40px rgba(26, 95, 122, 0.3);
            }
        }

        .order-icon i {
            font-size: 35px;
            color: white;
        }

        .order-header h2 {
            font-weight: 800;
            font-size: 2rem;
            color: var(--text-primary);
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .order-header p {
            color: var(--text-secondary);
            font-size: 1.05rem;
            line-height: 1.6;
            font-weight: 400;
        }

        /* تحسين النموذج */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1.1rem;
            transition: var(--transition);
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 16px 50px 16px 20px;
            font-size: 1.1rem;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            transition: var(--transition);
            font-family: 'Tajawal', sans-serif;
            background-color: #f8fafb;
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: white;
            box-shadow: 0 0 0 4px rgba(26, 95, 122, 0.1);
        }

        .form-control:focus + .input-icon {
            color: var(--primary-color);
            transform: translateY(-50%) scale(1.1);
        }

        .form-control::placeholder {
            color: #a0aec0;
        }

        /* زر محسّن */
        .btn-submit {
            width: 100%;
            padding: 18px;
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 8px 20px rgba(26, 95, 122, 0.25);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(26, 95, 122, 0.35);
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        }

        .btn-submit:active {
            transform: translateY(0);
            box-shadow: 0 5px 15px rgba(26, 95, 122, 0.3);
        }

        .btn-submit i {
            margin-left: 8px;
            transition: var(--transition);
        }

        .btn-submit:hover i {
            transform: translateX(-3px);
        }

        /* تنبيهات محسّنة */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.4s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background-color: #fee;
            color: #c33;
            border-right: 4px solid #c33;
        }

        .alert i {
            font-size: 1.2rem;
        }

        /* تحسينات الاستجابة */
        @media (max-width: 768px) {
            .brand-card {
                flex-direction: column;
                text-align: center;
                padding: 30px 20px;
            }

            .brand-header {
                flex-direction: column;
                text-align: center;
            }

            .brand-card::before {
                width: 100%;
                height: 5px;
            }

            .branch-item {
                justify-content: center;
            }

            .branch-item:hover {
                transform: translateY(-2px);
            }

            .order-card {
                padding: 35px 25px;
            }

            .order-header h2 {
                font-size: 1.6rem;
            }

            .brand-info h3 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }

            .brand-card {
                padding: 25px 15px;
            }

            .order-card {
                padding: 30px 20px;
            }

            .brand-logo-circle {
                width: 70px;
                height: 70px;
            }

            .brand-logo-circle i {
                font-size: 32px;
            }

            .order-icon {
                width: 65px;
                height: 65px;
            }

            .order-icon i {
                font-size: 28px;
            }

            .order-header h2 {
                font-size: 1.4rem;
            }

            .order-header p {
                font-size: 0.95rem;
            }

            .form-control {
                padding: 14px 45px 14px 18px;
                font-size: 1rem;
            }

            .btn-submit {
                padding: 16px;
                font-size: 1.1rem;
            }
        }

        /* تأثيرات إضافية */
        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        /* تحسين التمرير */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>
</head>
<body>
    <div class="container-wrapper">
        <!-- بطاقة بيانات المحل -->
        <div class="brand-card">
            <div class="brand-header">
                <div class="brand-logo-circle floating">
                    <i class="fas fa-store"></i>
                    <img src="{{ asset('images/logo.jpeg') }}" alt="Logo">
                </div>
                <div class="brand-info">
                    <h3>السفير جلابية</h3>
                    <div class="branch-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>فرع امدرمان - تقاطع الابراج مقابل بنك الخرطوم</span>
                    </div>
                    <div class="branch-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>فرع عطبرة - شارع كسلا مقابل البنك الاهلي المصري</span>
                    </div>
                    <div class="branch-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>فرع مصر - 102 شارع السودان مع جامعة الدول</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- نموذج متابعة الطلب -->
        <div class="order-card">
            <div class="order-header">
                <div class="order-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h2>متابعة طلبك</h2>
                <p>أدخل رقم هاتفك المسجل لدينا لعرض حالة طلباتك بكل سهولة</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('portal.orders.show') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="phone" class="form-label">رقم الهاتف</label>
                    <div class="input-wrapper">
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            class="form-control" 
                            placeholder="مثال: 0912345678" 
                            required
                            pattern="[0-9]{10}"
                            title="الرجاء إدخال رقم هاتف صحيح مكون من 10 أرقام"
                        >
                        <i class="fas fa-phone input-icon"></i>
                    </div>
                </div>
                <button type="submit" class="btn-submit">
                    <span>عرض الطلبات</span>
                    <i class="fas fa-arrow-left"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // تحسين تجربة المستخدم
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone');
            const form = document.querySelector('form');
            
            // تنسيق رقم الهاتف تلقائياً
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                e.target.value = value;
            });
            
            // تأثير عند الإرسال
            form.addEventListener('submit', function(e) {
                const button = form.querySelector('.btn-submit');
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري البحث...';
                button.disabled = true;
            });
            
            // تأثير التركيز على الحقل
            phoneInput.addEventListener('focus', function() {
                this.parentElement.parentElement.style.transform = 'scale(1.02)';
            });
            
            phoneInput.addEventListener('blur', function() {
                this.parentElement.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>

