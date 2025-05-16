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
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('type_name');
            $table->integer('priority_level')->default(0)->after('discount_percentage');
            $table->decimal('min_spending', 15, 2)->default(0)->after('priority_level');
            $table->text('description')->nullable()->after('min_spending');
            $table->string('color_code', 20)->default('#FFD700')->after('description');
            $table->boolean('is_active')->default(true)->after('color_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_types', function (Blueprint $table) {
            $table->dropColumn([
                'discount_percentage',
                'priority_level',
                'min_spending',
                'description',
                'color_code',
                'is_active'
            ]);
        });
    }
};
