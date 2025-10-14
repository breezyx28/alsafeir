<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("إضافة دفعة جديدة") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route("tailor_payments.store") }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="tailor_id">الترزي:</label>
                            <select class="form-control" id="tailor_id" name="tailor_id" required>
                                <option value="">اختر ترزي</option>
                                @foreach ($tailors as $tailor)
                                    <option value="{{ $tailor->id }}" {{ old("tailor_id") == $tailor->id ? "selected" : "" }}>{{ $tailor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="amount">المبلغ:</label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ old("amount") }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="payment_date">تاريخ الدفعة:</label>
                            <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ old("payment_date", date("Y-m-d")) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="payment_method">طريقة الدفع:</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="cash" {{ old("payment_method") == "cash" ? "selected" : "" }}>نقداً</option>
                                <option value="bank_transfer" {{ old("payment_method") == "bank_transfer" ? "selected" : "" }}>تحويل بنكي</option>
                                <option value="other" {{ old("payment_method") == "other" ? "selected" : "" }}>أخرى</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="notes">ملاحظات:</label>
                            <textarea class="form-control" id="notes" name="notes">{{ old("notes") }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success">حفظ</button>
                        <a href="{{ route("tailor_payments.index") }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>