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
        // Xóa bảng cũ và tạo lại
        Schema::dropIfExists('times');
        
        Schema::create('times', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('started_time');
            $table->timestamps();
        });
        
        // Chạy TimeSeeder để tạo dữ liệu mới
        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'TimeSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('times');
    }
};
