<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CustomerType;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Rules\MinimumAge;
use App\Services\EmailNotificationService;
use App\Services\EmailService;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/customer/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'address' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female,other'],
            'birthday' => [
                'required',
                'date',
                new MinimumAge(18)
            ],
        ], [
            'birthday.before_or_equal' => 'Bạn phải đủ 18 tuổi trở lên để đăng ký tài khoản.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Tìm role Customer - không phân biệt chữ hoa chữ thường
        $role = Role::whereRaw('LOWER(name) = ?', [strtolower('Customer')])->first();

        // Nếu không tìm thấy, tạo mới
        if (!$role) {
            $role = Role::create([
                'id' => Str::uuid(),
                'name' => 'Customer',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tìm customer type Regular
        $type = CustomerType::whereRaw('LOWER(type_name) = ?', [strtolower('Regular')])->first();

        // Nếu không tìm thấy, tạo mới
        if (!$type) {
            $type = CustomerType::create([
                'id' => Str::uuid(),
                'type_name' => 'Regular',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return User::create([
            'id' => Str::uuid(),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'gender' => $data['gender'],
            'birthday' => $data['birthday'],
            'address' => $data['address'],
            'password' => Hash::make($data['password']),
            'role_id' => $role->id,
            'type_id' => $type->id,
            'province_id' => null,
            'district_id' => null,
            'ward_id' => null,
        ]);
    }

    protected function registered(Request $request, $user)
    {
        // Gửi email xác nhận đăng ký tài khoản
        try {
            if (config('app.debug')) {
                Log::info('Bắt đầu gửi email xác nhận đăng ký cho: ' . $user->email);
            }

            // Gửi email bằng cả ba phương thức để đảm bảo thành công
            $emailSent = false;

            // Phương thức 1: Sử dụng EmailService mới
            try {
                $emailService = new EmailService();
                $result = $emailService->sendRegistrationConfirmation($user);

                if ($result) {
                    if (config('app.debug')) {
                        Log::info('Đã gửi email xác nhận đăng ký thành công (new service) cho: ' . $user->email);
                    }
                    $emailSent = true;
                }
            } catch (\Exception $newServiceError) {
                if (config('app.debug')) {
                    Log::warning('Không thể gửi email qua service mới: ' . $newServiceError->getMessage());
                }
            }

            // Phương thức 2: Sử dụng EmailNotificationService cũ (nếu phương thức 1 thất bại)
            if (!$emailSent) {
                try {
                    $legacyEmailService = new EmailNotificationService();
                    $notification = $legacyEmailService->sendRegistrationConfirmation($user);

                    if ($notification && $notification->status === 'sent') {
                        if (config('app.debug')) {
                            Log::info('Đã gửi email xác nhận đăng ký thành công (legacy service) cho: ' . $user->email);
                        }
                        $emailSent = true;
                    }
                } catch (\Exception $legacyServiceError) {
                    if (config('app.debug')) {
                        Log::warning('Không thể gửi email qua service cũ: ' . $legacyServiceError->getMessage());
                    }
                }
            }

            // Phương thức 3: Sử dụng Mail facade trực tiếp (nếu cả hai phương thức trước đều thất bại)
            if (!$emailSent) {
                try {
                    Mail::to($user->email)->send(new \App\Mail\UserRegistrationMail($user));
                    if (config('app.debug')) {
                        Log::info('Đã gửi email xác nhận đăng ký thành công (direct) cho: ' . $user->email);
                    }
                    $emailSent = true;
                } catch (\Exception $directMailError) {
                    if (config('app.debug')) {
                        Log::error('Không thể gửi email trực tiếp: ' . $directMailError->getMessage());
                    }
                }
            }

            // Ghi log kết quả cuối cùng
            if (!$emailSent) {
                if (config('app.debug')) {
                    Log::error('Không thể gửi email xác nhận đăng ký cho: ' . $user->email . ' bằng cả ba phương thức');
                }
            }
        } catch (\Exception $e) {
            // Ghi log lỗi nhưng không ngăn chặn đăng ký thành công
            if (config('app.debug')) {
                Log::error('Không thể gửi email xác nhận đăng ký: ' . $e->getMessage());
            }
        }

        return redirect()->route('customer.dashboard');
    }
}
