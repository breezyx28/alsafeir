<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaymentsTable extends Migration
{
    public function up()
    {
                // database/migrations/xxxx_create_order_payments_table.php
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('order_id')->constrained()->cascadeOnDelete();
            $table->decimal('tailoring_price', 10, 2);
            $table->decimal('fabrics_total', 10, 2);
            $table->decimal('total_before_discount', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('total_after_discount', 10, 2);
            $table->decimal('total_paid', 10, 2)->default(0); // إجمالي ما تم دفعه
            $table->decimal('remaining_amount', 10, 2);
            $table->timestamps();
        });


    }

    public function down()
    {
        Schema::dropIfExists('order_payments');
    }
}
