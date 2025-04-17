<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::create(['id' => Str::uuid(), 'name' => 'admin']);
        $staffRole = Role::create(['id' => Str::uuid(), 'name' => 'staff']);
        $userRole = Role::create(['id' => Str::uuid(), 'name' => 'user']);
        $doctorRole = Role::create(['id' => Str::uuid(), 'name' => 'doctor']);

        // Create admin user
        User::create([
            'id' => Str::uuid(),
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@ntu.edu.vn',
            'password' => Hash::make('password'),
            'phone' => '0123456789',
            'address' => 'Nha Trang University',
            'gender' => 'male',
            'role_id' => $adminRole->id,
        ]);

        // Create doctor user
        User::create([
            'id' => Str::uuid(),
            'first_name' => 'Chuyên Viên',
            'last_name' => 'Nguyễn',
            'email' => 'beautician@salon.com',
            'password' => Hash::make('password'),
            'phone' => '0123456788',
            'address' => 'Nha Trang Beauty Salon',
            'gender' => 'female',
            'role_id' => $doctorRole->id,
            'position' => 'Chuyên viên làm đẹp',
            'department' => 'Trung tâm làm đẹp',
        ]);

        // Run other seeders
        $this->call([
            TimeSlotSeeder::class,
            CategorySeeder::class,
            BeautySalonServiceSeeder::class,
            ClinicAndServiceSeeder::class,
        ]);
    }
}

