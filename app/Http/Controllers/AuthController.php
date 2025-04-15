<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role->name === 'Admin' || $user->role->name === 'Employee') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('customer.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ]);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
        ]);

        $customerRole = Role::where('name', 'customer')->first();
        $defaultType = CustomerType::where('type_name', 'regular')->first();

        if (!$customerRole) {
            $customerRole = Role::create([
                'id' => Str::uuid(),
                'name' => 'customer'
            ]);
        }

        if (!$defaultType) {
            $defaultType = CustomerType::create([
                'id' => Str::uuid(),
                'type_name' => 'regular'
            ]);
        }

        $customer = Customer::create([
            'id' => Str::uuid(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'role_id' => $customerRole->id,
            'type_id' => $defaultType->id,
            'province_id' => $request->province_id ?? '',
            'district_id' => $request->district_id ?? '',
            'ward_id' => $request->ward_id ?? '',
        ]);

        Auth::login($customer);

        return redirect()->route('customer.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
