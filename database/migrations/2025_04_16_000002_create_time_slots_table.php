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
        Schema::create('time_slots', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->time('start_time');
            $table->time('end_time');
            $table->tinyInteger('day_of_week')->nullable(); // 0 = Sunday, 6 = Saturday, null = any day
            $table->boolean('is_available')->default(true);
            $table->integer('max_appointments')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
