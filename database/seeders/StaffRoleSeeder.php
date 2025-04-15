<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Str;

class StaffRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if Staff role already exists
        $staffRole = Role::where('name', 'Staff')->first();
        
        // If not, create it
        if (!$staffRole) {
            Role::create([
                'id' => Str::uuid(),
                'name' => 'Staff',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Staff role has been created.');
        } else {
            $this->command->info('Staff role already exists.');
        }
    }
}
