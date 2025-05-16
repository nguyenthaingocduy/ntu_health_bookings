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
        Schema::table('customer_types', function (Blueprint $table) {
            $table->boolean('has_priority_booking')->default(false)->after('is_active');
            $table->boolean('has_personal_consultant')->default(false)->after('has_priority_booking');
            $table->boolean('has_birthday_gift')->default(false)->after('has_personal_consultant');
            $table->boolean('has_free_service')->default(false)->after('has_birthday_gift');
            $table->integer('free_service_count')->default(0)->after('has_free_service');
            $table->boolean('has_extended_warranty')->default(false)->after('free_service_count');
            $table->integer('extended_warranty_days')->default(0)->after('has_extended_warranty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_types', function (Blueprint $table) {
            $table->dropColumn([
                'has_priority_booking',
                'has_personal_consultant',
                'has_birthday_gift',
                'has_free_service',
                'free_service_count',
                'has_extended_warranty',
                'extended_warranty_days'
            ]);
        });
    }
};
