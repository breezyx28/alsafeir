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
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();

            $table->uuid('purchase_order_id'); // الفاتورة المرجعية
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');

            $table->uuid('supplier_id'); // المورد المرتجع إليه
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');

            $table->date('return_date');
            $table->decimal('total_amount', 10, 2)->default(0); // القيمة المالية للمرتجع

            $table->enum('refund_type', ['none', 'cash', 'credit', 'exchange'])->default('none');  
            // نوع التعويض: لا شيء - كاش - رصيد - استبدال

            $table->text('notes')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};
