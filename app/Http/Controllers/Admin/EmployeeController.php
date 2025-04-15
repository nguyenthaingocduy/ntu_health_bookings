<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['role', 'clinic'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'customer')->get();
        $clinics = Clinic::all();
        return view('admin.employees.create', compact('roles', 'clinics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:male,female,other',
            'role_id' => 'required|exists:roles,id',
            'clinic_id' => 'required|exists:clinics,id',
        ]);

        Employee::create([
            'id' => Str::uuid(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthday' => $request->birthday,
            'address' => $request->address,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'role_id' => $request->role_id,
            'clinic_id' => $request->clinic_id,
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Nhân viên đã được thêm thành công.');
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $roles = Role::where('name', '!=', 'customer')->get();
        $clinics = Clinic::all();
        
        return view('admin.employees.edit', compact('employee', 'roles', 'clinics'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:male,female,other',
            'role_id' => 'required|exists:roles,id',
            'clinic_id' => 'required|exists:clinics,id',
        ]);

        $employee = Employee::findOrFail($id);
        $employee->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthday' => $request->birthday,
            'address' => $request->address,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'role_id' => $request->role_id,
            'clinic_id' => $request->clinic_id,
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Thông tin nhân viên đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        
        // Kiểm tra xem nhân viên có lịch hẹn nào không
        if ($employee->appointments()->count() > 0) {
            return back()->with('error', 'Không thể xóa nhân viên này vì đã có lịch hẹn.');
        }
        
        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Nhân viên đã được xóa thành công.');
    }
}
