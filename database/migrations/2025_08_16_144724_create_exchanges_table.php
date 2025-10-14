<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangesTable extends Migration
{
    public function up()
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('return_id');
            $table->foreign('return_id')
                ->references('id')
                ->on('sale_returns')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('new_ready_sale_id')->nullable();
            $table->foreign('new_ready_sale_id')
                ->references('id')
                ->on('ready_sales')
                ->nullOnDelete();

            $table->dateTime('exchange_date')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->decimal('amount_paid_by_customer', 12, 2)->default(0);
            $table->decimal('amount_refunded_to_customer', 12, 2)->default(0);

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('exchanges');
    }
}
