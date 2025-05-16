<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\CustomerType;
use Illuminate\Support\Str;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'id' => Str::uuid(),
                'type_name' => 'Regular',
                'discount_percentage' => 0,
                'priority_level' => 0,
                'min_spending' => 0,
                'description' => 'Khách hàng thông thường, không có ưu đãi đặc biệt.',
                'color_code' => '#6B7280', // Gray
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'type_name' => 'VIP',
                'discount_percentage' => 5,
                'priority_level' => 1,
                'min_spending' => 2000000,
                'description' => 'Khách hàng VIP được giảm 5% cho tất cả dịch vụ và được ưu tiên đặt lịch.',
                'color_code' => '#EAB308', // Yellow
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'type_name' => 'Premium',
                'discount_percentage' => 10,
                'priority_level' => 2,
                'min_spending' => 5000000,
                'description' => 'Khách hàng Premium được giảm 10% cho tất cả dịch vụ, ưu tiên cao nhất khi đặt lịch và được tư vấn riêng.',
                'color_code' => '#B91C1C', // Red
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        CustomerType::insert($types);
    }
}
