<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function index(Request $request)
    {
        $query = Clinic::withCount(['services', 'employees']);

        // Apply filters if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clinics = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate statistics
        $totalClinics = Clinic::count();
        $activeClinics = Clinic::where('status', 'active')->count();
        $inactiveClinics = Clinic::where('status', 'inactive')->count();
        $totalEmployees = Employee::count();

        $statistics = [
            'total' => $totalClinics,
            'active' => $activeClinics,
            'inactive' => $inactiveClinics,
            'total_employees' => $totalEmployees,
        ];

        return view('admin.clinics.index', compact('clinics', 'statistics'));
    }

    public function create()
    {
        return view('admin.clinics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:clinics,email',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'address', 'phone', 'email', 'description', 'status']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/clinics'), $imageName);
            $data['image_url'] = 'images/clinics/' . $imageName;
        }

        Clinic::create($data);

        return redirect()->route('admin.clinics.index')
            ->with('success', 'Phòng khám đã được thêm thành công.');
    }

    public function edit($id)
    {
        $clinic = Clinic::findOrFail($id);
        return view('admin.clinics.edit', compact('clinic'));
    }

    public function update(Request $request, $id)
    {
        $clinic = Clinic::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:clinics,email,' . $clinic->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'address', 'phone', 'email', 'description', 'status']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($clinic->image_url && file_exists(public_path($clinic->image_url))) {
                unlink(public_path($clinic->image_url));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/clinics'), $imageName);
            $data['image_url'] = 'images/clinics/' . $imageName;
        }

        $clinic->update($data);

        return redirect()->route('admin.clinics.index')
            ->with('success', 'Thông tin phòng khám đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $clinic = Clinic::findOrFail($id);

        // Kiểm tra xem phòng khám có nhân viên hoặc dịch vụ nào không
        if ($clinic->employees()->count() > 0 || $clinic->services()->count() > 0) {
            return back()->with('error', 'Không thể xóa phòng khám này vì đã có nhân viên hoặc dịch vụ.');
        }

        $clinic->delete();

        return redirect()->route('admin.clinics.index')
            ->with('success', 'Phòng khám đã được xóa thành công.');
    }

    /**
     * Display the specified clinic.
     */
    public function show($id)
    {
        $clinic = Clinic::with(['employees', 'services'])
            ->withCount(['employees', 'services'])
            ->findOrFail($id);

        // Get total counts for statistics
        $total_employees = Employee::count();
        $total_services = Service::count();

        // Ensure we don't have zero values to avoid division by zero
        $total_employees = max(1, $total_employees);
        $total_services = max(1, $total_services);

        return view('admin.clinics.show', compact('clinic', 'total_employees', 'total_services'));
    }

    /**
     * Toggle the status of the specified clinic.
     */
    public function toggleStatus($id)
    {
        $clinic = Clinic::findOrFail($id);
        $clinic->status = $clinic->status === 'active' ? 'inactive' : 'active';
        $clinic->save();

        return redirect()->route('admin.clinics.index')
            ->with('success', 'Trạng thái phòng khám đã được cập nhật thành công!');
    }
}
