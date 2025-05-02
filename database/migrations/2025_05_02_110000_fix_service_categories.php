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
        // Cập nhật các dịch vụ có category_id là null
        
        // Gói làm đẹp toàn diện cô dâu -> Gói dịch vụ -> Gói làm đẹp toàn diện
        DB::table('services')
            ->where('id', 3)
            ->update(['category_id' => 29]); // Gói làm đẹp toàn diện
        
        // Làm Móng Gel -> Chăm sóc móng -> Sơn gel
        DB::table('services')
            ->where('id', 9)
            ->update(['category_id' => 19]); // Sơn gel
        
        // Phun Xăm Chân Mày -> Trang điểm
        DB::table('services')
            ->where('id', 12)
            ->update(['category_id' => 4]); // Trang điểm
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Khôi phục lại giá trị null cho category_id
        DB::table('services')
            ->whereIn('id', [3, 9, 12])
            ->update(['category_id' => null]);
    }
};
