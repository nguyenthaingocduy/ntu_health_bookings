<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Kiểm tra và thêm cột discount_amount nếu chưa tồn tại
            if (!Schema::hasColumn('appointments', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('final_price');
            }

            // Kiểm tra và thêm cột direct_discount_percent nếu chưa tồn tại
            if (!Schema::hasColumn('appointments', 'direct_discount_percent')) {
                $table->decimal('direct_discount_percent', 5, 2)->default(0)->after('discount_amount');
            }
        });

        // Cập nhật các bản ghi hiện có để tính toán lại giá trị giảm giá
        DB::statement('
            UPDATE appointments
            SET discount_amount = CASE
                WHEN final_price IS NOT NULL AND final_price > 0 THEN
                    (SELECT price FROM services WHERE services.id = appointments.service_id) - final_price
                ELSE 0
            END,
            direct_discount_percent = CASE
                WHEN final_price IS NOT NULL AND final_price > 0 AND (SELECT price FROM services WHERE services.id = appointments.service_id) > 0 THEN
                    ROUND(((SELECT price FROM services WHERE services.id = appointments.service_id) - final_price) / (SELECT price FROM services WHERE services.id = appointments.service_id) * 100, 2)
                ELSE 0
            END
            WHERE service_id IS NOT NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không xóa các cột này vì chúng cần thiết cho chức năng giảm giá
    }
};
