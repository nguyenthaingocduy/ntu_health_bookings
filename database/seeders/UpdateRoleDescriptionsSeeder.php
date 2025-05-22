<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\Schema;

class UpdateRoleDescriptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra xem cột description có tồn tại không
        if (!Schema::hasColumn('roles', 'description')) {
            $this->command->info('Cột description chưa tồn tại trong bảng roles.');
            return;
        }

        // Định nghĩa mô tả cho các vai trò
        $roleDescriptions = [
            'Admin' => 'Quản trị viên hệ thống với toàn quyền quản lý',
            'Customer' => 'Khách hàng sử dụng dịch vụ của spa',
            'Employee' => 'Nhân viên làm việc tại spa',
            'Receptionist' => 'Lễ tân - Quản lý lịch hẹn, hỗ trợ khách hàng tại quầy, cập nhật thông tin cơ bản',
            'Technician' => 'Nhân viên kỹ thuật - Thực hiện dịch vụ chăm sóc, theo dõi lịch trình chăm sóc khách hàng',
            'Staff' => 'Nhân viên chung của hệ thống',
        ];

        // Cập nhật mô tả cho các vai trò
        foreach ($roleDescriptions as $roleName => $description) {
            $role = Role::where('name', $roleName)->first();
            
            if ($role) {
                // Chỉ cập nhật nếu chưa có mô tả hoặc mô tả trống
                if (empty($role->description)) {
                    $role->update(['description' => $description]);
                    $this->command->info("Đã cập nhật mô tả cho vai trò: {$roleName}");
                } else {
                    $this->command->info("Vai trò {$roleName} đã có mô tả: {$role->description}");
                }
            } else {
                $this->command->warn("Không tìm thấy vai trò: {$roleName}");
            }
        }
    }
}
