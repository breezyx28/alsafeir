<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الطلب - السفير جلابية</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #4a6741 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .form-content {
            padding: 40px;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
        }

        .step {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            font-weight: 600;
            margin: 0 20px;
            position: relative;
        }

        .step.active {
            background: #4a6741;
            box-shadow: 0 0 20px rgba(74, 103, 65, 0.5);
        }

        .step.completed {
            background: #28a745;
        }

        .step.inactive {
            background: #e0e0e0;
            color: #666;
        }

        .step::after {
            content: '';
            position: absolute;
            right: -25px;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 2px;
            background: #e0e0e0;
        }

        .step:last-child::after {
            display: none;
        }

        .customer-info {
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            border-right: 5px solid #4a6741;
        }

        .customer-name {
            color: #2c3e50;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .customer-phone {
            color: #666;
            font-size: 1rem;
        }

        .section-title {
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 25px;
            font-weight: 600;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            right: 0;
            width: 60px;
            height: 3px;
            background: #4a6741;
            border-radius: 2px;
        }

        .item-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .item-card:hover {
            border-color: #4a6741;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .item-title {
            color: #2c3e50;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .remove-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: -10px;
        }

        .form-col {
            padding: 10px;
            flex: 1;
            min-width: 200px;
        }

        .form-col-2 {
            flex: 0 0 calc(50% - 20px);
        }

        .form-col-3 {
            flex: 0 0 calc(33.333% - 20px);
        }

        .form-col-4 {
            flex: 0 0 calc(25% - 20px);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 1rem;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #4a6741;
            background: white;
            box-shadow: 0 0 0 3px rgba(74, 103, 65, 0.1);
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4a6741 0%, #2c3e50 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            font-size: 1.1rem;
            padding: 15px 30px;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        }

        .add-item-section {
            text-align: center;
            margin: 30px 0;
        }

        .submit-section {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e0e0e0;
        }

        .back-btn {
            background: #6c757d;
            color: white;
            margin-left: 15px;
        }

        .back-btn:hover {
            background: #5a6268;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .header {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .form-content {
                padding: 20px;
            }
            
            .form-col,
            .form-col-2,
            .form-col-3,
            .form-col-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            
            .step {
                margin: 0 10px;
                width: 35px;
                height: 35px;
            }
            
            .item-header {
                flex-direction: column;
                gap: 10px;
            }
        }

        .form-animation {
            animation: slideInUp 0.6s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .item-enter {
            animation: itemSlideIn 0.4s ease-out;
        }

        @keyframes itemSlideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
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
                <div class="step completed">1</div>
                <div class="step active">2</div>
            </div>

            <div class="customer-info">
                <div class="customer-name" id="customerName">اسم العميل</div>
                <div class="customer-phone" id="customerPhone">رقم الهاتف</div>
            </div>

            <h2 class="section-title">تفاصيل الطلب</h2>

            <form id="orderForm" onsubmit="submitOrder(event)">
                <div id="items-container">
                    <!-- سيتم إضافة العناصر هنا بواسطة JavaScript -->
                </div>

                <div class="add-item-section">
                    <button type="button" class="btn btn-secondary" onclick="addItem()">
                        + إضافة نوع تفصيل آخر
                    </button>
                </div>

                <div class="submit-section">
                    <button type="button" class="btn back-btn" onclick="goBack()">
                        ← الرجوع
                    </button>
                    <button type="submit" class="btn btn-success">
                        ✓ حفظ الطلب وإنهاء
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let itemIndex = 0;

        // تحميل بيانات العميل من الصفحة السابقة
        function loadCustomerData() {
            const customerData = JSON.parse(localStorage.getItem('customerData') || '{}');
            
            if (customerData.existingCustomerId) {
                // عميل موجود
                const customers = {
                    '1': { name: 'أحمد محمد', phone: '01234567890' },
                    '2': { name: 'محمد علي', phone: '01098765432' },
                    '3': { name: 'علي أحمد', phone: '01156789012' },
                    '4': { name: 'حسام محمود', phone: '01267890123' }
                };
                
                const customer = customers[customerData.existingCustomerId];
                if (customer) {
                    document.getElementById('customerName').textContent = customer.name;
                    document.getElementById('customerPhone').textContent = customer.phone;
                }
            } else if (customerData.newCustomer && customerData.newCustomer.name) {
                // عميل جديد
                document.getElementById('customerName').textContent = customerData.newCustomer.name;
                document.getElementById('customerPhone').textContent = customerData.newCustomer.phonePrimary;
            } else {
                // لا توجد بيانات
                document.getElementById('customerName').textContent = 'عميل جديد';
                document.getElementById('customerPhone').textContent = 'لم يتم تحديد رقم الهاتف';
            }
        }

        function createItemHTML(index) {
            return `
                <div class="item-card item-enter">
                    <div class="item-header">
                        <div class="item-title">قطعة تفصيل ${index + 1}</div>
                        <button type="button" class="remove-btn" onclick="removeItem(this)" ${index === 0 ? 'style="display:none"' : ''}>
                            🗑️ حذف
                        </button>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col form-col-3">
                            <div class="form-group">
                                <label class="form-label">نوع التفصيل *</label>
                                <select name="items[${index}][detail_type]" class="form-select" required>
                                    <option value="">اختر النوع</option>
                                    <option value="جلابية">جلابية</option>
                                    <option value="سروال">سروال</option>
                                    <option value="على الله">على الله</option>
                                    <option value="عراقي">عراقي</option>
                                    <option value="قميص">قميص</option>
                                    <option value="فستان">فستان</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-col form-col-4">
                            <div class="form-group">
                                <label class="form-label">الكمية *</label>
                                <input type="number" name="items[${index}][quantity]" class="form-control" value="1" min="1" required>
                            </div>
                        </div>
                        
                        <div class="form-col form-col-4">
                            <div class="form-group">
                                <label class="form-label">الطول (سم)</label>
                                <input type="number" name="items[${index}][height]" class="form-control" placeholder="170">
                            </div>
                        </div>
                        
                        <div class="form-col form-col-4">
                            <div class="form-group">
                                <label class="form-label">الوزن (كجم)</label>
                                <input type="number" name="items[${index}][weight]" class="form-control" placeholder="75">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col form-col-2">
                            <div class="form-group">
                                <label class="form-label">الألوان المقترحة</label>
                                <input type="text" name="items[${index}][suggested_colors]" class="form-control" placeholder="أبيض، بيج، أزرق فاتح">
                            </div>
                        </div>
                        
                        <div class="form-col form-col-2">
                            <div class="form-group">
                                <label class="form-label">الميزانية (جنيه)</label>
                                <input type="number" name="items[${index}][budget]" class="form-control" placeholder="500">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">ملاحظات إضافية</label>
                                <input type="text" name="items[${index}][notes]" class="form-control" placeholder="أي تفاصيل أو ملاحظات خاصة">
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function addItem() {
            const container = document.getElementById('items-container');
            container.insertAdjacentHTML('beforeend', createItemHTML(itemIndex));
            itemIndex++;
            updateItemTitles();
        }

        function removeItem(button) {
            const itemCard = button.closest('.item-card');
            itemCard.style.animation = 'itemSlideOut 0.3s ease-out';
            setTimeout(() => {
                itemCard.remove();
                updateItemTitles();
            }, 300);
        }

        function updateItemTitles() {
            const items = document.querySelectorAll('.item-card');
            items.forEach((item, index) => {
                const title = item.querySelector('.item-title');
                title.textContent = `قطعة تفصيل ${index + 1}`;
                
                const removeBtn = item.querySelector('.remove-btn');
                if (index === 0 && items.length === 1) {
                    removeBtn.style.display = 'none';
                } else {
                    removeBtn.style.display = 'block';
                }
            });
        }

        function goBack() {
            window.location.href = 'step1-customer-data.html';
        }

        function submitOrder(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const orderData = {
                customer: JSON.parse(localStorage.getItem('customerData') || '{}'),
                items: [],
                orderDate: new Date().toISOString()
            };
            
            // جمع بيانات العناصر
            const items = document.querySelectorAll('.item-card');
            items.forEach((item, index) => {
                const itemData = {
                    detailType: formData.get(`items[${index}][detail_type]`),
                    quantity: formData.get(`items[${index}][quantity]`),
                    height: formData.get(`items[${index}][height]`),
                    weight: formData.get(`items[${index}][weight]`),
                    suggestedColors: formData.get(`items[${index}][suggested_colors]`),
                    budget: formData.get(`items[${index}][budget]`),
                    notes: formData.get(`items[${index}][notes]`)
                };
                
                if (itemData.detailType) {
                    orderData.items.push(itemData);
                }
            });
            
            if (orderData.items.length === 0) {
                alert('يرجى إضافة نوع تفصيل واحد على الأقل');
                return;
            }
            
            // حفظ الطلب
            localStorage.setItem('orderData', JSON.stringify(orderData));
            
            // عرض رسالة النجاح
            alert('تم حفظ الطلب بنجاح! شكراً لاختياركم السفير جلابية');
            
            // إعادة تعيين النموذج
            localStorage.removeItem('customerData');
            localStorage.removeItem('orderData');
            window.location.href = 'step1-customer-data.html';
        }

        // إضافة تأثير الخروج للعناصر
        const style = document.createElement('style');
        style.textContent = `
            @keyframes itemSlideOut {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }
                to {
                    opacity: 0;
                    transform: translateX(-20px);
                }
            }
        `;
        document.head.appendChild(style);

        // تشغيل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            loadCustomerData();
            addItem(); // إضافة العنصر الأول
        });
    </script>
</body>
</html>