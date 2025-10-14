<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exchange_items', function (Blueprint $table) {
            $table->id();

            // ربط العنصر بالاستبدال
            $table->foreignId('exchange_id')
                  ->constrained('exchanges')
                  ->cascadeOnDelete();

            // المنتج الأصلي الذي تم إرجاعه
            $table->foreignUuid('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');

            // المتغير (Variant) إن وجد
            $table->foreignUuid('variant_id')
                  ->nullable()
                  ->constrained('product_variants')
                  ->onDelete('cascade');

            // الكمية
            $table->integer('quantity')->default(1);

            // السعر أو المبلغ المرتجع/المضاف
            $table->decimal('amount', 12, 2)->default(0);

            // حالة المنتج (جديد، مستعمل، تالف)
            $table->enum('condition', ['جديد','مستعمل','تالف'])->default('جديد');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exchange_items');
    }
};
