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
        // Migration này đã được thực hiện trong 2023_08_10_100000_create_services_table.php
        // Vô hiệu hóa để tránh xung đột
        /*
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('clinic_id');
            $table->text('description')->nullable();
            $table->text('descriptive')->nullable();
            $table->string('promotion')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('discount')->nullable();
            $table->string('image_url')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
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
        // Schema::dropIfExists('services');
    }
};
