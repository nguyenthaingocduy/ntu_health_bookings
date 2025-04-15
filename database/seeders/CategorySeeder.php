<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Phun xăm',
                'icon' => 'fas fa-spa',
                'description' => 'Các dịch vụ phun xăm thẩm mỹ'
            ],
            [
                'name' => 'Điều trị da',
                'icon' => 'fas fa-face-smile',
                'description' => 'Các dịch vụ điều trị da chuyên sâu'
            ],
            [
                'name' => 'Giảm mỡ',
                'icon' => 'fas fa-weight-scale',
                'description' => 'Các dịch vụ giảm mỡ, làm thon gọn cơ thể'
            ],
            [
                'name' => 'Triệt lông',
                'icon' => 'fas fa-feather',
                'description' => 'Các dịch vụ triệt lông vĩnh viễn'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
