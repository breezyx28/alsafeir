<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('external_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('external_order_id')->constrained()->onDelete('cascade');
            $table->enum('detail_type', ['جلابية', 'على الله', 'عراقي', 'سروال']);
            $table->integer('quantity')->default(1);
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('suggested_colors')->nullable();
            $table->decimal('budget', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_order_items');
    }
};

