@extends('layouts.le-tan')

@section('title', 'Thêm khách hàng mới')

@section('header', 'Thêm khách hàng mới')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Thêm khách hàng mới</h1>
            <p class="text-sm text-gray-500 mt-1">Tạo tài khoản cho khách hàng mới</p>
        </div>
        <a href="{{ route('le-tan.customers.index') }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Quay lại
        </a>
    </div>

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <form action="{{ route('le-tan.customers.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Họ <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" id="first_name" class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 focus:border-transparent @error('first_name') border-red-500 @enderror" value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Tên <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" id="last_name" class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 focus:border-transparent @error('last_name') border-red-500 @enderror" value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 focus:border-transparent @error('email') border-red-500 @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="phone" class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 focus:border-transparent @error('phone') border-red-500 @enderror" value="{{ old('phone') }}" required>
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 focus:border-transparent @error('password') border-red-500 @enderror" required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Xác nhận mật khẩu <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 focus:border-transparent" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Ngày sinh</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 focus:border-transparent @error('date_of_birth') border-red-500 @enderror" value="{{ old('date_of_birth') }}">
                        @error('date_of_birth')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Giới tính <span class="text-red-500">*</span></label>
                        <select id="gender" name="gender" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm rounded-md @error('gender') border-red-500 @enderror" required>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                    <textarea id="address" name="address" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 focus:border-transparent @error('address') border-red-500 @enderror" placeholder="Nhập địa chỉ khách hàng">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Tạo khách hàng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
