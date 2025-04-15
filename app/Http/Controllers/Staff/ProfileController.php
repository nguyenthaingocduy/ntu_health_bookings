<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
        return view('staff.profile.index', compact('staff'));
    }

    /**
     * Show the form for editing the staff's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $staff = Auth::user();
        return view('staff.profile.edit', compact('staff'));
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
            'birthday' => 'nullable|date',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($staff->id),
            ],
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
        return view('staff.profile.change-password');
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
