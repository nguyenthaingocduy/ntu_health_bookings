<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LoginSimpleController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.simple-login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        $credentials = $request->only('email', 'password');
        
        // Kiểm tra thông tin user
        $user = User::where('email', $credentials['email'])->first();
        
        Log::info('LoginSimple attempt', [
            'email' => $credentials['email'],
            'user_exists' => $user ? true : false,
            'password_ok' => $user ? Hash::check($credentials['password'], $user->password) : false,
            'session_id' => Session::getId()
        ]);
        
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $authenticatedUser = Auth::user();
            
            Log::info('LoginSimple success', [
                'user_id' => $authenticatedUser->id,
                'email' => $authenticatedUser->email,
                'is_authenticated' => Auth::check(),
                'role' => $authenticatedUser->role ? $authenticatedUser->role->name : 'No Role',
                'session_id' => Session::getId()
            ]);
            
            if ($authenticatedUser->role && strtolower($authenticatedUser->role->name) === 'admin') {
                return redirect('/admin/dashboard');
            }
            
            return redirect('/customer/dashboard');
        }
        
        Log::warning('LoginSimple failed', [
            'email' => $credentials['email'],
            'session_id' => Session::getId()
        ]);
        
        return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác'])->withInput($request->only('email', 'remember'));
    }
} 