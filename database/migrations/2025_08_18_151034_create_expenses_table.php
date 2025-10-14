<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {

        // نبني جدول جديد ونظيف
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('expense_date');
            $table->decimal('amount', 15, 2);
            // حساب المصروف
            $table->foreignId('expense_account_id')->constrained('accounts')->cascadeOnDelete();
            // حساب الدفع (صندوق/بنك) - أيضاً من جدول الحسابات
            $table->foreignId('cash_account_id')->constrained('accounts')->cascadeOnDelete();
            // المستخدم الذي سجّل العملية
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // الفرع (اختياري)
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->text('notes')->nullable();
            // ربط القيد
            $table->foreignId('journal_entry_id')->nullable()->constrained('journal_entries')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
