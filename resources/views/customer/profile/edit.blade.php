@extends('layouts.app')

@section('title', 'Chỉnh sửa thông tin cá nhân')

@section('content')
<div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white py-12">
    <div class="container mx-auto px-6">
        <h1 class="text-3xl font-bold">Chỉnh sửa thông tin cá nhân</h1>
        <p class="mt-2">Cập nhật thông tin của bạn</p>
    </div>
</div>

<div class="container mx-auto px-6 py-10">
    <div class="max-w-4xl mx-auto">
        @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <div class="font-medium">Đã xảy ra lỗi:</div>
            <ul class="ml-4 mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <a href="{{ route('customer.profile.show') }}" class="text-gray-600 hover:text-pink-600 mr-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="text-xl font-semibold">Thông tin cá nhân</h2>
                </div>
            </div>

            <form action="{{ route('customer.profile.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-gray-700 font-medium mb-2">Họ</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $customer->first_name) }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    </div>
                    
                    <div>
                        <label for="last_name" class="block text-gray-700 font-medium mb-2">Tên</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $customer->last_name) }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" id="email" value="{{ $customer->email }}" 
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50" disabled>
                        <p class="text-sm text-gray-500 mt-1">Không thể thay đổi email</p>
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-gray-700 font-medium mb-2">Số điện thoại</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    </div>
                    
                    <div>
                        <label for="gender" class="block text-gray-700 font-medium mb-2">Giới tính</label>
                        <select name="gender" id="gender" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                            <option value="male" {{ old('gender', $customer->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                            <option value="female" {{ old('gender', $customer->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                            <option value="other" {{ old('gender', $customer->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="date_of_birth" class="block text-gray-700 font-medium mb-2">Ngày sinh</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $customer->birthday ? date('Y-m-d', strtotime($customer->birthday)) : '') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="address" class="block text-gray-700 font-medium mb-2">Địa chỉ</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $customer->address) }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 mt-8 pt-8">
                    <h3 class="text-lg font-semibold mb-4">Đổi mật khẩu</h3>
                    <p class="text-gray-600 mb-4 text-sm">Để trống nếu bạn không muốn thay đổi mật khẩu</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="current_password" class="block text-gray-700 font-medium mb-2">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" id="current_password" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                        </div>
                        
                        <div></div>
                        
                        <div>
                            <label for="new_password" class="block text-gray-700 font-medium mb-2">Mật khẩu mới</label>
                            <input type="password" name="new_password" id="new_password" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                        </div>
                        
                        <div>
                            <label for="new_password_confirmation" class="block text-gray-700 font-medium mb-2">Xác nhận mật khẩu mới</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end">
                    <a href="{{ route('customer.profile.show') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg mr-4 hover:bg-gray-300 transition">
                        Hủy
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white px-6 py-2 rounded-lg hover:shadow-lg hover:shadow-pink-200 transition-all">
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 