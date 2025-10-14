<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            إنشاء طلب جديد (الخطوة 3 من 4: المقاسات والأقمشة)
        </h2>
    </x-slot>

    <div class="container py-4" x-data="wizardStep3" x-init="init()">

        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h6>1. تفاصيل العميل والطلب</h6>
                <table class="table table-sm table-bordered">
                    <tr>
                        <th>العميل</th><td>{{ $order->customer->name }}</td>
                        <th>الهاتف</th><td>{{ $order->customer->phone_primary }}</td>
                    </tr>
                    <tr>
                        <th>الفرع</th><td>{{ $order->branch->name }}</td>
                        <th>تاريخ الاستلام</th><td>{{ $order->expected_delivery_date }}</td>
                    </tr>
                </table>

        <form method="POST" action="{{ route('order.wizard.processStep3', $order) }}">
            @csrf

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">اختر الموظف المسؤول عن الطلب</h5>
                </div>
                <div class="card-body">
                    <select class="form-select" name="employee_id" required>
                        <option value="">-- اختر الموظف --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" 
                                {{ old('employee_id', $order->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            {{-- قسم المقاسات --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">ثالثاً: أضف بنود التفصيل والمقاسات</h5>
                    <button type="button" @click="addMeasurement" class="btn btn-primary btn-sm">+ إضافة بند تفصيل</button>
                </div>
                <div class="card-body">
                    <template x-for="(m, index) in measurements" :key="index">
                        <div class="border p-3 mb-3 rounded bg-light position-relative">
                            <button type="button" @click="removeMeasurement(index)" class="btn-close position-absolute top-0 end-0 m-2" aria-label="Close"></button>
                            <div class="row">
                                <div class="col-md-4 mb-3"><label class="form-label">نوع التفصيل</label>
                                    <select class="form-select" 
                                            :name="`measurements[${index}][detail_type]`" 
                                            x-model="m.detail_type"
                                            @change="fetchMeasurement(index)">
                                        <option value="">-- اختر --</option>
                                        <option value="جلابية">جلابية</option>
                                        <option value="سروال">سروال</option>
                                        <option value="على الله">على الله</option>
                                        <option value="عراقي">عراقي</option>
                                    </select>
                                    </div>
                                <div class="col-md-2 mb-3"><label class="form-label">الكمية</label><input type="number" class="form-control" :name="`measurements[${index}][quantity]`" x-model.number="m.quantity" min="1"></div>
                                <div class="col-md-3 mb-3"><label>مصدر القماش</label><select class="form-select" :name="`measurements[${index}][fabric_source]`" x-model="m.fabric_source"><option value="داخلي">داخلي (من المحل)</option><option value="خارجي">خارجي</option></select></div>
                            </div>
                            <div class="row" x-show="m.detail_type">
                                <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(m.detail_type)"><label>الطول</label><input type="text" class="form-control" :name="`measurements[${index}][length]`" x-model="m.length"></div>
                                <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(m.detail_type)"><label>الكتف</label><input type="text" class="form-control" :name="`measurements[${index}][shoulder_width]`" x-model="m.shoulder_width"></div>
                                <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(m.detail_type)"><label>طول الذراع</label><input type="text" class="form-control" :name="`measurements[${index}][arm_length]`" x-model="m.arm_length"></div>
                                <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(m.detail_type)"><label>عرض اليد</label><input type="text" class="form-control" :name="`measurements[${index}][arm_width]`" x-model="m.arm_width" placeholder="e.g. 23,15"></div>
                                <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(m.detail_type)"><label>الجوانب</label><input type="text" class="form-control" :name="`measurements[${index}][sides]`" x-model="m.sides" placeholder="e.g. 9,18"></div>
                                <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(m.detail_type)"><label>القبة</label><input type="text" class="form-control" :name="`measurements[${index}][neck]`" x-model="m.neck"></div>
                                <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(m.detail_type)">
                                    <label>نوع الكفة</label>
                                    <select class="form-select" :name="`measurements[${index}][cuff_type]`" x-model="m.cuff_type">
                                        <option value="عادي">عادي</option>
                                        <option value="برمة">برمة</option>
                                        <option value="7 سنتمتر">7 سنتمتر</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(m.detail_type)"><label>تفصيل القماش</label><select class="form-select" :name="`measurements[${index}][fabric_detail]`" x-model="m.fabric_detail"><option value="داخلي">داخلي</option><option value="خارجي">خارجي</option><option value="مقفول">مقفول</option></select></div>
                                {{-- <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(m.detail_type)"><label>تفصيل القماش</label><select class="form-select" :name="`measurements[${index}][fabric_detail]`" x-model="m.fabric_detail"><option value="داخلي">داخلي</option><option value="خارجي">خارجي</option><option value="مقفول">مقفول</option></select></div> --}}
                                <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(m.detail_type)"><label>نوع الزاير </label><select class="form-select" :name="`measurements[${index}][buttons]`" x-model="m.buttons"><option value="بدون">بدون</option><option value="زرار عادي">زرار عادي</option><option value="زرار كبس">زرار كبس</option></select></div>
                                <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(m.detail_type)"><label>القيطان </label><select class="form-select" :name="`measurements[${index}][qitan]`" x-model="m.qitan"><option value="بدون">بدون</option><option value="ابيض">ابيض</option><option value="اسود">اسود</option><option value="بني">بني</option><option value="كحلي">كحلي</option></select></div>
                                <div class="col-md-2 mb-2" x-show="['سروال', 'على الله'].includes(m.detail_type)"><label>طول السروال</label><input type="text" class="form-control" :name="`measurements[${index}][pants_length]`" x-model="m.pants_length" placeholder="e.g. 100,20"></div>
                                <div class="col-md-2 mb-2" x-show="['سروال', 'على الله'].includes(m.detail_type)"><label>نوع السروال</label><select class="form-select" :name="`measurements[${index}][pants_type]`" x-model="m.pants_type"><option value="لستك">لستك</option><option value="تكة">تكة</option></select></div>
                                <div class="col-md-2 mb-2" x-show="['سروال', 'على الله'].includes(m.detail_type)"><label>مقاس السروال</label><select class="form-select" :name="`measurements[${index}][pants_size]`" x-model="m.pants_size"><option value="بنطلون">بنطلون</option><option value="بلدي">بلدي</option></select></div>
                            </div>
                        </div>
                    </template>
                    <p x-show="measurements.length === 0" class="text-muted text-center p-4">لم تتم إضافة أي بنود تفصيل بعد.</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">رابعاً: اختر الأقمشة (إن وجدت)</h5>
                    <button type="button" @click="addProduct" class="btn btn-success btn-sm">+ إضافة قماش</button>
                </div>
                <div class="card-body">
                    <template x-for="(p, index) in products" :key="index">
                        <div class="row align-items-end mb-3 border-bottom pb-3">
                            <div class="col-md-3"><label>القماش</label><select class="form-select" @change="p.variants = getVariantsByProductId($event.target.value); p.variant_id = ''"><option value="">-- اختر القماش --</option><template x-for="fabric in allFabrics" :key="fabric.id"><option :value="fabric.id" x-text="fabric.name"></option></template></select></div>
                            <div class="col-md-3"><label>اللون/المتغير</label><select class="form-select" :name="`products[${index}][variant_id]`" x-model="p.variant_id"><option value="">-- اختر اللون --</option><template x-for="variant in p.variants" :key="variant.id"><option :value="variant.id" x-text="`${variant.color} (السعر: ${variant.price_override || (variant.product ? variant.product.price : 0)})`"></option></template></select></div>
                            <div class="col-md-2"><label>الكمية (متر)</label><input type="number" class="form-control" :name="`products[${index}][quantity]`" x-model.number="p.quantity" step="0.1" min="0.1"></div>
                            <div class="col-md-2"><label>الحالة</label><select class="form-select" :name="`products[${index}][status]`" x-model="p.status"><option value="جاري التنفيذ">جاري التنفيذ</option><option value="جاهز">جاهز</option></select></div>
                            <div class="col-md-2"><label>ملاحظات</label><input type="text" class="form-control" :name="`products[${index}][notes]`" x-model="p.notes"></div>
                            <div class="col-md-1 d-flex align-items-end"><button type="button" @click="removeProduct(index)" class="btn btn-danger btn-sm">✖</button></div>
                        </div>
                    </template>
                    <p x-show="products.length === 0" class="text-muted text-center p-4">لم تتم إضافة أي أقمشة بعد.</p>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('order.wizard.step2', $order) }}" class="btn btn-secondary">→ العودة للخطوة 2</a>
                <button type="submit" class="btn btn-primary">التالي: الماليات والمراجعة ←</button>
            </div>
        </form>
    </div>
  
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('wizardStep3', () => ({
                    allFabrics: {!! $fabrics_json !!} || [],
                    customerId: '{{ $customer_id }}',
                    measurements: [],
                    products: [],
                    init() {
                        const oldProducts = {!! $old_data_json !!}.products || [];
                        this.products = oldProducts.map(p => {
                            const fabric = this.allFabrics.find(f => f.variants.some(v => v.id === p.variant_id));
                            return { ...p, variants: fabric ? fabric.variants : [] };
                        });
                    },
                    addMeasurement() {
                        this.measurements.push({ 
                            detail_type: '',
                            fabric_source: 'داخلي',
                            quantity: 1,
                            length: '',
                            shoulder_width: '',
                            arm_length: '',
                            arm_width: '',
                            sides: '',
                            neck: '',
                            cuff_type:'عادي',
                            fabric_detail: 'داخلي',
                            pants_length: '',
                            pants_size: '',
                            pants_type: ''
                        });
                    },
                    removeMeasurement(index) {
                        this.measurements.splice(index, 1);
                    },
                    addProduct() {
                        this.products.push({
                            variant_id: '',
                            quantity: 1,
                            status: 'جاري التنفيذ',
                            notes: '',
                            variants: []
                        });
                    },
                    removeProduct(index) {
                        this.products.splice(index, 1);
                    },
                    getVariantsByProductId(productId) {
                        if (!productId) return [];
                        const fabric = this.allFabrics.find(f => f.id === productId);
                        return fabric ? fabric.variants : [];
                    },
                    async fetchMeasurement(index) {
                        const m = this.measurements[index];
                        if (!m.detail_type || !this.customerId) return;

                        try {
                            const url = `/customers/${this.customerId}/measurements/${m.detail_type}`;
                            const response = await fetch(url);
                            if (response.status === 204) return;

                            const data = await response.json();
                            if (data) {
                                this.measurements[index] = { 
                                    ...m, 
                                    ...data,
                                    detail_type: m.detail_type,
                                    quantity: m.quantity,
                                    fabric_source: m.fabric_source
                                };
                            }
                        } catch (error) {
                            console.error('حدث خطأ أثناء جلب المقاسات:', error);
                        }
                    }
                }));
            });
        </script>


 
</x-app-layout>
