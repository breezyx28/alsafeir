<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            إنشاء طلب جديد (الخطوة 1 من 4: العميل)
        </h2>
    </x-slot>

    <div class="container py-4" style="max-width: 800px;">
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('order.wizard.processStep1') }}">
            @csrf
            <div class="card">
                <div class="card-body">
                     <h2 class="h4 font-weight-bold">
                     إنشاء طلب جديد (الخطوة 1 من 4: العميل)
                     </h2>

                    <h5 class="card-title mb-4">أولاً: اختر العميل</h5>

                    <!-- قسم العميل الحالي -->
                    <div class="mb-4">
                        <h6>عميل حالي؟ ابحث عنه هنا:</h6>
                        {{-- سنستخدم Select2 لجعل البحث سهلاً --}}
                        <select id="customer_search" name="customer_id" class="form-control">
                            <option value="">-- ابحث بالاسم أو رقم الهاتف --</option>
                        </select>
                    </div>

                    <hr>

                    <!-- قسم العميل الجديد -->
                    <div class="mt-4">
                        <h6>أو قم بإضافة عميل جديد:</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">اسم العميل</label>
                                <input type="text" name="new_customer[name]" class="form-control" value="{{ old('new_customer.name') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الهاتف الأساسي</label>
                                <input type="text" name="new_customer[phone_primary]" class="form-control" value="{{ old('new_customer.phone_primary') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الهاتف الثانوي</label>
                                <input type="text" name="new_customer[phone_secondary]" class="form-control" value="{{ old('new_customer.phone_secondary') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">درجة العميل</label>
                                <select name="new_customer[customer_level]" class="form-select">
                                    <option value="عابر">عابر</option>
                                    <option value="مميز">مميز</option>
                                    <option value="VIP">VIP</option>
                                    <option value="عضو">عضو</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">التالي: تفاصيل الطلب ←</button>
                </div>
            </div>
        </form>
    </div>

    
       {{-- resources/views/custom-orders/wizard/step1.blade.php --}}
     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
     <style>.select2-container .select2-selection--single { height: 38px; }</style>

    {{-- تأكد من وجود jQuery قبل Select2 --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document ).ready(function() {
            $('#customer_search').select2({
                placeholder: '-- ابحث بالاسم أو رقم الهاتف --',
                ajax: {
                    url: '{{ route("customers.search") }}', // <-- الراوت الصحيح للبحث
                    dataType: 'json',
                    delay: 250, // تأخير بسيط قبل إرسال الطلب
                    data: function (params) {
                        return {
                            term: params.term // مصطلح البحث الذي يكتبه المستخدم
                        };
                    },
                    processResults: function (data) {
                        // تحويل البيانات القادمة من لارافيل إلى الصيغة التي يفهمها Select2
                        return {
                            results: $.map(data, function(customer) {
                                return {
                                    id: customer.id,
                                    text: `${customer.name} - ${customer.phone_primary}`
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            // عند اختيار عميل، قم بتعطيل حقول العميل الجديد
            $('#customer_search').on('select2:select', function (e) {
                $('input[name^="new_customer"]').prop('disabled', true);
                $('select[name^="new_customer"]').prop('disabled', true);
            });

            // عند مسح الاختيار، قم بتفعيل حقول العميل الجديد
            $('#customer_search').on('select2:unselect', function (e) {
                $('input[name^="new_customer"]').prop('disabled', false);
                $('select[name^="new_customer"]').prop('disabled', false);
            });
        });
    </script>


   
</x-app-layout>
