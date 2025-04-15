<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Str;

class FixRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra xem role Customer đã tồn tại chưa
        $customerRole = Role::where('name', 'Customer')->first();
        
        // Nếu chưa tồn tại, tạo mới
        if (!$customerRole) {
            Role::create([
                'id' => Str::uuid(),
                'name' => 'Customer',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Role Customer đã được tạo.');
        } else {
            $this->command->info('Role Customer đã tồn tại.');
        }
        
        // Kiểm tra xem role Admin đã tồn tại chưa
        $adminRole = Role::where('name', 'Admin')->first();
        
        // Nếu chưa tồn tại, tạo mới
        if (!$adminRole) {
            Role::create([
                'id' => Str::uuid(),
                'name' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Role Admin đã được tạo.');
        } else {
            $this->command->info('Role Admin đã tồn tại.');
        }
    }
} 