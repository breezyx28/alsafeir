<?php

namespace App\Http\Controllers;

use App\Models\TailorPayment;
use App\Models\Tailor;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TailorPaymentController extends Controller
{
    public function index()
    {
        $tailorPayments = TailorPayment::with('tailor')->get();
        return view('tailor_payments.index', compact('tailorPayments'));
    }

    public function create()
    {
        $tailors = Tailor::all();
        return view('tailor_payments.create', compact('tailors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tailor_id' => 'required|exists:tailors,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,other',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $payment = TailorPayment::create($request->all());
            $tailor = Tailor::find($request->tailor_id);
            $user = Auth::user();

            // جلب حسابات دفتر اليومية
            $advancePayableId  = Account::where('code', 2300)->value('id'); // مستحقات الترزي
            $cashAccountId     = Account::where('code', 1000)->value('id'); // كاش
            $bankAccountId     = Account::where('code', 1010)->value('id'); // بنك

            if (!$advancePayableId) {
                throw new \Exception("حساب مستحقات الترزي غير موجود");
            }

            // إنشاء قيد اليومية للدفعة
            $journalEntry = JournalEntry::create([
                'entry_date' => $request->payment_date,
                'description' => "دفع للترزي: {$tailor->name}",
                'created_by' => $user->id,
            ]);

            // طرف مدين: مستحقات الترزي
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $advancePayableId,
                'debit' => $request->amount,
                'credit' => 0,
                'notes' => "تخفيض مستحقات الترزي {$tailor->name}",
            ]);

            // طرف دائن: النقدية / البنك حسب طريقة الدفع
            $journalAccountId = $request->payment_method === 'bank_transfer' ? $bankAccountId : $cashAccountId;

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $journalAccountId,
                'debit' => 0,
                'credit' => $request->amount,
                'notes' => "صرف دفعة للترزي {$tailor->name}",
            ]);
        });

        return redirect()->route('tailor_payments.index')->with('success', 'تم تسجيل الدفعة في النظام ودفتر اليومية بنجاح.');
    }

    public function show(TailorPayment $tailorPayment)
    {
        return view('tailor_payments.show', compact('tailorPayment'));
    }

    public function edit(TailorPayment $tailorPayment)
    {
        $tailors = Tailor::all();
        return view('tailor_payments.edit', compact('tailorPayment', 'tailors'));
    }

    public function update(Request $request, TailorPayment $tailorPayment)
    {
        $request->validate([
            'tailor_id' => 'required|exists:tailors,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,other',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $tailorPayment) {
            $tailorPayment->update($request->all());
            $tailor = Tailor::find($request->tailor_id);
            $user = Auth::user();

            // جلب حسابات دفتر اليومية
            $advancePayableId  = Account::where('code', 2300)->value('id'); // مستحقات الترزي
            $cashAccountId     = Account::where('code', 1100)->value('id'); // كاش
            $bankAccountId     = Account::where('code', 1200)->value('id'); // بنك

            if (!$advancePayableId) {
                throw new \Exception("حساب مستحقات الترزي غير موجود");
            }

            // إنشاء قيد اليومية لتعديل الدفعة
            $journalEntry = JournalEntry::create([
                'entry_date' => $request->payment_date,
                'description' => "تعديل دفعة للترزي: {$tailor->name}",
                'created_by' => $user->id,
            ]);

            // طرف مدين: مستحقات الترزي
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $advancePayableId,
                'debit' => $request->amount,
                'credit' => 0,
                'notes' => "تخفيض مستحقات الترزي {$tailor->name} بعد تعديل الدفعة",
            ]);

            // طرف دائن: النقدية / البنك حسب طريقة الدفع
            $journalAccountId = $request->payment_method === 'bank_transfer' ? $bankAccountId : $cashAccountId;

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $journalAccountId,
                'debit' => 0,
                'credit' => $request->amount,
                'notes' => "صرف دفعة للترزي {$tailor->name} بعد تعديل الدفعة",
            ]);
        });

        return redirect()->route('tailor_payments.index')->with('success', 'تم تعديل الدفعة وتسجيلها في دفتر اليومية.');
    }

    public function destroy(TailorPayment $tailorPayment)
    {
        $tailorPayment->delete();
        return redirect()->route('tailor_payments.index')->with('success', 'تم حذف الدفعة بنجاح.');
    }
}
