<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        // Kiểm tra và ghi log thông tin xác thực và cookie
        Log::info('Authenticate middleware: Chi tiết session và auth', [
            'is_authenticated' => Auth::check(),
            'session_id' => Session::getId(),
            'user_id' => Auth::id(),
            'url' => $request->url(),
            'cookie_names' => array_keys($request->cookies->all()),
            'session_cookie_name' => config('session.cookie'),
            'has_session_cookie' => $request->cookies->has(config('session.cookie'))
        ]);
        
        return parent::handle($request, $next, ...$guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Ghi log khi người dùng chưa đăng nhập
        Log::info('Authenticate middleware: Người dùng chưa đăng nhập, chuyển hướng đến trang đăng nhập', [
            'session_id' => Session::getId(),
            'url' => $request->url()
        ]);
        
        return $request->expectsJson() ? null : route('login');
    }
} 