@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Kiểm tra gửi email</h1>
                <a href="{{ url('/') }}" class="text-pink-600 hover:text-pink-700">
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <form action="{{ route('mail.test.send') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email nhận (để kiểm tra)
                    </label>
                    <input type="email" name="email" id="email" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"
                           value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Loại email để gửi
                    </label>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="radio" name="type" id="type_simple" value="simple" 
                                   class="focus:ring-pink-500 h-4 w-4 text-pink-600 border-gray-300" checked>
                            <label for="type_simple" class="ml-2 block text-sm text-gray-700">
                                Email đơn giản (kiểm tra kết nối)
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" name="type" id="type_registration" value="registration" 
                                   class="focus:ring-pink-500 h-4 w-4 text-pink-600 border-gray-300">
                            <label for="type_registration" class="ml-2 block text-sm text-gray-700">
                                Email xác nhận đăng ký tài khoản
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" name="type" id="type_appointment" value="appointment" 
                                   class="focus:ring-pink-500 h-4 w-4 text-pink-600 border-gray-300">
                            <label for="type_appointment" class="ml-2 block text-sm text-gray-700">
                                Email xác nhận đặt lịch
                            </label>
                        </div>
                    </div>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        Gửi email kiểm tra
                    </button>
                </div>
            </form>
            
            <div class="mt-8 border-t pt-6">
                <h2 class="text-lg font-medium text-gray-800 mb-3">Hướng dẫn cấu hình email</h2>
                <ol class="list-decimal pl-5 space-y-2 text-gray-700">
                    <li>Mở file <code class="bg-gray-100 px-1 py-0.5 rounded">.env</code> trong thư mục gốc của dự án</li>
                    <li>Cấu hình các thông số SMTP của Gmail:
                        <pre class="bg-gray-100 p-3 rounded-md mt-2 text-sm overflow-auto">
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@ntu-health-booking.com
MAIL_FROM_NAME="${APP_NAME}"</pre>
                    </li>
                    <li>Đối với MAIL_PASSWORD, bạn cần tạo "App Password" từ tài khoản Google:
                        <ul class="list-disc pl-5 mt-1">
                            <li>Đi tới <a href="https://myaccount.google.com/security" target="_blank" class="text-pink-600 hover:underline">Google Account Security</a></li>
                            <li>Bật xác thực 2 bước nếu chưa bật</li>
                            <li>Tìm "Mật khẩu ứng dụng" và tạo mật khẩu mới</li>
                            <li>Sử dụng mật khẩu được tạo để thay thế <code class="bg-gray-100 px-1 py-0.5 rounded">your-app-password</code></li>
                        </ul>
                    </li>
                    <li>Chạy lệnh <code class="bg-gray-100 px-1 py-0.5 rounded">php artisan config:cache</code> để cập nhật cấu hình</li>
                    <li>Khởi động lại server với <code class="bg-gray-100 px-1 py-0.5 rounded">php artisan serve</code></li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection 