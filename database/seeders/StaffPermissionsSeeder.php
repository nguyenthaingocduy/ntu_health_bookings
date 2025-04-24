<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StaffPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo các quyền cho Receptionist
        $receptionistPermissions = [
            // Quản lý lịch hẹn
            [
                'id' => Str::uuid(),
                'name' => 'appointments.view',
                'display_name' => 'Xem lịch hẹn',
                'description' => 'Xem danh sách lịch hẹn của khách hàng',
                'group' => 'Lịch hẹn',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'appointments.create',
                'display_name' => 'Tạo lịch hẹn',
                'description' => 'Tạo lịch hẹn mới cho khách hàng',
                'group' => 'Lịch hẹn',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'appointments.edit',
                'display_name' => 'Chỉnh sửa lịch hẹn',
                'description' => 'Chỉnh sửa thông tin lịch hẹn',
                'group' => 'Lịch hẹn',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'appointments.cancel',
                'display_name' => 'Hủy lịch hẹn',
                'description' => 'Hủy lịch hẹn của khách hàng',
                'group' => 'Lịch hẹn',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Quản lý khách hàng
            [
                'id' => Str::uuid(),
                'name' => 'customers.view',
                'display_name' => 'Xem thông tin khách hàng',
                'description' => 'Xem thông tin cơ bản của khách hàng',
                'group' => 'Khách hàng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'customers.create',
                'display_name' => 'Tạo khách hàng mới',
                'description' => 'Tạo thông tin khách hàng mới',
                'group' => 'Khách hàng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'customers.edit',
                'display_name' => 'Chỉnh sửa thông tin khách hàng',
                'description' => 'Chỉnh sửa thông tin cơ bản của khách hàng',
                'group' => 'Khách hàng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Quản lý dịch vụ
            [
                'id' => Str::uuid(),
                'name' => 'services.view',
                'display_name' => 'Xem dịch vụ',
                'description' => 'Xem danh sách dịch vụ',
                'group' => 'Dịch vụ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'service_packages.register',
                'display_name' => 'Đăng ký gói dịch vụ',
                'description' => 'Đăng ký gói dịch vụ cho khách hàng',
                'group' => 'Dịch vụ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Quản lý thanh toán
            [
                'id' => Str::uuid(),
                'name' => 'payments.create',
                'display_name' => 'Ghi nhận thanh toán',
                'description' => 'Ghi nhận thanh toán từ khách hàng',
                'group' => 'Thanh toán',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'payments.view',
                'display_name' => 'Xem thanh toán',
                'description' => 'Xem lịch sử thanh toán',
                'group' => 'Thanh toán',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Quản lý thông báo
            [
                'id' => Str::uuid(),
                'name' => 'notifications.send',
                'display_name' => 'Gửi thông báo',
                'description' => 'Gửi thông báo/nhắc lịch cho khách hàng',
                'group' => 'Thông báo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Tạo các quyền cho Technician
        $technicianPermissions = [
            // Lịch làm việc
            [
                'id' => Str::uuid(),
                'name' => 'work_schedule.view',
                'display_name' => 'Xem lịch làm việc',
                'description' => 'Xem lịch làm việc cá nhân',
                'group' => 'Lịch làm việc',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Quản lý tiến trình
            [
                'id' => Str::uuid(),
                'name' => 'treatment_progress.update',
                'display_name' => 'Cập nhật tiến trình',
                'description' => 'Cập nhật tiến trình thực hiện dịch vụ',
                'group' => 'Tiến trình',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'treatment_progress.view',
                'display_name' => 'Xem tiến trình',
                'description' => 'Xem tiến trình thực hiện dịch vụ',
                'group' => 'Tiến trình',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Ghi chú chuyên môn
            [
                'id' => Str::uuid(),
                'name' => 'professional_notes.create',
                'display_name' => 'Tạo ghi chú chuyên môn',
                'description' => 'Tạo ghi chú chuyên môn cho khách hàng',
                'group' => 'Ghi chú',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'professional_notes.view',
                'display_name' => 'Xem ghi chú chuyên môn',
                'description' => 'Xem ghi chú chuyên môn của khách hàng',
                'group' => 'Ghi chú',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'professional_notes.edit',
                'display_name' => 'Chỉnh sửa ghi chú chuyên môn',
                'description' => 'Chỉnh sửa ghi chú chuyên môn của khách hàng',
                'group' => 'Ghi chú',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Cập nhật trạng thái
            [
                'id' => Str::uuid(),
                'name' => 'session_status.update',
                'display_name' => 'Cập nhật trạng thái buổi chăm sóc',
                'description' => 'Cập nhật trạng thái hoàn thành của buổi chăm sóc',
                'group' => 'Trạng thái',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Xem thông tin khách hàng (giới hạn)
            [
                'id' => Str::uuid(),
                'name' => 'customers.view_limited',
                'display_name' => 'Xem thông tin khách hàng (giới hạn)',
                'description' => 'Xem thông tin cơ bản của khách hàng được phân công',
                'group' => 'Khách hàng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Lưu tất cả quyền vào cơ sở dữ liệu
        $allPermissions = array_merge($receptionistPermissions, $technicianPermissions);
        foreach ($allPermissions as $permission) {
            Permission::create($permission);
        }
        
        // Gán quyền cho vai trò Receptionist
        $receptionistRole = Role::where('name', 'Receptionist')->first();
        if ($receptionistRole) {
            foreach ($receptionistPermissions as $permission) {
                $permissionModel = Permission::where('name', $permission['name'])->first();
                if ($permissionModel) {
                    RolePermission::create([
                        'id' => Str::uuid(),
                        'role_id' => $receptionistRole->id,
                        'permission_id' => $permissionModel->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        
        // Gán quyền cho vai trò Technician
        $technicianRole = Role::where('name', 'Technician')->first();
        if ($technicianRole) {
            foreach ($technicianPermissions as $permission) {
                $permissionModel = Permission::where('name', $permission['name'])->first();
                if ($permissionModel) {
                    RolePermission::create([
                        'id' => Str::uuid(),
                        'role_id' => $technicianRole->id,
                        'permission_id' => $permissionModel->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
