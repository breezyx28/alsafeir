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
        Schema::create("quality_reports", function (Blueprint $table) {
            $table->id();
            $table->foreignId("tailor_id")->constrained("tailors")->onDelete("cascade"); // مفتاح خارجي لجدول الترزية
            $table->string("order_item_identifier"); // معرف الصنف/القطعة (مثال: رقم الطلب-رقم الصنف)
            $table->string("issue_type"); // نوع المشكلة (خياطة، مقاس، عيب قماش)
            $table->text("description"); // وصف تفصيلي للمشكلة
            $table->enum("severity", ["low", "medium", "high"]); // خطورة المشكلة
            $table->enum("status", ["pending", "fixed"])->default("pending"); // حالة الإصلاح
            $table->string("reported_by"); // اسم الموظف الذي أبلغ عن المشكلة
            $table->date("reported_date"); // تاريخ الإبلاغ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("quality_reports");
    }
};