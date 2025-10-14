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
       Schema::create('production_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reference')->nullable();
            $table->date('production_date');
            $table->text('notes')->nullable();
            $table->unsignedInteger('quantity');
            $table->foreignUuid('product_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('variant_id')->nullable()->constrained('product_variants')->onDelete('set null');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};
