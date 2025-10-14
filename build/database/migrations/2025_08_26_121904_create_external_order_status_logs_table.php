<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('external_order_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('external_order_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['مسودة', 'قيد التنفيذ', 'مكتمل', 'ملغي']);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_order_status_logs');
    }
};

