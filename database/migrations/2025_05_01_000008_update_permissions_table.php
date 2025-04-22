<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm cột mới vào bảng permissions
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('display_name')->after('name')->nullable();
            $table->string('description')->after('display_name')->nullable();
            $table->string('group')->after('description')->default('general');
        });
        
        // Thêm các quyền mới
        $permissions = [
            // Quản lý người dùng
            ['name' => 'users.view', 'display_name' => 'Xem người dùng', 'description' => 'Xem danh sách người dùng', 'group' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Thêm người dùng', 'description' => 'Thêm người dùng mới', 'group' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Sửa người dùng', 'description' => 'Sửa thông tin người dùng', 'group' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Xóa người dùng', 'description' => 'Xóa người dùng', 'group' => 'users'],
            
            // Quản lý vai trò
            ['name' => 'roles.view', 'display_name' => 'Xem vai trò', 'description' => 'Xem danh sách vai trò', 'group' => 'roles'],
            ['name' => 'roles.create', 'display_name' => 'Thêm vai trò', 'description' => 'Thêm vai trò mới', 'group' => 'roles'],
            ['name' => 'roles.edit', 'display_name' => 'Sửa vai trò', 'description' => 'Sửa thông tin vai trò', 'group' => 'roles'],
            ['name' => 'roles.delete', 'display_name' => 'Xóa vai trò', 'description' => 'Xóa vai trò', 'group' => 'roles'],
            
            // Quản lý quyền
            ['name' => 'permissions.view', 'display_name' => 'Xem quyền', 'description' => 'Xem danh sách quyền', 'group' => 'permissions'],
            ['name' => 'permissions.assign', 'display_name' => 'Gán quyền', 'description' => 'Gán quyền cho người dùng hoặc vai trò', 'group' => 'permissions'],
            
            // Quản lý dịch vụ
            ['name' => 'services.view', 'display_name' => 'Xem dịch vụ', 'description' => 'Xem danh sách dịch vụ', 'group' => 'services'],
            ['name' => 'services.create', 'display_name' => 'Thêm dịch vụ', 'description' => 'Thêm dịch vụ mới', 'group' => 'services'],
            ['name' => 'services.edit', 'display_name' => 'Sửa dịch vụ', 'description' => 'Sửa thông tin dịch vụ', 'group' => 'services'],
            ['name' => 'services.delete', 'display_name' => 'Xóa dịch vụ', 'description' => 'Xóa dịch vụ', 'group' => 'services'],
            
            // Quản lý lịch hẹn
            ['name' => 'appointments.view', 'display_name' => 'Xem lịch hẹn', 'description' => 'Xem danh sách lịch hẹn', 'group' => 'appointments'],
            ['name' => 'appointments.create', 'display_name' => 'Thêm lịch hẹn', 'description' => 'Thêm lịch hẹn mới', 'group' => 'appointments'],
            ['name' => 'appointments.edit', 'display_name' => 'Sửa lịch hẹn', 'description' => 'Sửa thông tin lịch hẹn', 'group' => 'appointments'],
            ['name' => 'appointments.delete', 'display_name' => 'Xóa lịch hẹn', 'description' => 'Xóa lịch hẹn', 'group' => 'appointments'],
            
            // Quản lý hóa đơn
            ['name' => 'invoices.view', 'display_name' => 'Xem hóa đơn', 'description' => 'Xem danh sách hóa đơn', 'group' => 'invoices'],
            ['name' => 'invoices.create', 'display_name' => 'Tạo hóa đơn', 'description' => 'Tạo hóa đơn mới', 'group' => 'invoices'],
            ['name' => 'invoices.edit', 'display_name' => 'Sửa hóa đơn', 'description' => 'Sửa thông tin hóa đơn', 'group' => 'invoices'],
            ['name' => 'invoices.delete', 'display_name' => 'Xóa hóa đơn', 'description' => 'Xóa hóa đơn', 'group' => 'invoices'],
            ['name' => 'invoices.print', 'display_name' => 'In hóa đơn', 'description' => 'In hóa đơn', 'group' => 'invoices'],
            
            // Quản lý tin tức
            ['name' => 'posts.view', 'display_name' => 'Xem tin tức', 'description' => 'Xem danh sách tin tức', 'group' => 'posts'],
            ['name' => 'posts.create', 'display_name' => 'Thêm tin tức', 'description' => 'Thêm tin tức mới', 'group' => 'posts'],
            ['name' => 'posts.edit', 'display_name' => 'Sửa tin tức', 'description' => 'Sửa thông tin tin tức', 'group' => 'posts'],
            ['name' => 'posts.delete', 'display_name' => 'Xóa tin tức', 'description' => 'Xóa tin tức', 'group' => 'posts'],
            
            // Quản lý khuyến mãi
            ['name' => 'promotions.view', 'display_name' => 'Xem khuyến mãi', 'description' => 'Xem danh sách khuyến mãi', 'group' => 'promotions'],
            ['name' => 'promotions.create', 'display_name' => 'Thêm khuyến mãi', 'description' => 'Thêm khuyến mãi mới', 'group' => 'promotions'],
            ['name' => 'promotions.edit', 'display_name' => 'Sửa khuyến mãi', 'description' => 'Sửa thông tin khuyến mãi', 'group' => 'promotions'],
            ['name' => 'promotions.delete', 'display_name' => 'Xóa khuyến mãi', 'description' => 'Xóa khuyến mãi', 'group' => 'promotions'],
            
            // Cài đặt hệ thống
            ['name' => 'settings.view', 'display_name' => 'Xem cài đặt', 'description' => 'Xem cài đặt hệ thống', 'group' => 'settings'],
            ['name' => 'settings.edit', 'display_name' => 'Sửa cài đặt', 'description' => 'Sửa cài đặt hệ thống', 'group' => 'settings'],
            
            // Báo cáo thống kê
            ['name' => 'reports.view', 'display_name' => 'Xem báo cáo', 'description' => 'Xem báo cáo thống kê', 'group' => 'reports'],
            ['name' => 'reports.export', 'display_name' => 'Xuất báo cáo', 'description' => 'Xuất báo cáo thống kê', 'group' => 'reports'],
        ];
        
        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'id' => Str::uuid(),
                'name' => $permission['name'],
                'display_name' => $permission['display_name'],
                'description' => $permission['description'],
                'group' => $permission['group'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Cấp tất cả quyền cho vai trò Admin
        $adminRole = DB::table('roles')->where('name', 'Admin')->first();
        
        if ($adminRole) {
            $permissions = DB::table('permissions')->get();
            
            foreach ($permissions as $permission) {
                DB::table('role_permissions')->insert([
                    'id' => Str::uuid(),
                    'role_id' => $adminRole->id,
                    'permission_id' => $permission->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa các quyền đã thêm
        $permissionNames = [
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            'permissions.view', 'permissions.assign',
            'services.view', 'services.create', 'services.edit', 'services.delete',
            'appointments.view', 'appointments.create', 'appointments.edit', 'appointments.delete',
            'invoices.view', 'invoices.create', 'invoices.edit', 'invoices.delete', 'invoices.print',
            'posts.view', 'posts.create', 'posts.edit', 'posts.delete',
            'promotions.view', 'promotions.create', 'promotions.edit', 'promotions.delete',
            'settings.view', 'settings.edit',
            'reports.view', 'reports.export',
        ];
        
        DB::table('permissions')->whereIn('name', $permissionNames)->delete();
        
        // Xóa các cột đã thêm
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'description', 'group']);
        });
    }
};
