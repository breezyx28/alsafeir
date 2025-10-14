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
        Schema::create("tailor_production_logs", function (Blueprint $table) {
            $table->id();
            $table->foreignId("tailor_id")->constrained("tailors")->onDelete("cascade"); // مفتاح خارجي لجدول الترزية
            $table->foreignId("piece_rate_definition_id")->constrained("piece_rate_definitions")->onDelete("cascade"); // مفتاح خارجي لتعريف أجر القطعة
            $table->integer("quantity"); // عدد القطع المنتجة
            $table->date("production_date"); // تاريخ الإنتاج
            $table->enum("status", ["completed", "under_review", "rejected"])->default("completed"); // حالة القطعة المنتجة
            $table->text("notes")->nullable(); // ملاحظات إضافية
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("tailor_production_logs");
    }
};
