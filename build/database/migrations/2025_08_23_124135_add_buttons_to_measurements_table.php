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
        Schema::table('measurements', function (Blueprint $table) {
            $table->enum('buttons', ['زرار عادي', 'زرار كبس', 'بدون'])->nullable()->after('fabric_detail')->comment('الزراير');
            $table->enum('qitan', ['بدون', 'ابيض ', 'اسود','بني','كحلي'])->nullable()->after('cuff_type')->comment('القيطان');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('measurements', function (Blueprint $table) {
            $table->dropColumn('buttons');
        });
    }
};
