{{-- resources/views/customers/partials/measurements_form.blade.php --}}

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">نوع التفصيل</label>
        <select class="form-select" name="detail_type" x-model="measurement.detail_type" required>
            <option value="">-- اختر --</option>
            <option value="جلابية">جلابية</option>
            <option value="سروال">سروال</option>
            <option value="على الله">على الله</option>
            <option value="عراقي">عراقي</option>
        </select>
    </div>
</div>
<hr>
<div class="row" x-show="measurement.detail_type">
    <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(measurement.detail_type)"><label>الطول</label><input type="text" class="form-control" name="length" x-model="measurement.length"></div>
    <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(measurement.detail_type)"><label>الكتف</label><input type="text" class="form-control" name="shoulder_width" x-model="measurement.shoulder_width"></div>
    <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(measurement.detail_type)"><label>طول الذراع</label><input type="text" class="form-control" name="arm_length" x-model="measurement.arm_length"></div>
    <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(measurement.detail_type)"><label>عرض اليد</label><input type="text" class="form-control" name="arm_width" x-model="measurement.arm_width"></div>
    <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(measurement.detail_type)"><label>الجوانب</label><input type="text" class="form-control" name="sides" x-model="measurement.sides"></div>
    <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(measurement.detail_type)"><label>القبة</label><input type="text" class="form-control" name="neck" x-model="measurement.neck"></div>
    <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(measurement.detail_type)"><label>تفصيل القماش</label><select class="form-select" name="fabric_detail" x-model="measurement.fabric_detail"><option value="داخلي">داخلي</option><option value="خارجي">خارجي</option><option value="مقفول">مقفول</option></select></div>
    <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(m.detail_type)">
                                    <label>نوع الكفة</label>
                                    <select class="form-select" :name="`measurements[${index}][cuff_type]`" x-model="m.cuff_type">
                                        <option value="عادي">عادي</option>
                                        <option value="برمة">برمة</option>
                                        <option value="7 سنتمتر">7 سنتمتر</option>
                                    </select>
                                </div>
     <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(m.detail_type)"><label>نوع الزاير </label><select class="form-select" :name="`measurements[${index}][buttons]`" x-model="m.buttons"><option value="بدون">بدون</option><option value="زرار عادي">زرار عادي</option><option value="زرار كبس">زرار كبس</option></select></div>
    <div class="col-md-2 mb-2" x-show="['جلابية', 'عراقي', 'على الله'].includes(m.detail_type)"><label>القيطان </label><select class="form-select" :name="`measurements[${index}][qitan]`" x-model="m.qitan"><option value="بدون">بدون</option><option value="ابيض">ابيض</option><option value="اسود">اسود</option><option value="بني">بني</option><option value="كحلي">كحلي</option></select></div>
    <div class="col-md-2 mb-2" x-show="['سروال', 'على الله'].includes(measurement.detail_type)"><label>طول السروال</label><input type="text" class="form-control" name="pants_length" x-model="measurement.pants_length"></div>
    <div class="col-md-2 mb-2" x-show="['سروال', 'على الله'].includes(measurement.detail_type)"><label>نوع السروال</label><select class="form-select" name="pants_type" x-model="measurement.pants_type"><option value="لستك">لستك</option><option value="تكة">تكة</option></select></div>
</div>
