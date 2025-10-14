<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    public function up()
    {
        // database/migrations/xxxx_create_order_products_table.php
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('order_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('product_variant_id')->constrained();
            $table->decimal('quantity', 8, 2);
            $table->decimal('price_per_meter', 8, 2);
            $table->string('status')->default('جاري التنفيذ'); // (جاري التنفيذ, جاهز)
            $table->text('notes')->nullable();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('order_products');
    }
}

