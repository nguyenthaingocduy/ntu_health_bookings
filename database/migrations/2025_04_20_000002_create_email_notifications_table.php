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
        Schema::create('email_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // registration, booking, reminder, etc.
            $table->string('user_id')->nullable(); // Using string to match the users table
            $table->string('appointment_id')->nullable(); // Using string to match the appointments table
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->text('data')->nullable(); // JSON data for the email
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_notifications');
    }
};
