<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixDuplicatePermissions extends Migration
{
    /**
     * Run the migration.
     *
     * @return void
     */
    public function up()
    {
        // Danh sách các quyền bị trùng lặp
        $duplicatePermissions = [
            'appointments.delete',
            'users.delete',
            'roles.edit',
            'services.delete',
            'services.edit',
            'appointments.view',
            'users.create',
            'users.edit',
            'roles.create',
            'appointments.edit',
            'services.create',
            'users.view',
            'roles.view',
            'roles.delete',
            'services.view',
        ];

        foreach ($duplicatePermissions as $permissionName) {
            // Lấy tất cả các quyền có cùng tên
            $permissions = DB::table('permissions')
                ->where('name', $permissionName)
                ->orderBy('created_at', 'asc')
                ->get();

            if ($permissions->count() > 1) {
                // Lấy quyền có display_name không null (quyền mới hơn, có nhóm cụ thể)
                $newPermission = $permissions->firstWhere('display_name', '!=', null);
                
                // Lấy quyền cũ (không có display_name, thuộc nhóm 'general')
                $oldPermission = $permissions->firstWhere('display_name', null);

                if ($newPermission && $oldPermission) {
                    // Cập nhật các bản ghi trong bảng user_permissions
                    DB::table('user_permissions')
                        ->where('permission_id', $oldPermission->id)
                        ->update(['permission_id' => $newPermission->id]);

                    // Cập nhật các bản ghi trong bảng role_permissions
                    DB::table('role_permissions')
                        ->where('permission_id', $oldPermission->id)
                        ->update(['permission_id' => $newPermission->id]);

                    // Xóa quyền cũ
                    DB::table('permissions')
                        ->where('id', $oldPermission->id)
                        ->delete();

                    Log::info("Đã xóa quyền trùng lặp: {$permissionName}, ID cũ: {$oldPermission->id}, ID mới: {$newPermission->id}");
                }
            }
        }
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        // Không thể khôi phục lại các quyền đã bị xóa
    }
}
