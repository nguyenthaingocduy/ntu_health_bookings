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
        // Cột category_id đã tồn tại trong bảng services
        // Vô hiệu hóa để tránh xung đột
        /*
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null');
        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Vô hiệu hóa để tránh xung đột
        /*
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
        */
    }
};
