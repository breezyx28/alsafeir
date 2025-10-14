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
        Schema::create("piece_rate_definitions", function (Blueprint $table) {
            $table->id();
            $table->string("item_type")->unique(); // نوع القطعة (جلابية، سروال) - يجب أن يكون فريداً
            $table->decimal("rate", 8, 2); // الأجر المحدد لهذه القطعة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("piece_rate_definitions");
    }
};