<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\CustomerType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find Staff role
        $staffRole = Role::where('name', 'Staff')->first();
        
        if (!$staffRole) {
            $this->command->error('Staff role not found. Please run StaffRoleSeeder first.');
            return;
        }
        
        // Find or create regular customer type
        $regularType = CustomerType::where('type_name', 'Regular')->first();
        
        if (!$regularType) {
            $regularType = CustomerType::create([
                'id' => Str::uuid(),
                'type_name' => 'Regular',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Create staff user if it doesn't exist
        $staffExists = User::where('email', 'staff@ntu.edu.vn')->exists();
        
        if (!$staffExists) {
            User::create([
                'id' => Str::uuid(),
                'first_name' => 'Nhân',
                'last_name' => 'Viên',
                'email' => 'staff@ntu.edu.vn',
                'password' => Hash::make('password'),
                'phone' => '0987654321',
                'address' => 'Trường Đại học Nha Trang',
                'gender' => 'male',
                'role_id' => $staffRole->id,
                'type_id' => $regularType->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Staff user has been created.');
        } else {
            $this->command->info('Staff user already exists.');
        }
    }
}
