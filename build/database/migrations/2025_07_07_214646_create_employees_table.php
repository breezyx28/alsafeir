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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('position')->nullable(); // الوظيفة
            $table->string('national_id')->nullable(); // الرقم القومي أو الهوية
            $table->date('hiring_date')->nullable(); // تاريخ التعيين
            $table->decimal('salary', 10, 2)->nullable();
            $table->boolean('status')->default(true); // نشط / موقوف
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
