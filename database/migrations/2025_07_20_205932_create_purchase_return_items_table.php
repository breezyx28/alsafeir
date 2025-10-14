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
        Schema::create('purchase_return_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_return_id')->constrained()->onDelete('cascade');
            
            $table->uuid('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->uuid('variant_id')->nullable();
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('set null');

            $table->integer('quantity')->default(0);
            $table->decimal('unit_price', 10, 2)->default(0); // السعر وقت الشراء
            $table->decimal('subtotal', 10, 2)->default(0); // الكمية * السعر

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_return_items');
    }
};
