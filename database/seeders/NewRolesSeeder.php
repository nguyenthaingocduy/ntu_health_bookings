<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class NewRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra xem cột description có tồn tại không
        $hasDescriptionColumn = Schema::hasColumn('roles', 'description');

        $roles = [
            [
                'id' => Str::uuid(),
                'name' => 'Receptionist',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Technician',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Thêm mô tả nếu cột tồn tại
        if ($hasDescriptionColumn) {
            $roles[0]['description'] = 'Quản lý lịch hẹn, hỗ trợ khách hàng tại quầy, cập nhật thông tin cơ bản';
            $roles[1]['description'] = 'Thực hiện dịch vụ chăm sóc, theo dõi lịch trình chăm sóc khách hàng';
        }

        foreach ($roles as $role) {
            // Kiểm tra xem vai trò đã tồn tại chưa
            $existingRole = Role::where('name', $role['name'])->first();
            if (!$existingRole) {
                Role::create($role);
                $this->command->info("Đã tạo vai trò {$role['name']}");
            } else {
                $this->command->info("Vai trò {$role['name']} đã tồn tại");
            }
        }
    }
}
