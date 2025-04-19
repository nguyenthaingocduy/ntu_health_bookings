<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\CustomerType;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

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
        $data['avatar_url'] = 'images/employees/default-avatar.svg';

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');

            // Debug information
            Log::info('Avatar upload attempt', [
                'original_name' => $avatar->getClientOriginalName(),
                'mime_type' => $avatar->getMimeType(),
                'size' => $avatar->getSize()
            ]);

            // Make sure the directory exists with proper permissions
            $directory = public_path('images/employees');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // Generate a unique filename
            $avatarName = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();

            // Store the file
            $avatar->move($directory, $avatarName);
            $data['avatar_url'] = 'images/employees/' . $avatarName;

            Log::info('Avatar uploaded successfully', ['path' => $directory . '/' . $avatarName]);
        }

        // Create the employee record
        $employee = Employee::create($data);

        // Sync services
        if ($request->has('services')) {
            $employee->services()->sync($request->services);
        }

        // Create a corresponding user account for authentication
        $defaultPassword = 'password123'; // Default password, should be changed by the employee

        // Get a default customer type ID
        $defaultTypeId = null;
        try {
            $defaultType = CustomerType::first();
            if ($defaultType) {
                $defaultTypeId = $defaultType->id;
            }
        } catch (\Exception $e) {
            Log::error("Error getting default customer type: {$e->getMessage()}");
        }

        $user = User::create([
            'id' => Str::uuid(),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'gender' => $data['gender'],
            'birthday' => $data['birthday'],
            'password' => Hash::make($defaultPassword),
            'role_id' => $data['role_id'],
            'type_id' => $defaultTypeId,
            'status' => $data['status'],
        ]);

        Log::info('Created user account for employee', [
            'employee_id' => $employee->id,
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Nhân viên đã được thêm thành công. Tài khoản đăng nhập đã được tạo với mật khẩu mặc định: ' . $defaultPassword);
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
            $avatar = $request->file('avatar');

            // Debug information
            Log::info('Avatar upload attempt (update)', [
                'original_name' => $avatar->getClientOriginalName(),
                'mime_type' => $avatar->getMimeType(),
                'size' => $avatar->getSize()
            ]);

            // Delete old avatar if exists and not the default avatar
            if ($employee->avatar_url &&
                file_exists(public_path($employee->avatar_url)) &&
                $employee->avatar_url != 'images/employees/default-avatar.svg') {
                unlink(public_path($employee->avatar_url));
            }

            // Make sure the directory exists with proper permissions
            $directory = public_path('images/employees');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // Generate a unique filename
            $avatarName = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();

            // Store the file
            $avatar->move($directory, $avatarName);
            $data['avatar_url'] = 'images/employees/' . $avatarName;

            Log::info('Avatar uploaded successfully (update)', ['path' => $directory . '/' . $avatarName]);
        }

        // Update the employee record
        $employee->update($data);

        // Sync services
        if ($request->has('services')) {
            $employee->services()->sync($request->services);
        } else {
            $employee->services()->detach();
        }

        // Update the corresponding user account if it exists
        $user = User::where('email', $data['email'])->first();

        if ($user) {
            $user->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'gender' => $data['gender'],
                'birthday' => $data['birthday'],
                'role_id' => $data['role_id'],
                'status' => $data['status'],
            ]);

            Log::info('Updated user account for employee', [
                'employee_id' => $employee->id,
                'user_id' => $user->id,
                'email' => $user->email
            ]);
        } else {
            // If no user account exists, create one
            $defaultPassword = 'password123';

            // Get a default customer type ID
            $defaultTypeId = null;
            try {
                $defaultType = CustomerType::first();
                if ($defaultType) {
                    $defaultTypeId = $defaultType->id;
                }
            } catch (\Exception $e) {
                Log::error("Error getting default customer type: {$e->getMessage()}");
            }

            $user = User::create([
                'id' => Str::uuid(),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'gender' => $data['gender'],
                'birthday' => $data['birthday'],
                'password' => Hash::make($defaultPassword),
                'role_id' => $data['role_id'],
                'type_id' => $defaultTypeId,
                'status' => $data['status'],
            ]);

            Log::info('Created user account for existing employee', [
                'employee_id' => $employee->id,
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return redirect()->route('admin.employees.index')
                ->with('success', 'Thông tin nhân viên đã được cập nhật thành công. Tài khoản đăng nhập đã được tạo với mật khẩu mặc định: ' . $defaultPassword);
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

        // Find and delete the corresponding user account
        $user = User::where('email', $employee->email)->first();
        if ($user) {
            Log::info('Deleting user account for employee', [
                'employee_id' => $employee->id,
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            $user->delete();
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

        // Update the corresponding user account status
        $user = User::where('email', $employee->email)->first();
        if ($user) {
            $user->status = $employee->status;
            $user->save();

            Log::info('Updated user status for employee', [
                'employee_id' => $employee->id,
                'user_id' => $user->id,
                'email' => $user->email,
                'new_status' => $employee->status
            ]);
        }

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
