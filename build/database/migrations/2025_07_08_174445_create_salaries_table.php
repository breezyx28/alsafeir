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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('month');
            $table->string('year');
            $table->decimal('basic_salary', 10, 2);
            $table->integer('work_days')->default(0);
            $table->integer('absent_days')->default(0);
            $table->decimal('absence_deduction', 10, 2)->default(0);
            $table->decimal('bonuses_total', 10, 2)->default(0);
            $table->decimal('penalties_total', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};