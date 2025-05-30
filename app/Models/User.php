<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'gender',
        'date_of_birth',
        'password',
        'role_id',
        'type_id',
        'staff_id',
        'department',
        'position',
        'employee_code',
        'status',
        'email_notifications_enabled',
        'notify_appointment_confirmation',
        'notify_appointment_reminder',
        'notify_appointment_cancellation',
        'notify_promotions',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'last_health_check' => 'date',
            'email_notifications_enabled' => 'boolean',
            'notify_appointment_confirmation' => 'boolean',
            'notify_appointment_reminder' => 'boolean',
            'notify_appointment_cancellation' => 'boolean',
            'notify_promotions' => 'boolean',
        ];
    }

    public function getStatusAttribute($value)
    {
        return $value ?? 'active';
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function userPermissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    public function isAdmin()
    {
        return $this->hasRole('Admin');
    }

    public function isCustomer()
    {
        return $this->hasRole('Customer');
    }

    public function isStaff()
    {
        return $this->hasRole('Staff');
    }

    public function isUniversityStaff()
    {
        return !empty($this->staff_id) && !empty($this->department);
    }

    public function isReceptionist()
    {
        return $this->hasRole('Receptionist');
    }

    public function isTechnician()
    {
        return $this->hasRole('Technician');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'customer_id');
    }

    public function staffAppointments()
    {
        return $this->hasMany(Appointment::class, 'employee_id');
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function hasRole($roleName)
    {
        if (!$this->role) {
            return false;
        }

        return strtolower($this->role->name) === strtolower($roleName);
    }

    /**
     * Check if user has a specific permission through role
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermissionThroughRole($permissionName)
    {
        if (!$this->role) {
            return false;
        }

        $cacheKey = 'user_role_permission_' . $this->id . '_' . $permissionName;

        return Cache::remember($cacheKey, 60 * 5, function () use ($permissionName) {
            return $this->role->permissions()
                ->where('name', $permissionName)
                ->exists();
        });
    }

    /**
     * Check if user has a specific permission directly assigned
     *
     * @param string $permissionName (e.g., 'promotions' or 'promotions.view')
     * @param string $action (view, create, edit, delete)
     * @return bool
     */
    public function hasDirectPermission($permissionName, $action = 'view')
    {
        $cacheKey = 'user_direct_permission_' . $this->id . '_' . $permissionName . '_' . $action;

        return Cache::remember($cacheKey, 60 * 5, function () use ($permissionName, $action) {
            // If permissionName doesn't contain dot, add action to it
            if (!str_contains($permissionName, '.')) {
                $fullPermissionName = $permissionName . '.' . $action;
            } else {
                $fullPermissionName = $permissionName;
            }

            $permission = Permission::where('name', $fullPermissionName)->first();

            if (!$permission) {
                return false;
            }

            $userPermission = $this->userPermissions()
                ->where('permission_id', $permission->id)
                ->first();

            if (!$userPermission) {
                return false;
            }

            $actionField = 'can_' . $action;

            return $userPermission->$actionField;
        });
    }

    /**
     * Check if user has a specific permission (only through role)
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission($permissionName)
    {
        // Admin has all permissions
        if ($this->isAdmin()) {
            return true;
        }

        // Only check role permission
        return $this->hasPermissionThroughRole($permissionName);
    }

    /**
     * Check if user has a specific permission (through role OR direct assignment)
     *
     * @param string $permissionName
     * @param string $action
     * @return bool
     */
    public function hasAnyPermission($permissionName, $action = 'view')
    {
        // Admin has all permissions
        if ($this->isAdmin()) {
            return true;
        }

        // Check role permission first
        $fullPermissionName = str_contains($permissionName, '.') ? $permissionName : $permissionName . '.' . $action;
        if ($this->hasPermissionThroughRole($fullPermissionName)) {
            return true;
        }

        // Check direct permission
        return $this->hasDirectPermission($permissionName, $action);
    }

    /**
     * Check if user can view a specific resource
     *
     * @param string $resource
     * @return bool
     */
    public function canView($resource)
    {
        return $this->hasAnyPermission($resource, 'view');
    }

    /**
     * Check if user can create a specific resource
     *
     * @param string $resource
     * @return bool
     */
    public function canCreate($resource)
    {
        return $this->hasAnyPermission($resource, 'create');
    }

    /**
     * Check if user can edit a specific resource
     *
     * @param string $resource
     * @return bool
     */
    public function canEdit($resource)
    {
        return $this->hasAnyPermission($resource, 'edit');
    }

    /**
     * Check if user can delete a specific resource
     *
     * @param string $resource
     * @return bool
     */
    public function canDelete($resource)
    {
        return $this->hasAnyPermission($resource, 'delete');
    }

    /**
     * Clear permission cache for this user
     *
     * @return void
     */
    public function clearPermissionCache()
    {
        // Clear all cache keys that start with user permission patterns
        $cacheKeys = [
            'user_role_permission_' . $this->id . '_*',
            'user_direct_permission_' . $this->id . '_*'
        ];

        // Get all permissions to clear specific cache keys
        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            // Clear role permission cache
            Cache::forget('user_role_permission_' . $this->id . '_' . $permission->name);

            // Clear direct permission cache for all actions
            foreach (['view', 'create', 'edit', 'delete'] as $action) {
                Cache::forget('user_direct_permission_' . $this->id . '_' . $permission->name . '_' . $action);

                // Also clear cache for resource-based permissions
                $parts = explode('.', $permission->name);
                if (count($parts) >= 2) {
                    $resource = $parts[0];
                    Cache::forget('user_direct_permission_' . $this->id . '_' . $resource . '_' . $action);
                }
            }
        }

        // Force refresh the user model to ensure fresh data
        $this->refresh();
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'created_by');
    }
}
