<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id' => Str::uuid(),
                'name' => 'Receptionist',
                'description' => 'Quản lý lịch hẹn, hỗ trợ khách hàng tại quầy, cập nhật thông tin cơ bản',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Technician',
                'description' => 'Thực hiện dịch vụ chăm sóc, theo dõi lịch trình chăm sóc khách hàng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
