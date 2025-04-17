<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClinicAndServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo dữ liệu mẫu cho clinics
        $clinics = [
            [
                'name' => 'Beauty Spa Hà Nội',
                'address' => '123 Đường Lê Duẩn, Quận Đống Đa, Hà Nội',
                'phone' => '024-1234-5678',
                'email' => 'hanoi@beautyspa.com',
                'description' => 'Trung tâm làm đẹp cao cấp tại Hà Nội với đội ngũ chuyên gia giàu kinh nghiệm.',
                'image_url' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
            ],
            [
                'name' => 'Beauty Spa Hồ Chí Minh',
                'address' => '456 Đường Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh',
                'phone' => '028-8765-4321',
                'email' => 'hcmc@beautyspa.com',
                'description' => 'Trung tâm làm đẹp hàng đầu tại TP. Hồ Chí Minh với công nghệ hiện đại.',
                'image_url' => 'https://images.unsplash.com/photo-1560750588-73207b1ef5b8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
            ],
            [
                'name' => 'Beauty Spa Đà Nẵng',
                'address' => '789 Đường Trần Phú, Quận Hải Châu, Đà Nẵng',
                'phone' => '0236-9876-5432',
                'email' => 'danang@beautyspa.com',
                'description' => 'Trung tâm làm đẹp chất lượng cao tại Đà Nẵng với dịch vụ đa dạng.',
                'image_url' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
            ],
        ];

        $clinicIds = [];
        foreach ($clinics as $clinic) {
            // Debug: In ra thông tin clinic trước khi tạo
            dump('Creating clinic:', $clinic);

            $newClinic = Clinic::create([
                'name' => $clinic['name'],
                'address' => $clinic['address'],
                'phone' => $clinic['phone'],
                'email' => $clinic['email'],
                'description' => $clinic['description'],
                'image_url' => $clinic['image_url'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Debug: In ra thông tin clinic sau khi tạo
            dump('Created clinic:', $newClinic->toArray());

            $clinicIds[] = $newClinic->id;
        }

        // Debug: In ra danh sách clinic_ids
        dump('Clinic IDs:', $clinicIds);

        // Lấy danh sách category IDs
        $categoryIds = [];
        $categories = \App\Models\Category::all();
        foreach ($categories as $category) {
            $categoryIds[$category->name] = $category->id;
        }

        // Debug: In ra danh sách category_ids
        dump('Category IDs:', $categoryIds);

        // Tạo dữ liệu mẫu cho services
        $services = [
            [
                'name' => 'Massage Thư Giãn',
                'slug' => 'massage-thu-gian',
                'description' => 'Liệu pháp massage thư giãn giúp giảm căng thẳng, mệt mỏi và cải thiện lưu thông máu.',
                'promotion' => 10,
                'price' => 500000,
                'duration' => 60,
                'image_url' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 1,
                'category_id' => $categoryIds['Massage body'] ?? null,
            ],
            [
                'name' => 'Chăm Sóc Da Mặt Cơ Bản',
                'slug' => 'cham-soc-da-mat-co-ban',
                'description' => 'Liệu pháp chăm sóc da mặt cơ bản giúp làm sạch, dưỡng ẩm và trẻ hóa làn da.',
                'promotion' => 15,
                'price' => 750000,
                'duration' => 90,
                'image_url' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 1,
                'category_id' => $categoryIds['Chăm sóc da'] ?? null,
            ],
            [
                'name' => 'Trị Mụn Chuyên Sâu',
                'slug' => 'tri-mun-chuyen-sau',
                'description' => 'Liệu pháp trị mụn chuyên sâu giúp loại bỏ mụn, ngăn ngừa tái phát và cải thiện làn da.',
                'promotion' => 25,
                'price' => 950000,
                'duration' => 120,
                'image_url' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 1,
                'category_id' => $categoryIds['Trị mụn'] ?? null,
            ],
            [
                'name' => 'Trị Nám Tàn Nhang',
                'slug' => 'tri-nam-tan-nhang',
                'description' => 'Liệu pháp trị nám tàn nhang hiệu quả, giúp làn da đều màu và sáng hơn.',
                'promotion' => 20,
                'price' => 1200000,
                'duration' => 90,
                'image_url' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 2,
                'category_id' => $categoryIds['Trị nám, tàn nhang'] ?? null,
            ],
            [
                'name' => 'Trang Điểm Cô Dâu',
                'slug' => 'trang-diem-co-dau',
                'description' => 'Dịch vụ trang điểm cô dâu chuyên nghiệp, tạo nên vẻ đẹp lộng lẫy trong ngày cưới.',
                'promotion' => 10,
                'price' => 2500000,
                'duration' => 120,
                'image_url' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 2,
                'category_id' => $categoryIds['Trang điểm'] ?? null,
            ],
            [
                'name' => 'Làm Móng Gel',
                'slug' => 'lam-mong-gel',
                'description' => 'Dịch vụ làm móng gel bền đẹp, nhiều mẫu mã đa dạng.',
                'promotion' => 15,
                'price' => 350000,
                'duration' => 60,
                'image_url' => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 2,
                'category_id' => $categoryIds['Làm móng'] ?? null,
            ],
            [
                'name' => 'Uốn Tóc Cao Cấp',
                'slug' => 'uon-toc-cao-cap',
                'description' => 'Dịch vụ uốn tóc cao cấp với nhiều kiểu dáng phù hợp với khuôn mặt.',
                'promotion' => 10,
                'price' => 1500000,
                'duration' => 180,
                'image_url' => 'https://images.unsplash.com/photo-1562322140-8baeececf3df?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 3,
                'category_id' => $categoryIds['Uốn tóc'] ?? null,
            ],
            [
                'name' => 'Nhuộm Tóc Thời Trang',
                'slug' => 'nhuom-toc-thoi-trang',
                'description' => 'Dịch vụ nhuộm tóc với nhiều màu sắc thời trang, phù hợp với xu hướng.',
                'promotion' => 15,
                'price' => 1200000,
                'duration' => 120,
                'image_url' => 'https://images.unsplash.com/photo-1605497788044-5a32c7078486?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 3,
                'category_id' => $categoryIds['Nhuộm tóc'] ?? null,
            ],
            [
                'name' => 'Phun Xăm Chân Mày',
                'slug' => 'phun-xam-chan-may',
                'description' => 'Dịch vụ phun xăm chân mày tự nhiên, phù hợp với khuôn mặt.',
                'promotion' => 5,
                'price' => 2000000,
                'duration' => 120,
                'image_url' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 3,
                'category_id' => $categoryIds['Phun xăm'] ?? null,
            ],
            [
                'name' => 'Massage Mặt Trẻ Hóa',
                'slug' => 'massage-mat-tre-hoa',
                'description' => 'Liệu pháp massage mặt giúp trẻ hóa làn da, giảm nếp nhăn và tăng cường sự đàn hồi.',
                'promotion' => 20,
                'price' => 800000,
                'duration' => 60,
                'image_url' => 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 1,
                'category_id' => $categoryIds['Massage mặt'] ?? null,
            ],
        ];

        foreach ($services as $service) {
            // Debug: In ra thông tin dịch vụ trước khi tạo
            dump('Creating service:', $service);

            try {
                Service::create($service);
                dump('Service created successfully');
            } catch (\Exception $e) {
                dump('Error creating service:', $e->getMessage());
            }
        }
    }
}
