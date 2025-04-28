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
            // Kiểm tra xem cột đã tồn tại chưa
            if (!Schema::hasColumn('appointments', 'promotion_code')) {
                $table->string('promotion_code')->nullable()->after('notes');
            }

            // Thêm cột final_price
            if (!Schema::hasColumn('appointments', 'final_price')) {
                $table->decimal('final_price', 15, 2)->nullable()->after('promotion_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Xóa cột final_price trước vì nó phụ thuộc vào promotion_code
            if (Schema::hasColumn('appointments', 'final_price')) {
                $table->dropColumn('final_price');
            }

            // Xóa cột promotion_code
            if (Schema::hasColumn('appointments', 'promotion_code')) {
                $table->dropColumn('promotion_code');
            }
        });
    }
};
