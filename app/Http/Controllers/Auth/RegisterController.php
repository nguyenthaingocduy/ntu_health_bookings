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
            Log::info('Bắt đầu gửi email xác nhận đăng ký cho: ' . $user->email);
            Mail::to($user->email)->send(new \App\Mail\UserRegistrationMail($user));
            Log::info('Đã gửi email xác nhận đăng ký thành công cho: ' . $user->email);
        } catch (\Exception $e) {
            // Ghi log lỗi nhưng không ngăn chặn đăng ký thành công
            Log::error('Không thể gửi email xác nhận đăng ký: ' . $e->getMessage());
        }
        
        return redirect()->route('customer.dashboard');
    }
}
