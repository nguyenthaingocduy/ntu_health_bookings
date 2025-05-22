<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class CleanupDuplicateRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Bắt đầu dọn dẹp các vai trò trùng lặp...');

        // Lấy tất cả các vai trò nhóm theo tên
        $roleGroups = Role::all()->groupBy('name');

        foreach ($roleGroups as $roleName => $roles) {
            if ($roles->count() > 1) {
                $this->command->info("Tìm thấy {$roles->count()} vai trò trùng lặp cho: {$roleName}");
                
                // Tìm vai trò có mô tả (ưu tiên giữ lại)
                $roleWithDescription = $roles->whereNotNull('description')->first();
                $roleToKeep = $roleWithDescription ?: $roles->first();
                
                $this->command->info("Giữ lại vai trò ID: {$roleToKeep->id} với mô tả: " . ($roleToKeep->description ?: 'Không có'));
                
                // Lấy danh sách các vai trò cần xóa
                $rolesToDelete = $roles->where('id', '!=', $roleToKeep->id);
                
                foreach ($rolesToDelete as $roleToDelete) {
                    $this->command->info("Đang xử lý vai trò trùng lặp ID: {$roleToDelete->id}");
                    
                    // Cập nhật tất cả users đang sử dụng vai trò này
                    $usersCount = User::where('role_id', $roleToDelete->id)->count();
                    if ($usersCount > 0) {
                        User::where('role_id', $roleToDelete->id)->update(['role_id' => $roleToKeep->id]);
                        $this->command->info("Đã cập nhật {$usersCount} users từ vai trò {$roleToDelete->id} sang {$roleToKeep->id}");
                    }
                    
                    // Cập nhật tất cả employees đang sử dụng vai trò này
                    $employeesCount = Employee::where('role_id', $roleToDelete->id)->count();
                    if ($employeesCount > 0) {
                        Employee::where('role_id', $roleToDelete->id)->update(['role_id' => $roleToKeep->id]);
                        $this->command->info("Đã cập nhật {$employeesCount} employees từ vai trò {$roleToDelete->id} sang {$roleToKeep->id}");
                    }
                    
                    // Cập nhật role_permissions
                    $permissionsCount = DB::table('role_permissions')->where('role_id', $roleToDelete->id)->count();
                    if ($permissionsCount > 0) {
                        // Xóa các quyền trùng lặp trước khi chuyển
                        DB::table('role_permissions')
                            ->where('role_id', $roleToDelete->id)
                            ->whereIn('permission_id', function($query) use ($roleToKeep) {
                                $query->select('permission_id')
                                      ->from('role_permissions')
                                      ->where('role_id', $roleToKeep->id);
                            })
                            ->delete();
                            
                        // Chuyển các quyền còn lại
                        DB::table('role_permissions')
                            ->where('role_id', $roleToDelete->id)
                            ->update(['role_id' => $roleToKeep->id]);
                            
                        $this->command->info("Đã cập nhật quyền từ vai trò {$roleToDelete->id} sang {$roleToKeep->id}");
                    }
                    
                    // Xóa vai trò trùng lặp
                    $roleToDelete->delete();
                    $this->command->info("Đã xóa vai trò trùng lặp ID: {$roleToDelete->id}");
                }
                
                // Cập nhật mô tả nếu vai trò được giữ lại chưa có mô tả
                if (!$roleToKeep->description) {
                    $descriptions = [
                        'Admin' => 'Quản trị viên hệ thống với toàn quyền quản lý',
                        'Customer' => 'Khách hàng sử dụng dịch vụ của spa',
                        'Employee' => 'Nhân viên làm việc tại spa',
                        'Receptionist' => 'Lễ tân - Quản lý lịch hẹn, hỗ trợ khách hàng tại quầy, cập nhật thông tin cơ bản',
                        'Technician' => 'Nhân viên kỹ thuật - Thực hiện dịch vụ chăm sóc, theo dõi lịch trình chăm sóc khách hàng',
                        'Staff' => 'Nhân viên chung của hệ thống',
                    ];
                    
                    if (isset($descriptions[$roleName])) {
                        $roleToKeep->update(['description' => $descriptions[$roleName]]);
                        $this->command->info("Đã cập nhật mô tả cho vai trò: {$roleName}");
                    }
                }
            }
        }
        
        $this->command->info('Hoàn thành dọn dẹp các vai trò trùng lặp!');
    }
}
