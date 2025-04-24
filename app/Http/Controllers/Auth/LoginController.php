<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        login as protected traitLogin;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'email';
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Kiểm tra session info trước khi đăng nhập (chỉ trong môi trường debug)
        if (config('app.debug')) {
            Log::info('Session info trước khi đăng nhập', [
                'session_id' => Session::getId(),
                'has_session' => $request->hasSession(),
                'session_token' => csrf_token(),
                'cookies' => $request->cookies->all()
            ]);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            // Tạo mới session một cách rõ ràng
            $request->session()->regenerate();

            $user = Auth::user();
            // Kiểm tra session info sau khi đăng nhập (chỉ trong môi trường debug)
            if (config('app.debug')) {
                Log::info('Session info sau khi đăng nhập', [
                    'session_id' => Session::getId(),
                    'auth_check' => Auth::check(),
                    'user_id' => Auth::id(),
                    'cookies' => $request->cookies->all()
                ]);
            }

            // Chuyển hướng dựa vào vai trò
            if ($user->role) {
                $roleName = strtolower($user->role->name);

                if ($roleName === 'admin') {
                    if (config('app.debug')) {
                        Log::info('Redirecting admin user to admin dashboard', [
                            'user_id' => $user->id,
                            'role' => $roleName
                        ]);
                    }
                    return redirect()->route('admin.dashboard');
                } elseif ($roleName === 'staff') {
                    if (config('app.debug')) {
                        Log::info('Redirecting staff user to staff dashboard', [
                            'user_id' => $user->id,
                            'role' => $roleName
                        ]);
                    }
                    return redirect()->route('staff.dashboard');
                } elseif ($roleName === 'receptionist') {
                    if (config('app.debug')) {
                        Log::info('Redirecting receptionist user to receptionist dashboard', [
                            'user_id' => $user->id,
                            'role' => $roleName
                        ]);
                    }
                    return redirect()->route('receptionist.dashboard');
                } elseif ($roleName === 'technician') {
                    if (config('app.debug')) {
                        Log::info('Redirecting technician user to technician dashboard', [
                            'user_id' => $user->id,
                            'role' => $roleName
                        ]);
                    }
                    return redirect()->route('technician.dashboard');
                }
            }

            if (config('app.debug')) {
                Log::info('Redirecting user to customer dashboard', [
                    'user_id' => $user->id,
                    'role' => $user->role ? $user->role->name : 'No Role'
                ]);
            }
            return redirect()->route('customer.dashboard');
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        // Ghi log đăng nhập thất bại (chỉ trong môi trường debug)
        if (config('app.debug')) {
            Log::error('Đăng nhập thất bại', [
                'email' => $request->email,
                'session_id' => Session::getId()
            ]);
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        $email = Auth::user() ? Auth::user()->email : 'Unknown';

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if (config('app.debug')) {
            Log::info('User logged out', [
                'user_id' => $userId,
                'email' => $email
            ]);
        }

        return redirect('/login');
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }
}
