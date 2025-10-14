<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("tailor_payments", function (Blueprint $table) {
            $table->id();
            $table->foreignId("tailor_id")->constrained("tailors")->onDelete("cascade"); // مفتاح خارجي لجدول الترزية
            $table->decimal("amount", 10, 2); // المبلغ المدفوع للترزي
            $table->date("payment_date"); // تاريخ الدفعة
            $table->enum("payment_method", ["cash", "bank_transfer", "other"])->default("cash"); // طريقة الدفع
            $table->text("notes")->nullable(); // ملاحظات حول الدفعة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("tailor_payments");
    }
};