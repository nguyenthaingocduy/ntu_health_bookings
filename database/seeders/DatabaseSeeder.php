<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        // Run roles seeders first
        $this->call([
            RoleSeeder::class,
            FixRolesSeeder::class,
            NewRolesSeeder::class,
            UpdateRoleDescriptionsSeeder::class,
            CleanupDuplicateRolesSeeder::class,
        ]);

        // Run user seeders
        $this->call([
            AdminUserSeeder::class,
            CustomerTypeSeeder::class,
            UserSeeder::class,
            StaffRoleSeeder::class,
            StaffUserSeeder::class,
        ]);

        // Run permission seeders
        $this->call([
            PermissionSeeder::class,
            StaffPermissionsSeeder::class,
        ]);

        // Run service and category seeders
        $this->call([
            CategorySeeder::class,
            BeautySalonServiceSeeder::class,
            ClinicAndServiceSeeder::class,
        ]);

        // Run time and appointment seeders
        $this->call([
            TimeSlotSeeder::class,
            TimeSeeder::class,
            AppointmentSeeder::class,
        ]);
    }
}

