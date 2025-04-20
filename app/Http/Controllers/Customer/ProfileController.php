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
        ]);

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

        // Sử dụng DB để cập nhật trực tiếp
        DB::table('users')->where('id', $customer->id)->update($data);

        return redirect()->route('customer.profile.show')
            ->with('success', 'Thông tin cá nhân đã được cập nhật thành công.');
    }

    /**
     * Show the form for changing password.
     *
     * @return \Illuminate\View\View
     */
    public function changePassword()
    {
        return view('customer.profile.change-password');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        DB::table('users')->where('id', $user->id)->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('customer.profile.show')
            ->with('success', 'Mật khẩu đã được cập nhật thành công.');
    }

    /**
     * Show the notification settings form.
     *
     * @return \Illuminate\View\View
     */
    public function notificationSettings()
    {
        return view('customer.profile.notification-settings');
    }

    /**
     * Update the user's notification settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        DB::table('users')->where('id', $user->id)->update([
            'email_notifications_enabled' => $request->has('email_notifications_enabled'),
            'notify_appointment_confirmation' => $request->has('notify_appointment_confirmation'),
            'notify_appointment_reminder' => $request->has('notify_appointment_reminder'),
            'notify_appointment_cancellation' => $request->has('notify_appointment_cancellation'),
            'notify_promotions' => $request->has('notify_promotions'),
        ]);

        return redirect()->route('customer.profile.notification-settings')
            ->with('success', 'Cài đặt thông báo đã được cập nhật thành công.');
    }
}
