<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان العرض، مثال: "عرض العيد الوطني"
            $table->text('description'); // شرح تفصيلي للعرض
            $table->string('image_path'); // مسار الصورة
            $table->boolean('is_active')->default(true); // لتفعيل أو إخفاء العرض
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
