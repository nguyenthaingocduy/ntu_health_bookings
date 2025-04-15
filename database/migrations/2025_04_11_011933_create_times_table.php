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
        // Bảng times đã được tạo ở migration 2025_04_07_080306_create_times_table.php
        // Vô hiệu hóa để tránh xung đột
        /*
        Schema::create('times', function (Blueprint $table) {
            $table->id();
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
        // Schema::dropIfExists('times');
    }
};
