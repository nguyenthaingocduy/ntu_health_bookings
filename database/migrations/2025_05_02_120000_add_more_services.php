<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm các dịch vụ mới
        $services = [
            [
                'name' => 'Cắt Tóc Nam',
                'slug' => 'cat-toc-nam',
                'description' => 'Dịch vụ cắt tóc nam với nhiều kiểu dáng thời trang, phù hợp với khuôn mặt.',
                'price' => 150000,
                'duration' => 45,
                'category_id' => 12, // Cắt tóc
                'clinic_id' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'is_health_checkup' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cắt Tóc Nữ',
                'slug' => 'cat-toc-nu',
                'description' => 'Dịch vụ cắt tóc nữ với nhiều kiểu dáng thời trang, phù hợp với khuôn mặt.',
                'price' => 200000,
                'duration' => 60,
                'category_id' => 12, // Cắt tóc
                'clinic_id' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1560869713-7d0a29430803?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'is_health_checkup' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tẩy Tế Bào Chết',
                'slug' => 'tay-te-bao-chet',
                'description' => 'Dịch vụ tẩy tế bào chết giúp làn da mịn màng, sáng khỏe.',
                'price' => 350000,
                'duration' => 45,
                'category_id' => 11, // Tẩy tế bào chết
                'clinic_id' => 2,
                'image_url' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'is_health_checkup' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Trang Điểm Dự Tiệc',
                'slug' => 'trang-diem-du-tiec',
                'description' => 'Dịch vụ trang điểm dự tiệc chuyên nghiệp, phù hợp với từng sự kiện.',
                'price' => 500000,
                'duration' => 90,
                'category_id' => 22, // Trang điểm dự tiệc
                'clinic_id' => 2,
                'image_url' => 'https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'is_health_checkup' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gói Thư Giãn Toàn Thân',
                'slug' => 'goi-thu-gian-toan-than',
                'description' => 'Gói dịch vụ thư giãn toàn thân bao gồm massage, tắm thảo dược và xông hơi.',
                'price' => 1200000,
                'duration' => 150,
                'category_id' => 30, // Gói thư giãn
                'clinic_id' => 3,
                'image_url' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'is_health_checkup' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Xông Hơi Thảo Dược',
                'slug' => 'xong-hoi-thao-duoc',
                'description' => 'Dịch vụ xông hơi thảo dược giúp thải độc cơ thể, cải thiện lưu thông máu.',
                'price' => 400000,
                'duration' => 60,
                'category_id' => 27, // Xông hơi
                'clinic_id' => 3,
                'image_url' => 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'is_health_checkup' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Thêm các dịch vụ vào cơ sở dữ liệu
        foreach ($services as $service) {
            DB::table('services')->insert($service);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa các dịch vụ đã thêm
        DB::table('services')
            ->whereIn('slug', [
                'cat-toc-nam',
                'cat-toc-nu',
                'tay-te-bao-chet',
                'trang-diem-du-tiec',
                'goi-thu-gian-toan-than',
                'xong-hoi-thao-duoc',
            ])
            ->delete();
    }
};
