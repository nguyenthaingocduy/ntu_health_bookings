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
        Schema::create('health_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('appointment_id')->nullable();
            $table->dateTime('check_date');
            $table->decimal('height', 5, 2)->nullable(); // in cm
            $table->decimal('weight', 5, 2)->nullable(); // in kg
            $table->string('blood_pressure')->nullable(); // e.g., "120/80"
            $table->integer('heart_rate')->nullable(); // beats per minute
            $table->string('blood_type')->nullable(); // A, B, AB, O with +/-
            $table->text('allergies')->nullable();
            $table->text('medical_history')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('recommendations')->nullable();
            $table->dateTime('next_check_date')->nullable();
            $table->text('doctor_notes')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};
