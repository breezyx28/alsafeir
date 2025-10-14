<?php

namespace App\Http\Controllers;

use App\Models\Tailor;
use App\Models\TailorProductionLog;
use App\Models\TailorAdvance;
use App\Models\TailorPayment;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TailorAccountingController extends Controller
{
    // قائمة الترزيين
    public function index()
    {
        $tailors = Tailor::all();
        return view("tailor_accounting.index", compact("tailors"));
    }

    // عرض الحسابات للفترة
    public function show(Request $request, Tailor $tailor)
    {
        $startDate = $request->input("start_date") ?: now()->startOfMonth()->toDateString();
        $endDate   = $request->input("end_date")   ?: now()->endOfMonth()->toDateString();

        // إجمالي الأجر من الإنتاج المكتمل
        $totalEarnings = TailorProductionLog::where("tailor_id", $tailor->id)
            ->whereBetween("production_date", [$startDate, $endDate])
            ->where("status", "completed")
            ->join("piece_rate_definitions", "tailor_production_logs.piece_rate_definition_id", "=", "piece_rate_definitions.id")
            ->select(DB::raw("SUM(tailor_production_logs.quantity * piece_rate_definitions.rate) as total_amount"))
            ->first()
            ->total_amount ?? 0;

        // السلفيات
        $totalAdvances = TailorAdvance::where("tailor_id", $tailor->id)
            ->whereBetween("advance_date", [$startDate, $endDate])
            ->sum("amount");

        // الدفعات
        $totalPayments = TailorPayment::where("tailor_id", $tailor->id)
            ->whereBetween("payment_date", [$startDate, $endDate])
            ->sum("amount");

        // صافي المستحقات
        $netPayable = $totalEarnings - $totalAdvances - $totalPayments;

        // التفاصيل للفترة
        $advances = TailorAdvance::where("tailor_id", $tailor->id)
            ->whereBetween("advance_date", [$startDate, $endDate])
            ->get();

        $payments = TailorPayment::where("tailor_id", $tailor->id)
            ->whereBetween("payment_date", [$startDate, $endDate])
            ->get();

        $productionLogs = TailorProductionLog::where("tailor_id", $tailor->id)
            ->whereBetween("production_date", [$startDate, $endDate])
            ->where("status", "completed")
            ->with("pieceRateDefinition")
            ->get();

        return view("tailor_accounting.show", compact(
            "tailor", "totalEarnings", "totalAdvances", "totalPayments",
            "netPayable", "advances", "payments", "productionLogs",
            "startDate", "endDate"
        ));
    }

    // معالجة الدفع للترزي مع تسجيل قيد اليومية
    public function processPayment(Request $request, Tailor $tailor)
    {
        $request->validate([
            "amount" => "required|numeric|min:0.01",
            "payment_method" => "required|in:cash,bank_transfer,other",
            "notes" => "nullable|string",
        ]);

        DB::transaction(function () use ($request, $tailor) {
            // تسجيل الدفعة
            $payment = TailorPayment::create([
                "tailor_id" => $tailor->id,
                "amount" => $request->amount,
                "payment_date" => now(),
                "payment_method" => $request->payment_method,
                "notes" => $request->notes,
            ]);

            $user = Auth::user();

            // جلب الحسابات
            $expenseAccountId = Account::where('code', 5400)->value('id'); // مصاريف الترزية
            $cashAccountId    = Account::where('code', 1100)->value('id'); // كاش
            $bankAccountId    = Account::where('code', 1200)->value('id'); // بنك
            $tailorPayableId  = Account::where('code', 2300)->value('id'); // مستحقات الترزي

            if (!$expenseAccountId || !$tailorPayableId) {
                throw new \Exception("الحسابات المطلوبة للترزي غير موجودة");
            }

            // إنشاء قيد اليومية للدفعة
            $journalEntry = JournalEntry::create([
                'entry_date' => now(),
                'description' => "دفعة الترزي: {$tailor->name}",
                'created_by' => $user->id,
            ]);

            // طرف مدين: مستحقات الترزي
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $tailorPayableId,
                'debit' => $request->amount,
                'credit' => 0,
                'notes' => "دفعة للترزي {$tailor->name}",
            ]);

            // طرف دائن: النقد أو البنك
            $paymentAccountId = $request->payment_method === 'cash' ? $cashAccountId : $bankAccountId;
            JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $paymentAccountId,
                'debit' => 0,
                'credit' => $request->amount,
                'notes' => "دفعة نقدية / بنكية للترزي {$tailor->name}",
            ]);
        });

        return redirect()->route("tailor_accounting.show", $tailor->id)
            ->with("success", "تم تسجيل الدفعة بنجاح مع قيد اليومية.");
    }
}
