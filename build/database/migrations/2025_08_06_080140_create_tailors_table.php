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
        Schema::create('tailors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الترزي الكامل
            $table->string('phone'); // رقم الهاتف
            $table->text('address')->nullable(); // عنوان الترزي
            $table->date('join_date'); // تاريخ انضمام الترزي
            $table->enum('status', ['active', 'inactive'])->default('active'); // حالة الترزي
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->string('id_number')->nullable(); // رقم الهوية (اختياري)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tailors');
    }
};