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
        Schema::create('settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
        
        // Insert default settings
        DB::table('settings')->insert([
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'key' => 'site_name',
                'value' => 'NTU Health Booking',
                'group' => 'general',
                'description' => 'Tên cửa hàng',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'key' => 'site_description',
                'value' => 'Hệ thống đặt lịch khám sức khỏe và làm đẹp',
                'group' => 'general',
                'description' => 'Mô tả cửa hàng',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'key' => 'contact_email',
                'value' => 'ntuhealthbooking@gmail.com',
                'group' => 'contact',
                'description' => 'Email liên hệ',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'key' => 'contact_phone',
                'value' => '(0258) 2471303',
                'group' => 'contact',
                'description' => 'Số điện thoại liên hệ',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'key' => 'address',
                'value' => '02 Nguyễn Đình Chiểu, Nha Trang, Khánh Hòa',
                'group' => 'contact',
                'description' => 'Địa chỉ cửa hàng',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'key' => 'logo',
                'value' => 'images/logo.png',
                'group' => 'appearance',
                'description' => 'Logo cửa hàng',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'key' => 'favicon',
                'value' => 'images/favicon.ico',
                'group' => 'appearance',
                'description' => 'Favicon cửa hàng',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'key' => 'currency',
                'value' => 'VND',
                'group' => 'payment',
                'description' => 'Đơn vị tiền tệ',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'key' => 'tax_rate',
                'value' => '10',
                'group' => 'payment',
                'description' => 'Thuế suất (%)',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
