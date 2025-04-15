<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kiểm tra xem cột started_time đã tồn tại chưa
        if (Schema::hasColumn('times', 'started_time')) {
            // Nếu cột đã tồn tại với kiểu dateTime, sửa thành kiểu time
            Schema::table('times', function (Blueprint $table) {
                $table->time('started_time')->change();
            });
        } else {
            // Nếu cột chưa tồn tại, thêm mới
            Schema::table('times', function (Blueprint $table) {
                $table->time('started_time')->nullable(false);
            });
        }

        // Chạy seed để thêm dữ liệu
        Artisan::call('db:seed', [
            '--class' => 'TimeSeeder',
            '--force' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không cần xóa cột started_time vì nó là cột quan trọng
    }
};
