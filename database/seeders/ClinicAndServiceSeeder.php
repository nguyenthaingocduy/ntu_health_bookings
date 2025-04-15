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

        // Tạo dữ liệu mẫu cho services
        $services = [
            [
                'name' => 'Massage Thư Giãn',
                'slug' => 'massage-thu-gian',
                'description' => 'Liệu pháp massage thư giãn giúp giảm căng thẳng, mệt mỏi và cải thiện lưu thông máu.',
                'promotion' => 10,
                'price' => 500000,
                'image_url' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 1,
                'category_id' => null,
            ],
            [
                'name' => 'Chăm Sóc Da Mặt',
                'slug' => 'cham-soc-da-mat',
                'description' => 'Liệu pháp chăm sóc da mặt chuyên sâu giúp làm sạch, dưỡng ẩm và trẻ hóa làn da.',
                'promotion' => 15,
                'price' => 750000,
                'image_url' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 1,
                'category_id' => null,
            ],
            [
                'name' => 'Tắm Thảo Dược',
                'slug' => 'tam-thao-duoc',
                'description' => 'Liệu pháp tắm thảo dược giúp thanh lọc cơ thể, thư giãn và cải thiện sức khỏe.',
                'promotion' => 5,
                'price' => 850000,
                'image_url' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 2,
                'category_id' => null,
            ],
            [
                'name' => 'Massage Chân',
                'slug' => 'massage-chan',
                'description' => 'Liệu pháp massage chân giúp thư giãn, giảm đau và cải thiện lưu thông máu.',
                'promotion' => 20,
                'price' => 450000,
                'image_url' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 2,
                'category_id' => null,
            ],
            [
                'name' => 'Trị Mụn Chuyên Sâu',
                'slug' => 'tri-mun-chuyen-sau',
                'description' => 'Liệu pháp trị mụn chuyên sâu giúp loại bỏ mụn, ngăn ngừa tái phát và cải thiện làn da.',
                'promotion' => 25,
                'price' => 950000,
                'image_url' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 3,
                'category_id' => null,
            ],
            [
                'name' => 'Massage Toàn Thân',
                'slug' => 'massage-toan-than',
                'description' => 'Liệu pháp massage toàn thân giúp thư giãn, giảm căng thẳng và cải thiện sức khỏe tổng thể.',
                'promotion' => 15,
                'price' => 1200000,
                'image_url' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'status' => 'active',
                'clinic_id' => 3,
                'category_id' => null,
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
