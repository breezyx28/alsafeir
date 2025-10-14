<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\User;

class CreateOrdersTable extends Migration
{
    // database/migrations/xxxx_xx_xx_xxxxxx_create_orders_table.php

public function up(): void
{
    Schema::create('orders', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
        $table->foreignIdFor(Branch::class)->constrained()->cascadeOnDelete();
        $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
        
        // اجعل هذه الحقول قابلة للقيمة الفارغة في البداية
        $table->date('order_date')->nullable(); 
        $table->date('expected_delivery_date')->nullable();
        $table->date('operator_delivery_date')->nullable();
        
        $table->string('order_number')->unique()->nullable(); // سيتم إنشاؤه عند تأكيد الطلب
        $table->string('delivery_code')->unique()->nullable(); // سيتم إنشاؤه عند التسليم
        
        // هذا هو الحقل الأهم في نظام الـ Wizard
        $table->string('status')->default('draft'); // الحالات: draft, pending, ready, delivered

        $table->text('notes')->nullable();
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

