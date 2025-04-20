@extends('layouts.staff_new')

@section('title', 'Đổi mật khẩu - Cán bộ viên chức')
@section('page-title', 'Đổi mật khẩu')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="bg-white overflow-hidden shadow-xl rounded-2xl mb-8 border border-gray-100">
        <div class="p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row items-center justify-between">
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-r from-pink-500 to-purple-600 flex items-center justify-center mr-4 shadow-lg">
                        <i class="fas fa-key text-black text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Đổi mật khẩu</h2>
                        <p class="text-gray-600">Cập nhật mật khẩu đăng nhập của bạn</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('staff.profile.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition shadow-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Change Password Form -->
        <div class="lg:col-span-2">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100 mb-8">
                <div class="p-6 sm:p-8">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center mb-6">
                        <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                            <i class="fas fa-lock"></i>
                        </span>
                        Thay đổi mật khẩu
                    </h3>
                    
                    <form action="{{ route('staff.profile.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu hiện tại <span class="text-red-500">*</span></label>
                            <input type="password" id="current_password" name="current_password" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới <span class="text-red-500">*</span></label>
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Mật khẩu phải có ít nhất 8 ký tự.</p>
                        </div>
                        
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu mới <span class="text-red-500">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                        </div>
                        
                        <div class="mt-8">
                            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-black font-medium rounded-lg hover:from-pink-600 hover:to-purple-700 transition shadow-md">
                                <i class="fas fa-key mr-2"></i> Cập nhật mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Profile Photo -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
                <div class="p-6 text-center">
                    <div class="w-32 h-32 mx-auto rounded-full bg-gradient-to-r from-pink-100 to-purple-100 flex items-center justify-center mb-4 border-4 border-white shadow-lg">
                        <span class="text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-600">
                            {{ substr(Auth::user()->first_name, 0, 1) }}
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>
                    <p class="text-gray-500 mb-4">{{ Auth::user()->email }}</p>
                    
                    <div class="inline-flex px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                        <i class="fas fa-user-tie mr-2"></i> {{ Auth::user()->role->name ?? 'Cán bộ viên chức' }}
                    </div>
                </div>
            </div>
            
            <!-- Guidelines -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Hướng dẫn</h3>
                    
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                            Mật khẩu an toàn
                        </h4>
                        <p class="text-sm text-gray-600 mb-2">Một mật khẩu an toàn nên có:</p>
                        <ul class="space-y-2 text-sm text-gray-600 pl-6 list-disc">
                            <li>Ít nhất 8 ký tự</li>
                            <li>Kết hợp chữ hoa và chữ thường</li>
                            <li>Bao gồm số và ký tự đặc biệt</li>
                            <li>Không sử dụng thông tin cá nhân dễ đoán</li>
                            <li>Không trùng với mật khẩu đã sử dụng trước đây</li>
                        </ul>
                    </div>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <span class="font-bold">Lưu ý:</span> Sau khi đổi mật khẩu, bạn sẽ cần đăng nhập lại bằng mật khẩu mới.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
