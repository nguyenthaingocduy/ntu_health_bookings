<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\CustomerType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tìm hoặc tạo role admin
        $adminRole = Role::where('name', 'Admin')->first();
        
        if (!$adminRole) {
            $adminRole = Role::create([
                'id' => Str::uuid(),
                'name' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Tìm hoặc tạo loại khách hàng mặc định
        $defaultType = CustomerType::where('type_name', 'regular')->first();
        
        if (!$defaultType) {
            $defaultType = CustomerType::create([
                'id' => Str::uuid(),
                'type_name' => 'regular',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Tạo tài khoản admin nếu chưa tồn tại
        $adminExists = User::where('email', 'admin@gmail.com')->exists();
        
        if (!$adminExists) {
            User::create([
                'id' => Str::uuid(), // Thêm ID nếu bảng User sử dụng UUID
                'first_name' => 'Admin',
                'last_name' => 'System',
                'email' => 'admin@gmail.com',
                'phone' => '0123456789',
                'address' => 'Nha Trang',
                'gender' => 'male',
                'password' => Hash::make('123456'),
                'role_id' => $adminRole->id,
                'type_id' => $defaultType->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
