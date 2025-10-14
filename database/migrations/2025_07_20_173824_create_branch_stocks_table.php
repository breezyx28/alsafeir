<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('branch_stocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->uuid('product_id');
            $table->uuid('variant_id')->nullable(); // إذا كان عندك متغيرات للمنتج

            $table->integer('quantity')->default(0); // الكمية المتاحة حالياً في الفرع

            $table->timestamps();

            $table->unique(['branch_id', 'product_id', 'variant_id']); // لضمان عدم تكرار نفس المنتج في نفس الفرع

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_stocks');
    }
};
