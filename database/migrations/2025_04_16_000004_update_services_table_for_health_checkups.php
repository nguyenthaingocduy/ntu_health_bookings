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
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('is_health_checkup')->default(false)->after('status');
            $table->json('required_tests')->nullable()->after('is_health_checkup');
            $table->text('preparation_instructions')->nullable()->after('required_tests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'is_health_checkup',
                'required_tests',
                'preparation_instructions'
            ]);
        });
    }
};
