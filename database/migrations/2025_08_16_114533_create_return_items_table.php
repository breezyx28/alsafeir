<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnItemsTable extends Migration
{
    public function up()
    {
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();

            // بدل returns خلينا مع sale_returns
            $table->foreignId('return_id')
                  ->constrained('sale_returns')
                  ->cascadeOnDelete();

            // المنتجات
            $table->foreignUuid('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');

            $table->foreignUuid('variant_id')
                  ->nullable()
                  ->constrained('product_variants')
                  ->onDelete('cascade');

            $table->integer('quantity')->default(1);
            $table->decimal('refund_amount', 12, 2)->default(0);
            $table->enum('condition', ['new','used','damaged'])->default('new');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('return_items');
    }
}
