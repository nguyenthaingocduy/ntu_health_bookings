<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Service;
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
        $roles = Role::where('name', '!=', 'Customer')->get();
        $clinics = Clinic::all();
        $services = Service::where('status', 'active')->get();
        return view('admin.employees.create', compact('roles', 'clinics', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:employees,email',
            'gender' => 'nullable|in:male,female,other',
            'role_id' => 'required|exists:roles,id',
            'clinic_id' => 'required|exists:clinics,id',
            'status' => 'required|in:active,inactive',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        $data = $request->only([
            'first_name', 'last_name', 'birthday', 'address', 'phone', 'email',
            'gender', 'role_id', 'clinic_id', 'status'
        ]);

        // Generate UUID
        $data['id'] = Str::uuid();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('images/employees'), $avatarName);
            $data['avatar_url'] = 'images/employees/' . $avatarName;
        }

        $employee = Employee::create($data);

        // Sync services
        if ($request->has('services')) {
            $employee->services()->sync($request->services);
        }

        return redirect()->route('admin.employees.index')
            ->with('success', 'Nhân viên đã được thêm thành công.');
    }

    public function edit($id)
    {
        $employee = Employee::with('services')->findOrFail($id);
        $roles = Role::where('name', '!=', 'Customer')->get();
        $clinics = Clinic::all();
        $services = Service::where('status', 'active')->get();

        return view('admin.employees.edit', compact('employee', 'roles', 'clinics', 'services'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'gender' => 'nullable|in:male,female,other',
            'role_id' => 'required|exists:roles,id',
            'clinic_id' => 'required|exists:clinics,id',
            'status' => 'required|in:active,inactive',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        $data = $request->only([
            'first_name', 'last_name', 'birthday', 'address', 'phone', 'email',
            'gender', 'role_id', 'clinic_id', 'status'
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($employee->avatar_url && file_exists(public_path($employee->avatar_url))) {
                unlink(public_path($employee->avatar_url));
            }

            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('images/employees'), $avatarName);
            $data['avatar_url'] = 'images/employees/' . $avatarName;
        }

        $employee->update($data);

        // Sync services
        if ($request->has('services')) {
            $employee->services()->sync($request->services);
        } else {
            $employee->services()->detach();
        }

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
