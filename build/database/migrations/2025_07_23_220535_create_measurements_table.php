<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasurementsTable extends Migration
{
    public function up()
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            
            // ربط مباشر مع الطلب (orders بالـ UUID)
            $table->foreignUuid('order_id')->constrained()->cascadeOnDelete();

            $table->string('detail_type'); // جلابية, سروال, على الله, عراقي
            $table->string('fabric_source')->default('داخلي'); // داخلي / خارجي

            // مقاسات مشتركة (جلابية, عراقي, على الله)
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('shoulder_width', 8, 2)->nullable();
            $table->decimal('arm_length', 8, 2)->nullable(); // تم إضافته
            $table->string('arm_width')->nullable(); // e.g., "23,15"
            $table->string('sides')->nullable(); // e.g., "9,18"
            $table->decimal('neck', 8, 2)->nullable();
            $table->string('fabric_detail')->nullable();
            $table->enum('cuff_type', ['عادي', 'برمة', '7 سم'])->default('عادي'); // داخلي, خارجي, مقفول

            // مقاسات السروال (سروال, على الله)
            $table->string('pants_length')->nullable(); // e.g., "100,20"
            $table->string('pants_type')->nullable(); // لستك, تكة

            // إضافات إدارية
            $table->unsignedInteger('quantity')->default(1);
            $table->string('status')->default('قيد الانتظار');

            // كل عميل + نوع تفصيل + الطلب يكون فريد
            $table->unique(['customer_id', 'detail_type', 'order_id']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('measurements');
    }
}
