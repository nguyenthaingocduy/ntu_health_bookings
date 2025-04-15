<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class TestLoginController extends Controller
{
    public function login(Request $request)
    {
        // Nếu là GET request, hiển thị form đăng nhập
        if ($request->isMethod('get')) {
            return view('auth.test-login');
        }

        // Lấy thông tin từ form
        $email = $request->input('email');
        $password = $request->input('password');
        
        // Ghi log thông tin đăng nhập
        Log::info('TestLogin đang thử đăng nhập', [
            'email' => $email
        ]);
        
        // Kiểm tra user tồn tại
        $user = User::where('email', $email)->first();
        if (!$user) {
            Log::error('TestLogin - Không tìm thấy user', ['email' => $email]);
            return redirect('login')->withErrors(['email' => 'Email không tồn tại']);
        }
        
        // Kiểm tra mật khẩu
        if (!Hash::check($password, $user->password)) {
            Log::error('TestLogin - Mật khẩu không đúng', ['email' => $email]);
            return redirect('login')->withErrors(['email' => 'Mật khẩu không chính xác']);
        }
        
        // Đăng nhập trực tiếp
        Auth::login($user);
        
        // Tạo mới session
        $request->session()->regenerate();
        
        // Kiểm tra đăng nhập thành công
        if (Auth::check()) {
            Log::info('TestLogin thành công', [
                'user_id' => Auth::id(), 
                'email' => Auth::user()->email
            ]);
            
            // Chuyển hướng trực tiếp không qua middleware
            return redirect('/test-dashboard');
        }
        
        // Nếu đăng nhập thất bại
        Log::error('TestLogin - Đăng nhập thất bại không rõ nguyên nhân', ['email' => $email]);
        return redirect('login')->withErrors(['email' => 'Đăng nhập thất bại']);
    }
} 