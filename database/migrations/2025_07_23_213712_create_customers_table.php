<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_primary', 20)->unique();
            $table->string('phone_secondary', 20)->nullable();
            $table->enum('customer_level', ['عابر', 'مميز', 'VIP', 'عضو'])->default('عابر');
            $table->timestamps(); // created_at و updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
