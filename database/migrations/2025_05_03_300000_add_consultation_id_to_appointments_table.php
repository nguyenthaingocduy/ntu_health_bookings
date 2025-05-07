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
        // Kiểm tra xem cột consultation_id đã tồn tại trong bảng appointments chưa
        if (!Schema::hasColumn('appointments', 'consultation_id')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->unsignedBigInteger('consultation_id')->nullable()->after('employee_id');
                $table->foreign('consultation_id')->references('id')->on('service_consultations')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['consultation_id']);
            $table->dropColumn('consultation_id');
        });
    }
};
