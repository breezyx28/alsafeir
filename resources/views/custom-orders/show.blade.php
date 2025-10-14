<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            تفاصيل الطلب رقم: {{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
          
        <div class="row">
            {{-- العمود الأيمن: بيانات الطلب والعميل --}}
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">بيانات الطلب</h5>
                        <span class="badge bg-{{ $order->status_color }}">{{ $order->status }}</span>
                    </div>
                    <div class="card-body">
                        <p><strong>رقم الطلب:</strong> {{ $order->order_number }}</p>
                        <p><strong>العميل:</strong> {{ $order->customer->name }}</p>
                        <p><strong>الهاتف:</strong> {{ $order->customer->phone_primary }}</p>
                        <p><strong>هاتف ثانوي:</strong> {{ $order->customer->phone_secondary }}</p>
                        <p><strong>الفرع:</strong> {{ $order->branch->name }}</p>
                        <p><strong>تاريخ الطلب:</strong> {{ $order->order_date }}</p>
                        <p><strong>تاريخ الاستلام المتوقع:</strong> {{ $order->expected_delivery_date }}</p>
                        <p><strong>ملاحظات  :</strong> {{ $order->notes }}</p>
                        @if($order->delivery_code)
                            <div class="alert alert-info p-2"><strong>رقم التسليم:</strong> {{ $order->delivery_code }}</div>
                        @endif
                        <p><strong>الموظف المسؤول:</strong> {{ $order->employee->name ?? '—' }}</p>
                    </div>
                </div>

                {{-- بطاقة التحكم في حالة الطلب (النسخة المحدثة) --}}
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">التحكم بالطلب</h5></div>
                    <div class="card-body d-flex justify-content-center flex-wrap gap-2">

                        {{-- 1. يظهر فقط إذا كان الطلب "جاري التنفيذ" --}}
                        @if($order->status === 'جاري التنفيذ')
                            <form action="{{ route('custom-orders.updateStatus', $order) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟ سيتم توليد رقم تسليم إذا لم يكن موجوداً.')">
                                @csrf
                                <input type="hidden" name="status" value="جاهز للتسليم">
                                <button type="submit" class="btn btn-warning">تحديث إلى "جاهز للتسليم"</button>
                                
                            </form>
                        @endif
                         @if(in_array($order->status, ['جاري التنفيذ', 'جاهز للتسليم']))
                        {{-- زر تسجيل دفعة (يظهر طالما هناك مبلغ متبقي) --}}
                            @if($order->payment && $order->payment->remaining_amount > 0)
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                    تسجيل دفعة جديدة
                                </button>
                            @endif

                           @endif
                        {{-- 2. يظهر فقط إذا كان الطلب "جاهز للتسليم" --}}
                        @if($order->status === 'جاهز للتسليم')
                            
                            

                            {{-- زر تم التسليم (يظهر فقط إذا كان المبلغ المتبقي صفر) --}}
                            @if($order->payment->remaining_amount <= 0.01)
                                <form action="{{ route('custom-orders.updateStatus', $order) }}" method="POST" onsubmit="return confirm('هل تؤكد أن العميل قد استلم الطلب؟')">
                                    @csrf
                                    <input type="hidden" name="status" value="تم التسليم">
                                    <button type="submit" class="btn btn-primary">
                                        تأكيد الاستلام النهائي
                                    </button>
                                </form>
                            @endif

                        @endif
                        
                        {{-- 3. يظهر إذا كان الطلب مكتمل --}}
                        @if($order->status === 'تم التسليم')
                            <p class="text-success mb-0">✓ هذا الطلب مكتمل ومُسلَّم.</p>
                        @endif

                        @if($order->status !== 'تم التسليم')
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $order->id }}">
                            حذف الطلب
                        </button>
                         @endif

                    </div>
                </div>

                <div class="modal fade" id="deleteModal-{{ $order->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">تأكيد الحذف</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                هل أنت متأكد من رغبتك في حذف الطلب رقم <strong>{{ $order->order_number }}</strong>؟  

                                <strong class="text-danger">سيتم إرجاع كميات القماش للمخزون. لا يمكن التراجع عن هذا الإجراء.</strong>
                            </div>
                            <div class="modal-footer">
                                <form action="{{ route('custom-orders.destroy', $order) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                    <button type="submit" class="btn btn-danger">نعم، قم بالحذف</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- بطاقة الطباعة --}}
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">الطباعة</h5></div>
                    <div class="card-body d-flex justify-content-center gap-2">
                        <a href="{{ route('custom-orders.print.measurements', $order) }}" target="_blank" class="btn btn-outline-secondary">
                            طباعة بطاقة المقاسات
                        </a>
                        <a href="{{ route('custom-orders.print.invoice', $order) }}" target="_blank" class="btn btn-outline-primary">
                            طباعة الفاتورة المالية
                        </a>
                        <a href="{{ route('custom-orders.print.receipt', $order) }}" target="_blank" class="btn btn-outline-dark">
                            طباعة إيصال الاستلام
                        </a>
                    </div>
                </div>


            </div>

            {{-- العمود الأيسر: الماليات والأقمشة والمقاسات --}}
            <div class="col-lg-8">
                {{-- بطاقة الماليات --}}
                
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">البيانات المالية</h5></div>
                    <div class="card-body">
                        
                        <div class="row text-center">
                            @if($order->payment)
                            <div class="col"><h6>سعر التفصيل</h6><strong>{{ number_format($order->payment->tailoring_price, 2) }}</strong></div>
                            <div class="col"><h6>إجمالي الأقمشة</h6><strong>{{ number_format($order->payment->fabrics_total, 2) }}</strong></div>
                            <div class="col"><h6>الخصم</h6><strong>{{ $order->payment->discount_percentage }}%</strong></div>
                            <div class="col text-success"><h6>الإجمالي النهائي</h6><strong>{{ number_format($order->payment->total_after_discount, 2) }}</strong></div>
                            <div class="col text-primary"><h6>المدفوع</h6><strong>{{ number_format($order->payment->total_paid, 2) }}</strong></div>
                            <div class="col text-danger"><h6>المتبقي</h6><strong>{{ number_format($order->payment->remaining_amount, 2) }}</strong></div>
                             @else
                                <p class="text-danger text-center">لم يتم تسجيل بيانات مالية بعد</p>
                            @endif
                        </div>
                       
                    </div>
                </div>
                
                {{-- بطاقة الأقمشة --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">بنود التفصيل</h5></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead><tr><th>النوع</th><th>الكمية</th><th>المصدر</th></tr></thead>
                                <tbody>
                                    @foreach($order->measurements as $m)
                                    <tr><td>{{ $m->detail_type }}</td><td>{{ $m->quantity }}</td><td>{{ $m->fabric_source }}</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">الأقمشة</h5></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead><tr><th>القماش</th><th>اللون</th><th>الكمية</th><th>الحالة</th><th>إجراء</th></tr></thead>
                                <tbody>
                                    @forelse($order->products as $product)
                                        <tr>
                                            <td>{{ $product->variant->product->name }}</td>
                                            <td>{{ $product->variant->color }}</td>
                                            <td>{{ $product->quantity }} متر</td>
                                            <td><span class="badge bg-{{ $product->status === 'جاهز' ? 'success' : 'secondary' }}">{{ $product->status }}</span></td>
                                            <td>
                                                @if($product->status === 'جاري التنفيذ')
                                                    <form action="{{ route('order-products.updateStatus', $product) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="جاهز">
                                                        <button type="submit" class="btn btn-outline-success btn-sm">تحديث إلى جاهز</button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">--</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center p-3">لا توجد أقمشة من المحل في هذا الطلب.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                {{-- بطاقة سجل الدفعات --}}
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">سجل الدفعات</h5></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>المبلغ</th>
                                        <th>طريقة الدفع</th>
                                        <th>رقم العملية</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @forelse($order->installments as $txn)
                                    <tr>
                                        <td>{{ $txn->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ number_format($txn->amount, 2) }}</td>
                                        <td>{{ $txn->payment_method }}</td>
                                        <td>{{ $txn->transaction_number ?: '--' }}</td>
                                    </tr>
                                @empty
                                        <td colspan="4" class="text-center p-3">لا توجد دفعات مسجلة بعد.</td>
                                    </tr>
                                @endforelse

                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                
                {{-- بطاقة المقاسات --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">المقاسات وبنود التفصيل</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>نوع التفصيل</th>
                                        <th>الكمية</th>
                                        <th>الطول</th>
                                        <th>الكتف</th>
                                        <th>طول الذراع</th>
                                        <th>عرض اليد</th>
                                        <th>الجوانب</th>
                                        <th>القبة</th>
                                        <th>نوع الكفة</th>
                                        <th>طول السروال</th>
                                        <th>نوع السروال</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->measurements as $m)
                                        <tr>
                                            <td>{{ $m->detail_type }}</td>
                                            <td>{{ $m->quantity }}</td>
                                            <td>{{ $m->length ?? '-' }}</td>
                                            <td>{{ $m->shoulder_width ?? '-' }}</td>
                                            <td>{{ $m->arm_length ?? '-' }}</td>
                                            <td>{{ $m->arm_width ?? '-' }}</td>
                                            <td>{{ $m->sides ?? '-' }}</td>
                                            <td>{{ $m->neck ?? '-' }}</td>
                                            <td>{{ $m->cuff_type ?? '-' }}</td>
                                            <td>{{ $m->pants_length ?? '-' }}</td>
                                            <td>{{ $m->pants_type ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center p-3">
                                                لم يتم إدخال أي مقاسات لهذا الطلب.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>

    <!-- Modal تسجيل الدفعة -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">تسجيل دفعة للطلب: {{ $order->order_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('custom-orders.recordPayment', $order) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p><strong>العميل:</strong> {{ $order->customer->name }}</p>
                        <p><strong>الإجمالي بعد الخصم:</strong> {{ number_format($order->payment->total_after_discount, 2) }} جنيه</p>
                        <p class="text-danger"><strong>المبلغ المتبقي:</strong> {{ number_format($order->payment->remaining_amount, 2) }} جنيه</p>
                        <hr>
                        <div class="mb-3">
                            <label for="amount" class="form-label">المبلغ المدفوع الآن</label>
                            <input type="number" name="amount" id="amount" class="form-control" value="{{ $order->payment->remaining_amount }}" max="{{ $order->payment->remaining_amount }}" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">طريقة الدفع</label>
                            <select name="payment_method" id="payment_method" class="form-select" required>
                                <option value="كاش">كاش</option>
                                <option value="بطاقة">بطاقة</option>
                                <option value="تحويل">تحويل</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="transaction_number" class="form-label">رقم العملية (اختياري)</label>
                            <input type="text" name="transaction_number" id="transaction_number" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">تأكيد السداد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(session('print_receipt'))
    <script>
        window.open("{{ route('custom-orders.print.receipt', $order) }}", "_blank");
    </script>
@endif

</x-app-layout>
