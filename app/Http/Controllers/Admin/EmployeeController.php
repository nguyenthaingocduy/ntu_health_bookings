<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
        // Pre-process the address field to ensure it's not null
        if ($request->has('address') && $request->input('address') === null) {
            $request->merge(['address' => 'N/A']);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:employees,email',
            'gender' => 'required|in:male,female,other',
            'role_id' => 'required|exists:roles,id',
            'clinic_id' => 'required|exists:clinics,id',
            'status' => 'required|in:active,inactive',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ], [
            'birthday.before_or_equal' => 'Nhân viên phải từ 18 tuổi trở lên.',
            'address.required' => 'Vui lòng nhập địa chỉ của nhân viên.',
            'gender.required' => 'Vui lòng chọn giới tính của nhân viên.'
        ]);

        $data = $request->only([
            'first_name', 'last_name', 'birthday', 'phone', 'email',
            'gender', 'role_id', 'clinic_id', 'status', 'address'
        ]);

        // Generate UUID
        $data['id'] = Str::uuid();

        // Set default avatar if no avatar is uploaded
        $data['avatar_url'] = 'images/employees/default-avatar.jpg';

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            try {
                $avatar = $request->file('avatar');

                // Debug information
                Log::info('Avatar upload attempt', [
                    'original_name' => $avatar->getClientOriginalName(),
                    'mime_type' => $avatar->getMimeType(),
                    'size' => $avatar->getSize(),
                    'error' => $avatar->getError()
                ]);

                // Check if the file is valid
                if ($avatar->isValid()) {
                    $avatarName = time() . '.' . $avatar->getClientOriginalExtension();

                    // Make sure the directory exists with proper permissions
                    $directory = public_path('images/employees');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0777, true);
                    } else {
                        chmod($directory, 0777);
                    }

                    // Move the file with explicit path
                    $path = $directory . '/' . $avatarName;
                    if (move_uploaded_file($avatar->getRealPath(), $path)) {
                        $data['avatar_url'] = 'images/employees/' . $avatarName;
                        Log::info('Avatar uploaded successfully', ['path' => $path]);
                    } else {
                        Log::error('Failed to move uploaded file', [
                            'from' => $avatar->getRealPath(),
                            'to' => $path,
                            'permissions' => substr(sprintf('%o', fileperms($directory)), -4)
                        ]);
                    }
                } else {
                    Log::error('Invalid avatar file', ['error' => $avatar->getError()]);
                }
            } catch (\Exception $e) {
                Log::error('Exception during avatar upload', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
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

        // Pre-process the address field to ensure it's not null
        if ($request->has('address') && $request->input('address') === null) {
            $request->merge(['address' => 'N/A']);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'gender' => 'required|in:male,female,other',
            'role_id' => 'required|exists:roles,id',
            'clinic_id' => 'required|exists:clinics,id',
            'status' => 'required|in:active,inactive',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ], [
            'birthday.before_or_equal' => 'Nhân viên phải từ 18 tuổi trở lên.',
            'address.required' => 'Vui lòng nhập địa chỉ của nhân viên.',
            'gender.required' => 'Vui lòng chọn giới tính của nhân viên.'
        ]);

        $data = $request->only([
            'first_name', 'last_name', 'birthday', 'phone', 'email',
            'gender', 'role_id', 'clinic_id', 'status', 'address'
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            try {
                $avatar = $request->file('avatar');

                // Debug information
                Log::info('Avatar upload attempt (update)', [
                    'original_name' => $avatar->getClientOriginalName(),
                    'mime_type' => $avatar->getMimeType(),
                    'size' => $avatar->getSize(),
                    'error' => $avatar->getError()
                ]);

                // Check if the file is valid
                if ($avatar->isValid()) {
                    // Delete old avatar if exists and not the default avatar
                    if ($employee->avatar_url &&
                        file_exists(public_path($employee->avatar_url)) &&
                        $employee->avatar_url != 'images/employees/default-avatar.jpg') {
                        unlink(public_path($employee->avatar_url));
                    }

                    $avatarName = time() . '.' . $avatar->getClientOriginalExtension();

                    // Make sure the directory exists with proper permissions
                    $directory = public_path('images/employees');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0777, true);
                    } else {
                        chmod($directory, 0777);
                    }

                    // Move the file with explicit path
                    $path = $directory . '/' . $avatarName;
                    if (move_uploaded_file($avatar->getRealPath(), $path)) {
                        $data['avatar_url'] = 'images/employees/' . $avatarName;
                        Log::info('Avatar uploaded successfully (update)', ['path' => $path]);
                    } else {
                        Log::error('Failed to move uploaded file (update)', [
                            'from' => $avatar->getRealPath(),
                            'to' => $path,
                            'permissions' => substr(sprintf('%o', fileperms($directory)), -4)
                        ]);
                    }
                } else {
                    Log::error('Invalid avatar file (update)', ['error' => $avatar->getError()]);
                }
            } catch (\Exception $e) {
                Log::error('Exception during avatar upload (update)', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
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

        // Get current month appointments
        $now = now();
        $monthlyAppointments = $appointments->filter(function($appointment) use ($now) {
            return \Carbon\Carbon::parse($appointment->date_appointments)->month == $now->month &&
                   \Carbon\Carbon::parse($appointment->date_appointments)->year == $now->year;
        })->count();

        // Calculate completion rate
        $completionRate = $appointments->count() > 0
            ? ($appointments->where('status', 'completed')->count() / $appointments->count()) * 100
            : 0;

        // Calculate average rating (mock data since we don't have ratings)
        $averageRating = 4.5; // Default value

        // Get recent appointments for display
        $recentAppointments = $appointments->take(5);

        // Calculate statistics
        $statistics = [
            'total_appointments' => $appointments->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'pending' => $appointments->where('status', 'pending')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
            'monthly_appointments' => $monthlyAppointments,
            'completion_rate' => $completionRate,
            'average_rating' => $averageRating
        ];

        return view('admin.employees.show', compact('employee', 'appointments', 'statistics', 'recentAppointments'));
    }
}
