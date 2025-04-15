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
    public function index(Request $request)
    {
        $query = Employee::with(['role', 'clinic', 'services']);

        // Apply filters if provided
        if ($request->filled('clinic')) {
            $query->where('clinic_id', $request->clinic);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $employees = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get all clinics for the filter
        $clinics = Clinic::all();

        // Calculate statistics
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $inactiveEmployees = Employee::where('status', 'inactive')->count();

        $statistics = [
            'total' => $totalEmployees,
            'active' => $activeEmployees,
            'inactive' => $inactiveEmployees,
            'average_rating' => 4.5, // Placeholder value, replace with actual calculation if available
        ];

        return view('admin.employees.index', compact('employees', 'clinics', 'statistics'));
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

    /**
     * Toggle the status of the specified employee.
     */
    public function toggleStatus($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->status = $employee->status === 'active' ? 'inactive' : 'active';
        $employee->save();

        $statusText = $employee->status === 'active' ? 'được kích hoạt' : 'được chuyển sang trạng thái không hoạt động';

        return redirect()->route('admin.employees.index')
            ->with('success', "Nhân viên {$employee->name} đã {$statusText} thành công.");
    }

    /**
     * Display the specified employee.
     */
    public function show($id)
    {
        $employee = Employee::with(['role', 'clinic', 'services', 'appointments'])
            ->findOrFail($id);

        // Get employee's appointments
        $appointments = $employee->appointments()
            ->with(['service', 'user', 'timeAppointment'])
            ->orderBy('date_appointments', 'desc')
            ->get();

        // Calculate statistics
        $statistics = [
            'total_appointments' => $appointments->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'pending' => $appointments->where('status', 'pending')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
        ];

        return view('admin.employees.show', compact('employee', 'appointments', 'statistics'));
    }
}
