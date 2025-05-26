<?php

require_once 'vendor/autoload.php';

use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Support\Str;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Fixing Receptionist Permissions ===\n";

// Get Receptionist role
$receptionistRole = Role::where('name', 'Receptionist')->first();

if (!$receptionistRole) {
    echo "ERROR: Receptionist role not found!\n";
    exit(1);
}

echo "Found Receptionist role: {$receptionistRole->id}\n";

// Define basic permissions for Receptionist
$receptionistPermissions = [
    'appointments.view',
    'appointments.create', 
    'appointments.edit',
    'customers.view',
    'customers.create',
    'customers.edit',
    'services.view',
    'invoices.view',
    'invoices.create',
    'payments.view',
    'payments.create',
    'promotions.view',
    'reports.view'
];

echo "Assigning permissions to Receptionist role...\n";

$assignedCount = 0;
$notFoundCount = 0;

foreach ($receptionistPermissions as $permissionName) {
    $permission = Permission::where('name', $permissionName)->first();
    
    if (!$permission) {
        echo "WARNING: Permission '{$permissionName}' not found\n";
        $notFoundCount++;
        continue;
    }
    
    // Check if already assigned
    $existing = RolePermission::where('role_id', $receptionistRole->id)
        ->where('permission_id', $permission->id)
        ->first();
        
    if ($existing) {
        echo "SKIP: Permission '{$permissionName}' already assigned\n";
        continue;
    }
    
    // Assign permission
    RolePermission::create([
        'id' => Str::uuid(),
        'role_id' => $receptionistRole->id,
        'permission_id' => $permission->id,
    ]);
    
    echo "ASSIGNED: {$permissionName}\n";
    $assignedCount++;
}

echo "\n=== Summary ===\n";
echo "Permissions assigned: {$assignedCount}\n";
echo "Permissions not found: {$notFoundCount}\n";

// Clear cache for all Receptionist users
$receptionistUsers = \App\Models\User::where('role_id', $receptionistRole->id)->get();
echo "Clearing cache for " . $receptionistUsers->count() . " receptionist users...\n";

foreach ($receptionistUsers as $user) {
    $user->clearPermissionCache();
    echo "Cache cleared for: {$user->email}\n";
}

echo "\n=== DONE ===\n";
echo "Receptionist permissions have been fixed!\n";
echo "You can now test with: php artisan debug:permissions huyen@gmail.com\n";
