<?php

namespace App\Http\Controllers;

use App\Models\TailorAdvance;
use App\Models\Tailor;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TailorAdvanceController extends Controller
{
    public function index()
    {
        $tailorAdvances = TailorAdvance::with('tailor')->get();
        return view('tailor_advances.index', compact('tailorAdvances'));
    }

    public function create()
    {
        $tailors = Tailor::all();
        return view('tailor_advances.create', compact('tailors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tailor_id' => 'required|exists:tailors,id',
            'amount' => 'required|numeric|min:0.01',
            'advance_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $advance = TailorAdvance::create($request->all());
            $tailor = Tailor::find($request->tailor_id);
            $user = Auth::user();

            // الحسابات المطلوبة
            $advancePayableId  = Account::where('code', 2300)->value('id'); // مستحقات الترزي
            $cashAccountId     = Account::where('code', 1000)->value('id'); // كاش
            $bankAccountId     = Account::where('code', 1010)->value('id'); // بنك

            if (!$advancePayableId) {
                throw new \Exception("حساب مستحقات الترزي غير موجود");
            }

            // إنشاء قيد اليومية للسلفة
            $journalEntry = JournalEntry::create([
                'entry_date' => $request->advance_date,
                'description' => "سلفة للترزي: {$tailor->name}",
                'created_by' => $user->id,
            ]);

            // طرف مدين: النقدية / البنك (لأن الشركة أعطت المال)
            $journalAccountId = $request->payment_method ?? 'cash'; // يمكن إضافة حقل payment_method إذا أردت
            $journalAccountId = $journalAccountId === 'bank' ? $bankAccountId : $cashAccountId;

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $journalAccountId,
                'debit' => $request->amount,
                'credit' => 0,
                'notes' => "صرف سلفة للترزي {$tailor->name}",
            ]);

            // طرف دائن: مستحقات الترزي
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $advancePayableId,
                'debit' => 0,
                'credit' => $request->amount,
                'notes' => "سلفة مستحقة على الترزي {$tailor->name}",
            ]);
        });

        return redirect()->route('tailor_advances.index')->with('success', 'تم إنشاء السلفة وتسجيلها في دفتر اليومية.');
    }

    public function show(TailorAdvance $tailorAdvance)
    {
        return view('tailor_advances.show', compact('tailorAdvance'));
    }

    public function edit(TailorAdvance $tailorAdvance)
    {
        $tailors = Tailor::all();
        return view('tailor_advances.edit', compact('tailorAdvance', 'tailors'));
    }

    public function update(Request $request, TailorAdvance $tailorAdvance)
    {
        $request->validate([
            'tailor_id' => 'required|exists:tailors,id',
            'amount' => 'required|numeric|min:0.01',
            'advance_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $tailorAdvance) {
            $tailorAdvance->update($request->all());
            $tailor = Tailor::find($request->tailor_id);
            $user = Auth::user();

            // الحسابات المطلوبة
            $advancePayableId  = Account::where('code', 2300)->value('id'); // مستحقات الترزي
            $cashAccountId     = Account::where('code', 1100)->value('id'); // كاش
            $bankAccountId     = Account::where('code', 1200)->value('id'); // بنك

            if (!$advancePayableId) {
                throw new \Exception("حساب مستحقات الترزي غير موجود");
            }

            // إنشاء قيد يومية للتعديل (يمكنك تحديد ما إذا تريد إلغاء القديم أو تعديل)
            $journalEntry = JournalEntry::create([
                'entry_date' => $request->advance_date,
                'description' => "تعديل سلفة للترزي: {$tailor->name}",
                'created_by' => $user->id,
            ]);

            // طرف مدين: النقدية / البنك
            $journalAccountId = $request->payment_method ?? 'cash';
            $journalAccountId = $journalAccountId === 'bank' ? $bankAccountId : $cashAccountId;

            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $journalAccountId,
                'debit' => $request->amount,
                'credit' => 0,
                'notes' => "صرف سلفة بعد تعديل للترزي {$tailor->name}",
            ]);

            // طرف دائن: مستحقات الترزي
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $advancePayableId,
                'debit' => 0,
                'credit' => $request->amount,
                'notes' => "سلفة مستحقة بعد تعديل للترزي {$tailor->name}",
            ]);
        });

        return redirect()->route('tailor_advances.index')->with('success', 'تم تعديل السلفة وتسجيل التعديل في دفتر اليومية.');
    }

    public function destroy(TailorAdvance $tailorAdvance)
    {
        $tailorAdvance->delete();
        return redirect()->route('tailor_advances.index')->with('success', 'تم حذف السلفة بنجاح.');
    }
}
