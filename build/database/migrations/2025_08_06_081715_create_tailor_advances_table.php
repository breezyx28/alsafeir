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
        Schema::create("tailor_advances", function (Blueprint $table) {
            $table->id();
            $table->foreignId("tailor_id")->constrained("tailors")->onDelete("cascade"); // مفتاح خارجي لجدول الترزية
            $table->decimal("amount", 10, 2); // مبلغ السلفة
            $table->date("advance_date"); // تاريخ السلفة
            $table->text("notes")->nullable(); // ملاحظات حول السلفة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("tailor_advances");
    }
};