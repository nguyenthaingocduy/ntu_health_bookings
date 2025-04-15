<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClinicController extends Controller
{
    public function index()
    {
        $clinics = Clinic::withCount(['services', 'employees'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.clinics.index', compact('clinics'));
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
        ]);

        Clinic::create([
            'id' => Str::uuid(),
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

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
        ]);

        $clinic->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

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
}
