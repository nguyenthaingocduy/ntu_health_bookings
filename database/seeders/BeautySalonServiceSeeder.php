<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BeautySalonServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách category IDs
        $categoryIds = [];
        $categories = \App\Models\Category::all();
        foreach ($categories as $category) {
            $categoryIds[$category->name] = $category->id;
        }

        $services = [
            [
                'name' => 'Gói chăm sóc da cao cấp',
                'description' => 'Gói chăm sóc da cao cấp với các sản phẩm hữu cơ và công nghệ hiện đại',
                'price' => 1500000,
                'duration' => 120,
                'status' => 'active',
                'is_health_checkup' => false,
                'category_id' => $categoryIds['Chăm sóc da'] ?? null,
                'image_url' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'
            ],
            [
                'name' => 'Gói trẻ hóa da toàn diện',
                'description' => 'Gói trẻ hóa da toàn diện sử dụng công nghệ tiên tiến nhất',
                'price' => 2500000,
                'duration' => 150,
                'status' => 'active',
                'is_health_checkup' => false,
                'category_id' => $categoryIds['Trẻ hóa da'] ?? null,
                'image_url' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'
            ],
            [
                'name' => 'Gói làm đẹp toàn diện cô dâu',
                'description' => 'Gói làm đẹp toàn diện dành cho cô dâu, bao gồm trang điểm, làm tóc và chăm sóc da',
                'price' => 5000000,
                'duration' => 240,
                'status' => 'active',
                'is_health_checkup' => false,
                'category_id' => $categoryIds['Làm đẹp'] ?? null,
                'image_url' => 'https://images.unsplash.com/photo-1519699047748-de8e457a634e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'
            ]
        ];

        foreach ($services as $service) {
            Service::create(array_merge([
                'slug' => Str::slug($service['name']),
                'promotion' => 10,
                'required_tests' => null,
                'preparation_instructions' => null,
                'clinic_id' => rand(1, 3)
            ], $service));
        }
    }
}
