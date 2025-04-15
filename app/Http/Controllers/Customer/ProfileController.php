<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show()
    {
        $customer = Auth::user();
        
        // Lấy danh sách lịch hẹn của người dùng, sắp xếp theo thời gian mới nhất
        $appointments = \App\Models\Appointment::where('customer_id', $customer->id)
            ->with(['service', 'timeAppointment'])
            ->orderBy('date_appointments', 'desc')
            ->get();
        
        return view('customer.profile.show', compact('customer', 'appointments'));
    }
    
    public function edit()
    {
        $customer = Auth::user();
        return view('customer.profile.edit', compact('customer'));
    }
    
    public function update(Request $request)
    {
        $customer = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);
        
        // Kiểm tra mật khẩu hiện tại nếu người dùng muốn đổi mật khẩu
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $customer->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
            }
        }
        
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'birthday' => $request->date_of_birth,
            'province_id' => $request->province_id ?? $customer->province_id,
            'district_id' => $request->district_id ?? $customer->district_id,
            'ward_id' => $request->ward_id ?? $customer->ward_id,
        ];
        
        if ($request->filled('new_password')) {
            $data['password'] = Hash::make($request->new_password);
        }
        
        // Sử dụng DB để cập nhật trực tiếp
        DB::table('users')->where('id', $customer->id)->update($data);
        
        return redirect()->route('customer.profile.show')
            ->with('success', 'Thông tin cá nhân đã được cập nhật thành công.');
    }
}
