<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Rules\MinimumAge;

class ProfileController extends Controller
{
    /**
     * Display the staff's profile.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $staff = Auth::user();
        return view('staff.profile.index_new', compact('staff'));
    }

    /**
     * Show the form for editing the staff's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $staff = Auth::user();
        return view('staff.profile.edit_new', compact('staff'));
    }

    /**
     * Update the staff's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $staff = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'birthday' => [
                'required',
                'date',
                new MinimumAge(18)
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($staff->id),
            ],
        ], [
            'birthday.required' => 'Vui lòng nhập ngày sinh.',
            'birthday.date' => 'Ngày sinh không hợp lệ.',
            'birthday.before_or_equal' => 'Bạn phải đủ 18 tuổi trở lên để sử dụng hệ thống.',
        ]);

        $staff->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'email' => $request->email,
        ]);

        return redirect()->route('staff.profile.index')
            ->with('success', 'Thông tin cá nhân đã được cập nhật thành công.');
    }

    /**
     * Show the form for changing the staff's password.
     *
     * @return \Illuminate\View\View
     */
    public function changePassword()
    {
        return view('staff.profile.change-password_new');
    }

    /**
     * Update the staff's password.
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

        $staff = Auth::user();

        if (!Hash::check($request->current_password, $staff->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        $staff->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('staff.profile.index')
            ->with('success', 'Mật khẩu đã được cập nhật thành công.');
    }
}
