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
        Schema::create('appointments', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('customer_id');
            $table->string('service_id');
            $table->dateTime('date_register');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed', 'no-show']);
            $table->dateTime('date_appointments');
            $table->string('time_appointments_id');
            $table->string('employee_id');
            $table->timestamps();
            
            // Loại bỏ các khóa ngoại để tránh lỗi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
