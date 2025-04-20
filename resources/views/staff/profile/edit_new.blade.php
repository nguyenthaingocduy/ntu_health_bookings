@extends('layouts.staff_new')

@section('title', 'Chỉnh sửa thông tin cá nhân - Cán bộ viên chức')
@section('page-title', 'Chỉnh sửa thông tin cá nhân')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="bg-white overflow-hidden shadow-xl rounded-2xl mb-8 border border-gray-100">
        <div class="p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row items-center justify-between">
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-r from-pink-500 to-purple-600 flex items-center justify-center mr-4 shadow-lg">
                        <i class="fas fa-user-edit text-black w-5 h-5 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Chỉnh sửa thông tin cá nhân</h2>
                        <p class="text-gray-600">Cập nhật thông tin cá nhân của bạn</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('staff.profile.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition shadow-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Edit Form -->
        <div class="lg:col-span-2">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100 mb-8">
                <div class="p-6 sm:p-8">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center mb-6">
                        <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        Thông tin cá nhân
                    </h3>

                    <form action="{{ route('staff.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Họ <span class="text-red-500">*</span></label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $staff->first_name) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('first_name') border-red-500 @enderror">
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Tên <span class="text-red-500">*</span></label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $staff->last_name) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('last_name') border-red-500 @enderror">
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email', $staff->email) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $staff->phone) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ <span class="text-red-500">*</span></label>
                            <input type="text" id="address" name="address" value="{{ old('address', $staff->address) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('address') border-red-500 @enderror">
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Giới tính <span class="text-red-500">*</span></label>
                            <select id="gender" name="gender" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('gender') border-red-500 @enderror">
                                <option value="male" {{ old('gender', $staff->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ old('gender', $staff->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ old('gender', $staff->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="birthday" class="block text-sm font-medium text-gray-700 mb-1">Ngày sinh <span class="text-red-500">*</span> <span class="text-red-500 text-xs">(Phải trên 18 tuổi)</span></label>
                            <input type="date" id="birthday" name="birthday" value="{{ old('birthday', $staff->birthday ? $staff->birthday->format('Y-m-d') : '') }}" required
                                max="{{ \Carbon\Carbon::now()->subYears(18)->format('Y-m-d') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('birthday') border-red-500 @enderror">
                            @error('birthday')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-8">
                            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-black font-medium rounded-lg hover:from-pink-600 hover:to-purple-700 transition shadow-md">
                                <i class="fas fa-save mr-2"></i> Lưu thay đổi
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
                            {{ substr($staff->first_name, 0, 1) }}
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $staff->first_name }} {{ $staff->last_name }}</h3>
                    <p class="text-gray-500 mb-4">{{ $staff->email }}</p>

                    <div class="inline-flex px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                        <i class="fas fa-user-tie mr-2"></i> {{ $staff->role->name ?? 'Cán bộ viên chức' }}
                    </div>
                </div>
            </div>

            <!-- Guidelines -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Hướng dẫn</h3>

                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Thông tin cần thiết
                        </h4>
                        <ul class="space-y-2 text-sm text-gray-600 pl-6 list-disc">
                            <li>Họ và tên: Nhập đầy đủ họ và tên của bạn</li>
                            <li>Email: Địa chỉ email dùng để đăng nhập và nhận thông báo</li>
                            <li>Số điện thoại: Số điện thoại liên hệ</li>
                            <li>Địa chỉ: Địa chỉ hiện tại của bạn</li>
                            <li>Giới tính: Chọn giới tính của bạn</li>
                        </ul>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-blue-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <span class="font-bold">Lưu ý:</span> Thông tin cá nhân của bạn sẽ được bảo mật và chỉ sử dụng cho mục đích đặt lịch khám sức khỏe.
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
