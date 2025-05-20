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
        Schema::table('appointments', function (Blueprint $table) {
            // Add columns for promotion and discount tracking
            if (!Schema::hasColumn('appointments', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('final_price');
            }
            if (!Schema::hasColumn('appointments', 'direct_discount_percent')) {
                $table->decimal('direct_discount_percent', 5, 2)->default(0)->after('discount_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['discount_amount', 'direct_discount_percent']);
        });
    }
};
