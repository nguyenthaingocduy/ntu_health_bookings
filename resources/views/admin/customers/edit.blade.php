@extends('layouts.admin')

@section('title', 'Chỉnh sửa khách hàng')
@section('header', 'Chỉnh sửa khách hàng')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chỉnh sửa khách hàng</h1>
            <p class="text-sm text-gray-500 mt-1">Cập nhật thông tin khách hàng</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.customers.show', $customer->id) }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Có lỗi xảy ra:</h3>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-pink-500 to-purple-600 px-6 py-4">
            <h2 class="text-white text-lg font-semibold">Thông tin khách hàng</h2>
        </div>
        
        <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Họ -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Họ <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="first_name" id="first_name" 
                        value="{{ old('first_name', $customer->first_name) }}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                        required>
                </div>

                <!-- Tên -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Tên <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="last_name" id="last_name" 
                        value="{{ old('last_name', $customer->last_name) }}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                        required>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" 
                        value="{{ old('email', $customer->email) }}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                        required>
                </div>

                <!-- Số điện thoại -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Số điện thoại <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" name="phone" id="phone" 
                        value="{{ old('phone', $customer->phone) }}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                        required>
                </div>

                <!-- Ngày sinh -->
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                        Ngày sinh
                    </label>
                    <input type="date" name="date_of_birth" id="date_of_birth" 
                        value="{{ old('date_of_birth', $customer->date_of_birth) }}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                </div>

                <!-- Địa chỉ -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Địa chỉ
                    </label>
                    <textarea name="address" id="address" rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                        placeholder="Nhập địa chỉ...">{{ old('address', $customer->address) }}</textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.customers.show', $customer->id) }}" 
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
                    Hủy
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:from-pink-600 hover:to-purple-700 transition-all duration-150 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
