<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ready_sale_id')->constrained('ready_sales')->cascadeOnDelete();
            $table->dateTime('return_date')->nullable();
            $table->decimal('total_refund_amount', 12, 2)->default(0);
            $table->text('reason')->nullable();
            $table->enum('status', ['pending','completed','rejected'])->default('pending');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_returns');
    }
};
