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
        // Kiểm tra xem bảng service_consultations đã tồn tại chưa
        if (!Schema::hasTable('service_consultations')) {
            Schema::create('service_consultations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id');
                $table->unsignedBigInteger('service_id');
                $table->text('notes');
                $table->date('recommended_date')->nullable();
                $table->unsignedBigInteger('created_by');
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->enum('status', ['pending', 'converted', 'cancelled'])->default('pending');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Kiểm tra xem cột consultation_id đã tồn tại trong bảng appointments chưa
        if (!Schema::hasColumn('appointments', 'consultation_id')) {
            // Thêm cột consultation_id vào bảng appointments
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
        // Xóa cột consultation_id khỏi bảng appointments
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['consultation_id']);
            $table->dropColumn('consultation_id');
        });

        Schema::dropIfExists('service_consultations');
    }
};
