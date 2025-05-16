<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Kiểm tra xem cột 'group' có tồn tại trong bảng permissions không
        try {
            $permissions = Permission::orderBy('name')->get();

            // Nếu có cột 'group', nhóm theo group
            if (Schema::hasColumn('permissions', 'group')) {
                $permissions = Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');
            } else {
                // Nếu không có cột 'group', tạo một nhóm mặc định
                $permissions = ['Tất cả quyền' => $permissions];
            }

            return view('admin.permissions.index_tailwind', compact('permissions'));
        } catch (\Exception $e) {
            // Nếu có lỗi, lấy tất cả quyền mà không sắp xếp theo group
            $permissions = Permission::orderBy('name')->get();
            $permissions = ['Tất cả quyền' => $permissions];

            return view('admin.permissions.index_tailwind', compact('permissions'));
        }
    }

    /**
     * Show the form for creating a new permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = [];

        // Kiểm tra xem cột 'group' có tồn tại trong bảng permissions không
        if (Schema::hasColumn('permissions', 'group')) {
            $groups = Permission::select('group')->distinct()->pluck('group');
        }

        return view('admin.permissions.create_tailwind', compact('groups'));
    }

    /**
     * Store a newly created permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRules = [
            'name' => 'required|string|max:255|unique:permissions,name',
        ];

        // Kiểm tra xem các cột có tồn tại trong bảng permissions không
        if (Schema::hasColumn('permissions', 'display_name')) {
            $validationRules['display_name'] = 'required|string|max:255';
        }

        if (Schema::hasColumn('permissions', 'description')) {
            $validationRules['description'] = 'nullable|string|max:1000';
        }

        if (Schema::hasColumn('permissions', 'group')) {
            $validationRules['group'] = 'required|string|max:255';
        }

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $permissionData = [
                'id' => Str::uuid(),
                'name' => $request->name,
            ];

            // Thêm các trường khác nếu chúng tồn tại
            if (Schema::hasColumn('permissions', 'display_name')) {
                $permissionData['display_name'] = $request->display_name;
            }

            if (Schema::hasColumn('permissions', 'description')) {
                $permissionData['description'] = $request->description;
            }

            if (Schema::hasColumn('permissions', 'group')) {
                $permissionData['group'] = $request->group;
            }

            $permission = Permission::create($permissionData);

            // Assign to admin role automatically
            $adminRole = Role::where('name', 'Admin')->first();

            if ($adminRole) {
                RolePermission::create([
                    'id' => Str::uuid(),
                    'role_id' => $adminRole->id,
                    'permission_id' => $permission->id,
                ]);
            }

            return redirect()->route('admin.permissions.index')
                ->with('success', 'Quyền đã được tạo thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi tạo quyền: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified permission.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $groups = [];

        // Kiểm tra xem cột 'group' có tồn tại trong bảng permissions không
        if (Schema::hasColumn('permissions', 'group')) {
            $groups = Permission::select('group')->distinct()->pluck('group');
        }

        return view('admin.permissions.edit_tailwind', compact('permission', 'groups'));
    }

    /**
     * Update the specified permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $validationRules = [
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ];

        // Kiểm tra xem các cột có tồn tại trong bảng permissions không
        if (Schema::hasColumn('permissions', 'display_name')) {
            $validationRules['display_name'] = 'required|string|max:255';
        }

        if (Schema::hasColumn('permissions', 'description')) {
            $validationRules['description'] = 'nullable|string|max:1000';
        }

        if (Schema::hasColumn('permissions', 'group')) {
            $validationRules['group'] = 'required|string|max:255';
        }

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $permissionData = [
                'name' => $request->name,
            ];

            // Thêm các trường khác nếu chúng tồn tại
            if (Schema::hasColumn('permissions', 'display_name')) {
                $permissionData['display_name'] = $request->display_name;
            }

            if (Schema::hasColumn('permissions', 'description')) {
                $permissionData['description'] = $request->description;
            }

            if (Schema::hasColumn('permissions', 'group')) {
                $permissionData['group'] = $request->group;
            }

            $permission->update($permissionData);

            return redirect()->route('admin.permissions.index')
                ->with('success', 'Quyền đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật quyền: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified permission from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        try {
            // Delete role permissions
            RolePermission::where('permission_id', $permission->id)->delete();

            // Delete user permissions
            UserPermission::where('permission_id', $permission->id)->delete();

            // Delete permission
            $permission->delete();

            return redirect()->route('admin.permissions.index')
                ->with('success', 'Quyền đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi xóa quyền: ' . $e->getMessage());
        }
    }

    /**
     * Display the role permissions page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function rolePermissions(Request $request)
    {
        $roles = Role::all();
        $permissions = [];

        // Nếu không có role_id được chỉ định, chọn vai trò đầu tiên mặc định
        $selectedRoleId = $request->query('role_id');
        if (empty($selectedRoleId) && $roles->count() > 0) {
            $selectedRoleId = $roles->first()->id;
        }

        // Kiểm tra xem cột 'group' có tồn tại trong bảng permissions không
        try {
            if (Schema::hasColumn('permissions', 'group')) {
                $permissions = Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');
            } else {
                $allPermissions = Permission::orderBy('name')->get();
                $permissions = ['Tất cả quyền' => $allPermissions];
            }
        } catch (\Exception $e) {
            $allPermissions = Permission::orderBy('name')->get();
            $permissions = ['Tất cả quyền' => $allPermissions];
        }

        $rolePermissions = [];

        foreach ($roles as $role) {
            $rolePermissions[$role->id] = $role->permissions()->pluck('permissions.id')->toArray();
        }

        // Ghi log để debug
        \Illuminate\Support\Facades\Log::info('Role Permissions View', [
            'roles' => $roles->count(),
            'permissions' => count($permissions),
            'view' => 'admin.permissions.role_permissions_tailwind',
            'selectedRoleId' => $selectedRoleId
        ]);

        return view('admin.permissions.role_permissions_tailwind', compact('roles', 'permissions', 'rolePermissions', 'selectedRoleId'));
    }

    /**
     * Update the role permissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateRolePermissions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $roleId = $request->role_id;
            $permissions = $request->permissions ?? [];

            DB::beginTransaction();

            // Delete existing role permissions
            RolePermission::where('role_id', $roleId)->delete();

            // Create new role permissions
            foreach ($permissions as $permissionId) {
                RolePermission::create([
                    'id' => Str::uuid(),
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                ]);
            }

            // Clear permission cache for all users with this role
            $users = User::where('role_id', $roleId)->get();

            foreach ($users as $user) {
                $user->clearPermissionCache();
            }

            DB::commit();

            return redirect()->route('admin.permissions.role-permissions')
                ->with('success', 'Quyền của vai trò đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật quyền của vai trò: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the user permissions page.
     *
     * @return \Illuminate\Http\Response
     */
    public function userPermissions()
    {
        $users = User::with('role')->whereHas('role', function($query) {
            $query->where('name', '!=', 'Customer');
        })->get();

        return view('admin.permissions.user_permissions_tailwind', compact('users'));
    }

    /**
     * Show the form for editing the user permissions.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function editUserPermissions($id)
    {
        $user = User::with('role', 'userPermissions')->findOrFail($id);
        $permissions = [];

        // Kiểm tra xem cột 'group' có tồn tại trong bảng permissions không
        try {
            if (Schema::hasColumn('permissions', 'group')) {
                $permissions = Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');
            } else {
                $allPermissions = Permission::orderBy('name')->get();
                $permissions = ['Tất cả quyền' => $allPermissions];
            }
        } catch (\Exception $e) {
            $allPermissions = Permission::orderBy('name')->get();
            $permissions = ['Tất cả quyền' => $allPermissions];
        }

        $userPermissions = [];

        foreach ($user->userPermissions as $userPermission) {
            $userPermissions[$userPermission->permission_id] = [
                'can_view' => $userPermission->can_view,
                'can_create' => $userPermission->can_create,
                'can_edit' => $userPermission->can_edit,
                'can_delete' => $userPermission->can_delete,
            ];
        }

        $rolePermissions = $user->role->permissions()->pluck('permissions.id')->toArray();

        return view('admin.permissions.edit_user_permissions_fixed', compact('user', 'permissions', 'userPermissions', 'rolePermissions'));
    }

    /**
     * Update the user permissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUserPermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'permissions' => 'nullable|array',
            'permissions.*.id' => 'required|exists:permissions,id',
            'permissions.*.can_view' => 'nullable|boolean',
            'permissions.*.can_create' => 'nullable|boolean',
            'permissions.*.can_edit' => 'nullable|boolean',
            'permissions.*.can_delete' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $permissions = $request->permissions ?? [];

            DB::beginTransaction();

            // Delete existing user permissions
            UserPermission::where('user_id', $user->id)->delete();

            // Create new user permissions
            foreach ($permissions as $permission) {
                if (isset($permission['id']) && (
                    isset($permission['can_view']) ||
                    isset($permission['can_create']) ||
                    isset($permission['can_edit']) ||
                    isset($permission['can_delete'])
                )) {
                    // Log để kiểm tra
                    Log::info('Creating permission:', [
                        'permission_id' => $permission['id'],
                        'can_view' => isset($permission['can_view']),
                        'can_create' => isset($permission['can_create']),
                        'can_edit' => isset($permission['can_edit']),
                        'can_delete' => isset($permission['can_delete']),
                    ]);

                    UserPermission::create([
                        'id' => Str::uuid(),
                        'user_id' => $user->id,
                        'permission_id' => $permission['id'],
                        'can_view' => isset($permission['can_view']),
                        'can_create' => isset($permission['can_create']),
                        'can_edit' => isset($permission['can_edit']),
                        'can_delete' => isset($permission['can_delete']),
                        'granted_by' => Auth::id() ?? $user->id,
                    ]);
                }
            }

            // Clear permission cache for the user
            $user->clearPermissionCache();

            DB::commit();

            return redirect()->route('admin.permissions.index')
                ->with('success', 'Quyền của người dùng đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật quyền của người dùng: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the current user's permissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function myPermissions()
    {
        return view('admin.permissions.my_permissions');
    }

    /**
     * Hiển thị trang ma trận phân quyền theo vai trò.
     *
     * @return \Illuminate\Http\Response
     */
    public function rolePermissionsMatrix()
    {
        $roles = Role::all();
        $permissions = [];

        // Kiểm tra xem cột 'group' có tồn tại trong bảng permissions không
        try {
            if (Schema::hasColumn('permissions', 'group')) {
                $permissions = Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');
            } else {
                $allPermissions = Permission::orderBy('name')->get();
                $permissions = ['Tất cả quyền' => $allPermissions];
            }
        } catch (\Exception $e) {
            $allPermissions = Permission::orderBy('name')->get();
            $permissions = ['Tất cả quyền' => $allPermissions];
        }

        $rolePermissions = [];

        foreach ($roles as $role) {
            $rolePermissions[$role->id] = $role->permissions()->pluck('permissions.id')->toArray();
        }

        return view('admin.permissions.role_permissions_matrix', compact('roles', 'permissions', 'rolePermissions'));
    }

    /**
     * Cập nhật phân quyền theo vai trò từ ma trận.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateRolePermissionsMatrix(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'matrix' => 'required|array',
            'matrix.*' => 'array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $matrix = $request->matrix;

            DB::beginTransaction();

            // Xử lý quyền cho từng vai trò
            foreach ($matrix as $roleId => $permissions) {
                // Xác thực ID vai trò
                if (!Role::where('id', $roleId)->exists()) {
                    continue;
                }

                // Xóa quyền vai trò hiện có
                RolePermission::where('role_id', $roleId)->delete();

                // Tạo quyền vai trò mới
                foreach (array_keys($permissions) as $permissionId) {
                    // Xác thực ID quyền
                    if (!Permission::where('id', $permissionId)->exists()) {
                        continue;
                    }

                    RolePermission::create([
                        'id' => Str::uuid(),
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                    ]);
                }

                // Xóa bộ nhớ đệm quyền cho tất cả người dùng có vai trò này
                $users = User::where('role_id', $roleId)->get();

                foreach ($users as $user) {
                    $user->clearPermissionCache();
                }
            }

            DB::commit();

            return redirect()->route('admin.permissions.role-permissions-matrix')
                ->with('success', 'Quyền của các vai trò đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật quyền của vai trò: ' . $e->getMessage())
                ->withInput();
        }
    }
}
