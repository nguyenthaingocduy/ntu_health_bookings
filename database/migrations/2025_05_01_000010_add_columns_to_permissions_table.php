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
        Schema::table('permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('permissions', 'display_name')) {
                $table->string('display_name')->nullable()->after('name');
            }
            
            if (!Schema::hasColumn('permissions', 'description')) {
                $table->string('description')->nullable()->after('display_name');
            }
            
            if (!Schema::hasColumn('permissions', 'group')) {
                $table->string('group')->default('general')->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'display_name')) {
                $table->dropColumn('display_name');
            }
            
            if (Schema::hasColumn('permissions', 'description')) {
                $table->dropColumn('description');
            }
            
            if (Schema::hasColumn('permissions', 'group')) {
                $table->dropColumn('group');
            }
        });
    }
};
