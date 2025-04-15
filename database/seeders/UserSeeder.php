<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\CustomerType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tìm hoặc tạo role Admin
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['id' => Str::uuid()]
        );

        // Tìm hoặc tạo role Customer
        $customerRole = Role::firstOrCreate(
            ['name' => 'Customer'],
            ['id' => Str::uuid()]
        );

        // Tìm hoặc tạo customer type Regular
        $regularType = CustomerType::firstOrCreate(
            ['type_name' => 'Regular'],
            ['id' => Str::uuid()]
        );

        // Tìm hoặc tạo customer type VIP
        $vipType = CustomerType::firstOrCreate(
            ['type_name' => 'VIP'],
            ['id' => Str::uuid()]
        );

        // Tạo user Admin nếu chưa tồn tại
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'id' => Str::uuid(),
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'phone' => '0123456789',
                'address' => 'Admin Address',
                'gender' => 'Male',
                'role_id' => $adminRole->id,
                'type_id' => $regularType->id,
                'province_id' => '1',
                'district_id' => '1',
                'ward_id' => '1',
            ]);
        }

        // Tạo user Customer nếu chưa tồn tại
        if (!User::where('email', 'customer@example.com')->exists()) {
            User::create([
                'id' => Str::uuid(),
                'first_name' => 'Customer',
                'last_name' => 'User',
                'email' => 'customer@example.com',
                'password' => Hash::make('password'),
                'phone' => '0987654321',
                'address' => 'Customer Address',
                'gender' => 'Female',
                'role_id' => $customerRole->id,
                'type_id' => $regularType->id,
                'province_id' => '1',
                'district_id' => '1',
                'ward_id' => '1',
            ]);
        }
    }
}
