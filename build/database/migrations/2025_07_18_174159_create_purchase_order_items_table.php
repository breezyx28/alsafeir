<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('product_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->decimal('cost_price', 10, 2); // السعر للوحدة
            $table->decimal('total_cost', 12, 2); // quantity * cost_price
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
