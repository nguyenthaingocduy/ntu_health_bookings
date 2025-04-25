<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EnsureAdminUserSeeder extends Seeder
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

        // Tìm người dùng admin
        $adminUser = User::where('email', 'admin@example.com')->first();

        if (!$adminUser) {
            // Tìm customer_type_id
            $customerType = \App\Models\CustomerType::first();
            if (!$customerType) {
                $this->command->error('Không tìm thấy loại khách hàng');
                return;
            }

            // Tạo người dùng admin mới
            $adminUser = User::create([
                'id' => Str::uuid(),
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'phone' => '0123456789',
                'address' => 'Địa chỉ admin',
                'gender' => 'Nam',
                'role_id' => $adminRole->id,
                'type_id' => $customerType->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info('Đã tạo người dùng admin mới');
        } else {
            // Cập nhật vai trò cho người dùng admin
            $adminUser->update([
                'role_id' => $adminRole->id,
            ]);

            $this->command->info('Đã cập nhật vai trò Admin cho người dùng admin');
        }
    }
}
