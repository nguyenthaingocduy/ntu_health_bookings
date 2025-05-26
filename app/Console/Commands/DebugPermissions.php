<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class DebugPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:permissions {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug user permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        if (!$email) {
            $email = $this->ask('Enter user email to debug');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found");
            return;
        }

        $this->info("=== Permission Debug for User: {$user->full_name} ===");
        $this->info("Email: {$user->email}");
        $this->info("Role: " . ($user->role ? $user->role->name : 'No role'));

        // Role permissions
        if ($user->role) {
            $rolePermissions = $user->role->permissions()->get();
            $this->info("\n=== Role Permissions ({$rolePermissions->count()}) ===");
            foreach ($rolePermissions as $perm) {
                $this->line("- {$perm->name} ({$perm->display_name})");
            }
        }

        // User permissions
        $userPermissions = $user->userPermissions()->with('permission')->get();
        $this->info("\n=== Direct User Permissions ({$userPermissions->count()}) ===");
        foreach ($userPermissions as $perm) {
            $actions = [];
            if ($perm->can_view) $actions[] = 'view';
            if ($perm->can_create) $actions[] = 'create';
            if ($perm->can_edit) $actions[] = 'edit';
            if ($perm->can_delete) $actions[] = 'delete';

            $this->line("- {$perm->permission->name} [" . implode(', ', $actions) . "]");
        }

        // Test specific permissions
        $testPermissions = [
            ['services', 'view'],
            ['services', 'create'],
            ['promotions', 'view'],
            ['promotions', 'create']
        ];
        $this->info("\n=== Permission Tests ===");
        foreach ($testPermissions as [$resource, $action]) {
            $fullPerm = $resource . '.' . $action;
            $hasRole = $user->hasPermissionThroughRole($fullPerm);
            $hasDirect = $user->hasDirectPermission($resource, $action);
            $hasAny = $user->hasAnyPermission($resource, $action);
            $canMethod = $user->{'can' . ucfirst($action)}($resource);

            $this->line("- {$fullPerm}: Role=" . ($hasRole ? 'YES' : 'NO') . ", Direct=" . ($hasDirect ? 'YES' : 'NO') . ", Any=" . ($hasAny ? 'YES' : 'NO') . ", can{$action}=" . ($canMethod ? 'YES' : 'NO'));
        }

        // Clear cache option
        if ($this->confirm('Clear permission cache for this user?')) {
            $user->clearPermissionCache();
            $this->info('Permission cache cleared!');
        }
    }
}
