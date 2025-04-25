<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Support\Str;

class UpdateAdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tìm vai trò Admin
        $adminRole = Role::where('name', 'Admin')->first();

        if (!$adminRole) {
            $this->command->error('Không tìm thấy vai trò Admin');
            return;
        }

        // Tìm quyền promotions.create
        $promotionsCreatePermission = Permission::where('name', 'promotions.create')->first();

        if (!$promotionsCreatePermission) {
            $this->command->error('Không tìm thấy quyền promotions.create');
            return;
        }

        // Kiểm tra xem quyền đã được gán cho vai trò Admin chưa
        $exists = RolePermission::where('role_id', $adminRole->id)
            ->where('permission_id', $promotionsCreatePermission->id)
            ->exists();

        if (!$exists) {
            // Gán quyền cho vai trò Admin
            RolePermission::create([
                'id' => Str::uuid(),
                'role_id' => $adminRole->id,
                'permission_id' => $promotionsCreatePermission->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info('Đã gán quyền promotions.create cho vai trò Admin');
        } else {
            $this->command->info('Quyền promotions.create đã được gán cho vai trò Admin');
        }

        // Gán tất cả quyền liên quan đến khuyến mãi cho vai trò Admin
        $promotionPermissions = Permission::where('group', 'promotions')->get();

        foreach ($promotionPermissions as $permission) {
            $exists = RolePermission::where('role_id', $adminRole->id)
                ->where('permission_id', $permission->id)
                ->exists();

            if (!$exists) {
                RolePermission::create([
                    'id' => Str::uuid(),
                    'role_id' => $adminRole->id,
                    'permission_id' => $permission->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->command->info("Đã gán quyền {$permission->name} cho vai trò Admin");
            } else {
                $this->command->info("Quyền {$permission->name} đã được gán cho vai trò Admin");
            }
        }
    }
}
