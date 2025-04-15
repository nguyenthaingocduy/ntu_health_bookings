<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HealthCheckupServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Khám sức khỏe định kỳ cơ bản',
                'description' => 'Gói khám sức khỏe định kỳ cơ bản dành cho cán bộ viên chức Trường Đại học Nha Trang',
                'price' => 0, // Free for university staff
                'status' => 'active',
                'is_health_checkup' => true,
                'required_tests' => json_encode([
                    'Khám tổng quát',
                    'Đo huyết áp',
                    'Xét nghiệm máu cơ bản',
                    'Đo chiều cao, cân nặng'
                ]),
                'preparation_instructions' => 'Nhịn ăn 8 giờ trước khi xét nghiệm máu. Mang theo thẻ nhân viên và CMND/CCCD.'
            ],
            [
                'name' => 'Khám sức khỏe định kỳ nâng cao',
                'description' => 'Gói khám sức khỏe định kỳ nâng cao dành cho cán bộ viên chức Trường Đại học Nha Trang',
                'price' => 0, // Free for university staff
                'status' => 'active',
                'is_health_checkup' => true,
                'required_tests' => json_encode([
                    'Khám tổng quát',
                    'Đo huyết áp',
                    'Xét nghiệm máu toàn diện',
                    'Đo chiều cao, cân nặng',
                    'Điện tâm đồ',
                    'X-quang ngực',
                    'Siêu âm ổ bụng'
                ]),
                'preparation_instructions' => 'Nhịn ăn 8 giờ trước khi xét nghiệm máu. Mang theo thẻ nhân viên và CMND/CCCD. Mặc quần áo rộng rãi, dễ thay đổi.'
            ],
            [
                'name' => 'Khám sức khỏe chuyên khoa',
                'description' => 'Khám chuyên khoa theo yêu cầu dành cho cán bộ viên chức Trường Đại học Nha Trang',
                'price' => 0, // Free for university staff
                'status' => 'active',
                'is_health_checkup' => true,
                'required_tests' => json_encode([
                    'Khám chuyên khoa theo yêu cầu'
                ]),
                'preparation_instructions' => 'Mang theo thẻ nhân viên, CMND/CCCD và các kết quả xét nghiệm trước đó (nếu có).'
            ]
        ];

        foreach ($services as $service) {
            Service::create(array_merge([
                'slug' => Str::slug($service['name']),
                'promotion' => 0,
                'category_id' => null,
                'clinic_id' => null,
                'image_url' => null
            ], $service));
        }
    }
}
