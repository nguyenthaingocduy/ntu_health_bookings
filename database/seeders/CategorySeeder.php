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
                'name' => 'Chăm sóc tóc',
                'icon' => 'fas fa-cut',
                'description' => 'Các dịch vụ chăm sóc và tạo kiểu tóc',
                'status' => 'active',
                'parent_id' => null
            ],
            [
                'name' => 'Chăm sóc móng',
                'icon' => 'fas fa-hand-sparkles',
                'description' => 'Các dịch vụ làm móng tay, móng chân',
                'status' => 'active',
                'parent_id' => null
            ],
            [
                'name' => 'Trang điểm',
                'icon' => 'fas fa-magic',
                'description' => 'Các dịch vụ trang điểm chuyên nghiệp',
                'status' => 'active',
                'parent_id' => null
            ],
            [
                'name' => 'Massage & Spa',
                'icon' => 'fas fa-hands',
                'description' => 'Các dịch vụ massage và spa thư giãn',
                'status' => 'active',
                'parent_id' => null
            ],
            [
                'name' => 'Gói dịch vụ',
                'icon' => 'fas fa-gift',
                'description' => 'Các gói dịch vụ kết hợp nhiều liệu trình',
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
            // Chăm sóc da
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
                'name' => 'Chăm sóc da mặt',
                'icon' => 'fas fa-face-smile',
                'description' => 'Dịch vụ chăm sóc da mặt cơ bản và chuyên sâu',
                'status' => 'active',
                'parent_id' => $parentIds['Chăm sóc da']
            ],
            [
                'name' => 'Tẩy tế bào chết',
                'icon' => 'fas fa-leaf',
                'description' => 'Dịch vụ tẩy tế bào chết cho da mặt và toàn thân',
                'status' => 'active',
                'parent_id' => $parentIds['Chăm sóc da']
            ],

            // Chăm sóc tóc
            [
                'name' => 'Cắt tóc',
                'icon' => 'fas fa-cut',
                'description' => 'Dịch vụ cắt tóc theo xu hướng mới nhất',
                'status' => 'active',
                'parent_id' => $parentIds['Chăm sóc tóc']
            ],
            [
                'name' => 'Nhuộm tóc',
                'icon' => 'fas fa-palette',
                'description' => 'Dịch vụ nhuộm tóc với nhiều màu sắc thời trang',
                'status' => 'active',
                'parent_id' => $parentIds['Chăm sóc tóc']
            ],
            [
                'name' => 'Uốn tóc',
                'icon' => 'fas fa-wind',
                'description' => 'Dịch vụ uốn tóc đa dạng kiểu dáng',
                'status' => 'active',
                'parent_id' => $parentIds['Chăm sóc tóc']
            ],
            [
                'name' => 'Ép tóc',
                'icon' => 'fas fa-ruler-vertical',
                'description' => 'Dịch vụ ép tóc thẳng mượt',
                'status' => 'active',
                'parent_id' => $parentIds['Chăm sóc tóc']
            ],
            [
                'name' => 'Dưỡng tóc',
                'icon' => 'fas fa-tint',
                'description' => 'Dịch vụ dưỡng tóc chuyên sâu',
                'status' => 'active',
                'parent_id' => $parentIds['Chăm sóc tóc']
            ],

            // Chăm sóc móng
            [
                'name' => 'Làm móng tay',
                'icon' => 'fas fa-hand-sparkles',
                'description' => 'Dịch vụ làm móng tay cơ bản và nghệ thuật',
                'status' => 'active',
                'parent_id' => $parentIds['Chăm sóc móng']
            ],
            [
                'name' => 'Làm móng chân',
                'icon' => 'fas fa-shoe-prints',
                'description' => 'Dịch vụ làm móng chân cơ bản và nghệ thuật',
                'status' => 'active',
                'parent_id' => $parentIds['Chăm sóc móng']
            ],
            [
                'name' => 'Sơn gel',
                'icon' => 'fas fa-paint-brush',
                'description' => 'Dịch vụ sơn gel bền đẹp',
                'status' => 'active',
                'parent_id' => $parentIds['Chăm sóc móng']
            ],
            [
                'name' => 'Đắp móng',
                'icon' => 'fas fa-plus',
                'description' => 'Dịch vụ đắp móng giả',
                'status' => 'active',
                'parent_id' => $parentIds['Chăm sóc móng']
            ],

            // Trang điểm
            [
                'name' => 'Trang điểm cô dâu',
                'icon' => 'fas fa-heart',
                'description' => 'Dịch vụ trang điểm cô dâu trong ngày cưới',
                'status' => 'active',
                'parent_id' => $parentIds['Trang điểm']
            ],
            [
                'name' => 'Trang điểm dự tiệc',
                'icon' => 'fas fa-glass-cheers',
                'description' => 'Dịch vụ trang điểm cho các sự kiện đặc biệt',
                'status' => 'active',
                'parent_id' => $parentIds['Trang điểm']
            ],
            [
                'name' => 'Trang điểm nhẹ nhàng',
                'icon' => 'fas fa-feather',
                'description' => 'Dịch vụ trang điểm nhẹ nhàng, tự nhiên',
                'status' => 'active',
                'parent_id' => $parentIds['Trang điểm']
            ],

            // Massage & Spa
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
            ],
            [
                'name' => 'Tắm trắng',
                'icon' => 'fas fa-shower',
                'description' => 'Dịch vụ tắm trắng an toàn',
                'status' => 'active',
                'parent_id' => $parentIds['Massage & Spa']
            ],
            [
                'name' => 'Xông hơi',
                'icon' => 'fas fa-hot-tub',
                'description' => 'Dịch vụ xông hơi thải độc',
                'status' => 'active',
                'parent_id' => $parentIds['Massage & Spa']
            ],

            // Gói dịch vụ
            [
                'name' => 'Gói cô dâu',
                'icon' => 'fas fa-heart',
                'description' => 'Gói dịch vụ trọn vẹn cho cô dâu',
                'status' => 'active',
                'parent_id' => $parentIds['Gói dịch vụ']
            ],
            [
                'name' => 'Gói làm đẹp toàn diện',
                'icon' => 'fas fa-star',
                'description' => 'Gói dịch vụ làm đẹp toàn diện từ đầu đến chân',
                'status' => 'active',
                'parent_id' => $parentIds['Gói dịch vụ']
            ],
            [
                'name' => 'Gói thư giãn',
                'icon' => 'fas fa-peace',
                'description' => 'Gói dịch vụ thư giãn toàn diện',
                'status' => 'active',
                'parent_id' => $parentIds['Gói dịch vụ']
            ]
        ];

        foreach ($childCategories as $category) {
            Category::create($category);
        }
    }
}
