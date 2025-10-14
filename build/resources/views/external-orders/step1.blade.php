<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بيانات العميل - السفير جلابية</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Cairo',sans-serif; background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); min-height:100vh; padding:20px; }
        .container { max-width:900px; margin:0 auto; background:white; border-radius:20px; box-shadow:0 20px 40px rgba(0,0,0,0.1); overflow:hidden; }
        .header { background: linear-gradient(135deg,#2c3e50 0%,#4a6741 100%); color:white; padding:30px; text-align:center; }
        .header h1 { font-size:2.5rem; margin-bottom:10px; font-weight:700; }
        .header p { font-size:1.1rem; opacity:0.9; }
        .form-content { padding:40px; }
        .step-indicator { display:flex; justify-content:center; align-items:center; margin-bottom:30px; }
        .step { display:flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:50%; background:#667eea; color:white; font-weight:600; margin:0 20px; position:relative; }
        .step.active { background:#4a6741; box-shadow:0 0 20px rgba(74,103,65,0.5); }
        .step.inactive { background:#e0e0e0; color:#666; }
        .step::after { content:''; position:absolute; right:-25px; top:50%; transform:translateY(-50%); width:30px; height:2px; background:#e0e0e0; }
        .step:last-child::after { display:none; }
        .section-title { color:#0a84ff; font-size:1.8rem; margin-bottom:25px; font-weight:600; position:relative; }
        .section-title::after { content:''; position:absolute; bottom:-8px; right:0; width:60px; height:3px; background:#4a6741; border-radius:2px; }
        .form-group { margin-bottom:25px; }
        .form-label { display:block; margin-bottom:8px; color:#2c3e50; font-weight:500; font-size:1rem; }
        .form-control, .form-select { width:100%; padding:15px 20px; border:2px solid #e0e0e0; border-radius:10px; font-size:1rem; background:#f8f9fa; transition:all 0.3s ease; }
        .form-control:focus, .form-select:focus { outline:none; border-color:#4a6741; background:white; box-shadow:0 0 0 3px rgba(74,103,65,0.1); }
        .row { display:flex; flex-wrap:wrap; margin:-10px; }
        .col-md-6 { flex:0 0 50%; max-width:50%; padding:10px; }
        .divider { margin:40px 0; text-align:center; position:relative; }
        .divider::before { content:''; position:absolute; top:50%; left:0; right:0; height:1px; background:#e0e0e0; }
        .divider-text { background:white; padding:0 20px; color:#666; font-weight:500; font-size:1.1rem; }
        .btn { padding:15px 30px; border:none; border-radius:10px; font-size:1.1rem; font-weight:600; cursor:pointer; transition:all 0.3s ease; text-decoration:none; display:inline-block; }
        .btn-primary { background: linear-gradient(135deg,#4a6741 0%,#2c3e50 100%); color:white; }
        .btn-primary:hover { transform:translateY(-2px); box-shadow:0 10px 20px rgba(0,0,0,0.2); }
        .new-customer-section { background: linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%); padding:30px; border-radius:15px; margin-top:20px; }
        .new-customer-title { color:#2c3e50; font-size:1.4rem; margin-bottom:20px; font-weight:600; }
        @media (max-width:768px){ .col-md-6 { flex:0 0 100%; max-width:100%; } }
        .form-animation { animation: slideInUp 0.6s ease-out; }
        @keyframes slideInUp { from{opacity:0; transform:translateY(30px);} to{opacity:1; transform:translateY(0);} }
    </style>
</head>
<body>
    <div class="container form-animation">
        <div class="header">
            <h1>السفير جلابية</h1>
            <p>نظام إدارة الطلبات والتفصيل</p>
        </div>
        
        <div class="form-content">
            <div class="step-indicator">
                <div class="step active">1</div>
                <div class="step inactive">2</div>
            </div>

            <h2 class="section-title">بيانات العميل</h2>

             <form id="customerForm" action="/order/wizard/step1" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label class="form-label">الاسم الكامل *</label>
                    <input type="text" name="new_customer[name]" class="form-control" placeholder="أدخل الاسم الكامل" required>
                </div>

                <div class="form-group">
                    <label class="form-label">الهاتف الأساسي *</label>
                    <input type="tel" name="new_customer[phone_primary]" class="form-control" placeholder="01xxxxxxxxx" required>
                </div>

                <div class="form-group">
                    <label class="form-label">الهاتف الثانوي</label>
                    <input type="tel" name="new_customer[phone_secondary]" class="form-control" placeholder="01xxxxxxxxx (اختياري)">
                </div>

                <div class="form-group">
                    <label class="form-label">مستوى العميل *</label>
                    <select name="new_customer[customer_level]" class="form-select" required>
                        <option value="">اختر المستوى</option>
                        <option value="عادي">عادي</option>
                        <option value="مميز">مميز</option>
                    </select>
                </div>

                <div style="margin-top: 40px; text-align: center;">
                    <button type="submit" class="btn btn-primary">التالي - تفاصيل الطلب ←</button>
                </div>
            </form>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const customerSelect = document.getElementById('customer_id');
            const newCustomerInputs = document.querySelectorAll('.new-customer-section input, .new-customer-section select');

            customerSelect.addEventListener('change', function() {
                if (this.value) {
                    newCustomerInputs.forEach(input => {
                        input.disabled = true;
                        input.style.opacity = '0.5';
                    });
                } else {
                    newCustomerInputs.forEach(input => {
                        input.disabled = false;
                        input.style.opacity = '1';
                    });
                }
            });
        });
    </script>
</body>
</html>
