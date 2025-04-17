<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, let's clear existing categories
        Category::truncate();

        // Create parent categories first
        $parentCategories = [
            [
                'name' => 'Chăm sóc da',
                'icon' => 'fas fa-spa',
                'description' => 'Các dịch vụ chăm sóc da chuyên sâu',
                'status' => 'active',
                'parent_id' => null
            ],
            [
                'name' => 'Làm đẹp',
                'icon' => 'fas fa-magic',
                'description' => 'Các dịch vụ làm đẹp tổng thể',
                'status' => 'active',
                'parent_id' => null
            ],
            [
                'name' => 'Tóc',
                'icon' => 'fas fa-cut',
                'description' => 'Các dịch vụ chăm sóc và trang điểm tóc',
                'status' => 'active',
                'parent_id' => null
            ],
            [
                'name' => 'Massage & Spa',
                'icon' => 'fas fa-hands',
                'description' => 'Các dịch vụ massage và spa thư giãn',
                'status' => 'active',
                'parent_id' => null
            ]
        ];

        $parentIds = [];
        foreach ($parentCategories as $category) {
            $newCategory = Category::create($category);
            $parentIds[$category['name']] = $newCategory->id;
        }

        // Now create child categories
        $childCategories = [
            [
                'name' => 'Trị mụn',
                'icon' => 'fas fa-face-smile',
                'description' => 'Các dịch vụ trị mụn chuyên sâu',
                'status' => 'active',
                'parent_id' => $parentIds['Chăm sóc da']
            ],
            [
                'name' => 'Trị nám, tàn nhang',
                'icon' => 'fas fa-sun',
                'description' => 'Dịch vụ trị nám, tàn nhang hiệu quả',
                'status' => 'active',
                'parent_id' => $parentIds['Chăm sóc da']
            ],
            [
                'name' => 'Trẻ hóa da',
                'icon' => 'fas fa-clock-rotate-left',
                'description' => 'Dịch vụ trẻ hóa da, chống lão hóa',
                'status' => 'active',
                'parent_id' => $parentIds['Chăm sóc da']
            ],
            [
                'name' => 'Trang điểm',
                'icon' => 'fas fa-paint-brush',
                'description' => 'Dịch vụ trang điểm chuyên nghiệp',
                'status' => 'active',
                'parent_id' => $parentIds['Làm đẹp']
            ],
            [
                'name' => 'Làm móng',
                'icon' => 'fas fa-hand-sparkles',
                'description' => 'Dịch vụ làm móng tay, móng chân',
                'status' => 'active',
                'parent_id' => $parentIds['Làm đẹp']
            ],
            [
                'name' => 'Phun xăm',
                'icon' => 'fas fa-pen-fancy',
                'description' => 'Dịch vụ phun xăm chân mày, môi, mắt',
                'status' => 'active',
                'parent_id' => $parentIds['Làm đẹp']
            ],
            [
                'name' => 'Uốn tóc',
                'icon' => 'fas fa-wind',
                'description' => 'Dịch vụ uốn tóc đa dạng kiểu',
                'status' => 'active',
                'parent_id' => $parentIds['Tóc']
            ],
            [
                'name' => 'Nhuộm tóc',
                'icon' => 'fas fa-palette',
                'description' => 'Dịch vụ nhuộm tóc đổi màu',
                'status' => 'active',
                'parent_id' => $parentIds['Tóc']
            ],
            [
                'name' => 'Massage body',
                'icon' => 'fas fa-user',
                'description' => 'Dịch vụ massage toàn thân',
                'status' => 'active',
                'parent_id' => $parentIds['Massage & Spa']
            ],
            [
                'name' => 'Massage mặt',
                'icon' => 'fas fa-face-smile',
                'description' => 'Dịch vụ massage mặt trẻ hóa',
                'status' => 'active',
                'parent_id' => $parentIds['Massage & Spa']
            ]
        ];

        foreach ($childCategories as $category) {
            Category::create($category);
        }
    }
}
