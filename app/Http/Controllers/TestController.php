<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class TestController extends Controller
{
    public function testLogin(Request $request)
    {
        // Kiểm tra đăng nhập hiện tại
        if (Auth::check()) {
            Log::info('Đã đăng nhập', [
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email
            ]);
            
            return redirect('/customer/dashboard');
        }
        
        // Nếu có thông tin đăng nhập
        if ($request->has('email') && $request->has('password')) {
            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                return "Không tìm thấy user với email " . $request->email;
            }
            
            // Đăng nhập
            Auth::login($user);
            
            if (Auth::check()) {
                Log::info('Đăng nhập thành công trong test', [
                    'user_id' => Auth::id()
                ]);
                
                return redirect('/customer/dashboard');
            } else {
                return "Đăng nhập thất bại";
            }
        }
        
        // Form đăng nhập đơn giản
        return '<form method="POST">
            <input type="hidden" name="_token" value="' . csrf_token() . '">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Đăng nhập</button>
        </form>';
    }
} 