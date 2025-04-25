<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->get();

        return view('admin.roles.index_tailwind', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.roles.create_tailwind');
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Role::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return redirect()->route('admin.roles.index')
                ->with('success', 'Vai trò đã được tạo thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi tạo vai trò: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $role->loadCount(['users', 'permissions']);

        return view('admin.roles.edit_tailwind', compact('role'));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $id . ',id',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $role->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return redirect()->route('admin.roles.index')
                ->with('success', 'Vai trò đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật vai trò: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Không cho phép xóa các vai trò mặc định
        if (in_array(strtolower($role->name), ['admin', 'technician', 'receptionist', 'customer', 'staff'])) {
            return redirect()->back()
                ->with('error', 'Không thể xóa vai trò mặc định.');
        }

        try {
            // Kiểm tra xem có người dùng nào đang sử dụng vai trò này không
            $usersCount = User::where('role_id', $role->id)->count();

            if ($usersCount > 0) {
                return redirect()->back()
                    ->with('error', 'Không thể xóa vai trò này vì có ' . $usersCount . ' người dùng đang sử dụng.');
            }

            // Xóa các quyền của vai trò
            DB::table('role_permissions')->where('role_id', $role->id)->delete();

            // Xóa vai trò
            $role->delete();

            return redirect()->route('admin.roles.index')
                ->with('success', 'Vai trò đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi xóa vai trò: ' . $e->getMessage());
        }
    }
}
