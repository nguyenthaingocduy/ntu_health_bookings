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
        // Bảng appointments đã được tạo ở migration 2025_04_09_170031_create_appointments_table.php
        // Vô hiệu hóa để tránh xung đột
        /*
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->uuid('customer_id');
            $table->foreignId('service_id');
            $table->uuid('employee_id')->nullable();
            $table->string('time_appointments_id');
            $table->foreignId('clinic_id')->nullable();
            $table->date('date_appointments');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Vô hiệu hóa để tránh xung đột
        // Schema::dropIfExists('appointments');
    }
};
