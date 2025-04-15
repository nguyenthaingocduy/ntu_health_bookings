<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\User;

class TestRolesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test roles in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking roles in the database:');
        
        // Hiển thị tất cả các roles
        $roles = Role::all();
        $this->table(
            ['ID', 'Name', 'Created At'],
            $roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'created_at' => $role->created_at,
                ];
            })
        );
        
        // Kiểm tra user có role Admin
        $this->info('Checking admin users:');
        $adminRole = Role::where('name', 'Admin')->first();
        
        if ($adminRole) {
            $adminUsers = User::where('role_id', $adminRole->id)->get();
            
            if ($adminUsers->count() > 0) {
                $this->table(
                    ['ID', 'Email', 'First Name', 'Last Name'],
                    $adminUsers->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'email' => $user->email,
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                        ];
                    })
                );
            } else {
                $this->error('No admin users found!');
            }
        } else {
            $this->error('Admin role not found!');
        }
        
        // Kiểm tra user hiện tại có role gì
        $this->info('Admin user with email admin@gmail.com:');
        $adminUser = User::where('email', 'admin@gmail.com')->first();
        
        if ($adminUser) {
            $this->info("User found with ID: {$adminUser->id}");
            $this->info("Role ID: {$adminUser->role_id}");
            
            if ($adminUser->role) {
                $this->info("Role name: {$adminUser->role->name}");
            } else {
                $this->error("No role found for this user!");
            }
        } else {
            $this->error('Admin user not found!');
        }
        
        return Command::SUCCESS;
    }
}
