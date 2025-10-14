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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('sku')->unique();
            $table->enum('type', ['ready', 'raw_material']); // جاهز، خام (قماش)
            $table->foreignUuid('category_id')->constrained('product_categories');
            $table->decimal('base_price', 10, 2);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->enum('unit', ['piece', 'meter'])->default('piece'); // وحدة البيع (قطعة، متر)
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
