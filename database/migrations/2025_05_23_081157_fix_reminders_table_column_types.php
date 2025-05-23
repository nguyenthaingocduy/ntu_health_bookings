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
        Schema::table('reminders', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['appointment_id']);
            $table->dropForeign(['created_by']);

            // Change column types
            $table->string('appointment_id')->change();
            $table->string('created_by')->change();

            // Re-add foreign keys
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['appointment_id']);
            $table->dropForeign(['created_by']);

            // Change back to bigint
            $table->bigInteger('appointment_id')->unsigned()->change();
            $table->bigInteger('created_by')->unsigned()->change();
        });
    }
};
