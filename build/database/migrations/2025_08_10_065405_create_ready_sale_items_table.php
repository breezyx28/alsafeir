<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadySaleItemsTable extends Migration
{
    public function up()
    {
        Schema::create('ready_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ready_sale_id')->constrained('ready_sales')->cascadeOnDelete();
            $table->foreignUuid('product_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('sub_total', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ready_sale_items');
    }
}
