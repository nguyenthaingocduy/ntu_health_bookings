<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa tất cả các phân quyền hiện có
        DB::table('role_permissions')->truncate();
        DB::table('user_permissions')->truncate();

        // Lấy các vai trò
        $adminRole = Role::where('name', 'Admin')->first();
        $receptionistRole = Role::where('name', 'Receptionist')->first();
        $technicianRole = Role::where('name', 'Technician')->first();

        // Nếu không tìm thấy vai trò, tạo mới
        if (!$adminRole) {
            $adminRole = Role::create([
                'id' => Str::uuid(),
                'name' => 'Admin',
                'description' => 'Quản trị viên hệ thống',
            ]);
        }

        if (!$receptionistRole) {
            $receptionistRole = Role::create([
                'id' => Str::uuid(),
                'name' => 'Receptionist',
                'description' => 'Lễ tân',
            ]);
        }

        if (!$technicianRole) {
            $technicianRole = Role::create([
                'id' => Str::uuid(),
                'name' => 'Technician',
                'description' => 'Nhân viên kỹ thuật',
            ]);
        }

        // Lấy tất cả các quyền
        $allPermissions = Permission::all();

        // Phân quyền cho Admin - tất cả các quyền
        foreach ($allPermissions as $permission) {
            DB::table('role_permissions')->insert([
                'id' => Str::uuid(),
                'role_id' => $adminRole->id,
                'permission_id' => $permission->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Phân quyền cho Lễ tân
        $receptionistPermissions = [
            'customers.view',
            'customers.view_limited',
            'customers.create',
            'customers.edit',
            'appointments.view',
            'appointments.create',
            'appointments.edit',
            'appointments.cancel',
            'payments.view',
            'payments.create',
            'services.view',
        ];

        foreach ($receptionistPermissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                DB::table('role_permissions')->insert([
                    'id' => Str::uuid(),
                    'role_id' => $receptionistRole->id,
                    'permission_id' => $permission->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Phân quyền cho Nhân viên kỹ thuật
        $technicianPermissions = [
            'work_schedule.view',
            'treatment_progress.view',
            'treatment_progress.update',
            'session_status.update',
            'professional_notes.view',
            'professional_notes.create',
            'professional_notes.edit',
            'customers.view_limited',
            'appointments.view',
        ];

        foreach ($technicianPermissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                DB::table('role_permissions')->insert([
                    'id' => Str::uuid(),
                    'role_id' => $technicianRole->id,
                    'permission_id' => $permission->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
