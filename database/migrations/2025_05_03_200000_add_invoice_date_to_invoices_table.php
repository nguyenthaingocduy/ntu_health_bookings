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
        Schema::table('invoices', function (Blueprint $table) {
            // Kiểm tra xem cột invoice_date đã tồn tại chưa
            if (!Schema::hasColumn('invoices', 'invoice_date')) {
                $table->date('invoice_date')->after('invoice_number')->nullable();
            }

            // Kiểm tra xem cột deleted_at đã tồn tại chưa
            if (!Schema::hasColumn('invoices', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('invoice_date');
            $table->dropSoftDeletes();
        });
    }
};
