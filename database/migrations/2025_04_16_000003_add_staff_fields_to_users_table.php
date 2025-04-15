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
        Schema::table('users', function (Blueprint $table) {
            $table->string('staff_id')->nullable()->after('type_id');
            $table->string('department')->nullable()->after('staff_id');
            $table->string('position')->nullable()->after('department');
            $table->string('employee_code')->nullable()->after('position');
            $table->date('last_health_check')->nullable()->after('employee_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'staff_id',
                'department',
                'position',
                'employee_code',
                'last_health_check'
            ]);
        });
    }
};
