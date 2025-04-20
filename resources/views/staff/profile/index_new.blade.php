@extends('layouts.staff_new')

@section('title', 'Hồ sơ cá nhân - Cán bộ viên chức')
@section('page-title', 'Hồ sơ cá nhân')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="bg-white overflow-hidden shadow-xl rounded-2xl mb-8 border border-gray-100">
        <div class="p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row items-center justify-between">
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-r from-pink-500 to-purple-600 flex items-center justify-center mr-4 shadow-lg">
                        <i class="fas fa-user text-black text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Hồ sơ cá nhân</h2>
                        <p class="text-gray-600">Quản lý thông tin cá nhân của bạn</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('staff.profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-black font-medium rounded-lg hover:from-pink-600 hover:to-purple-700 transition shadow-sm">
                        <i class="fas fa-user-edit mr-2"></i>
                        Chỉnh sửa hồ sơ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Information -->
        <div class="lg:col-span-2">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100 mb-8">
                <div class="p-6 sm:p-8">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center mb-6">
                        <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        Thông tin cá nhân
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Họ và tên</h4>
                                <p class="text-gray-800 font-semibold">{{ $staff->first_name }} {{ $staff->last_name }}</p>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Email</h4>
                                <p class="text-gray-800">{{ $staff->email }}</p>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Số điện thoại</h4>
                                <p class="text-gray-800">{{ $staff->phone ?? 'Chưa cập nhật' }}</p>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Giới tính</h4>
                                <p class="text-gray-800">
                                    @if($staff->gender == 'male')
                                        Nam
                                    @elseif($staff->gender == 'female')
                                        Nữ
                                    @else
                                        Khác
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div>
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Ngày sinh</h4>
                                <p class="text-gray-800">{{ $staff->birthday ? $staff->birthday->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Ngày tham gia</h4>
                                <p class="text-gray-800">{{ $staff->created_at->format('d/m/Y') }}</p>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Địa chỉ</h4>
                                <p class="text-gray-800">{{ $staff->address ?? 'Chưa cập nhật' }}</p>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Vai trò</h4>
                                <p class="text-gray-800">
                                    <span class="px-2.5 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                        {{ $staff->role->name ?? 'Chưa phân quyền' }}
                                    </span>
                                </p>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Ngày tham gia</h4>
                                <p class="text-gray-800">{{ $staff->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- University Staff Information -->
            @if($staff->isUniversityStaff())
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100 mb-8">
                <div class="p-6 sm:p-8">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center mb-6">
                        <span class="bg-green-100 text-green-600 p-2 rounded-lg mr-3">
                            <i class="fas fa-university"></i>
                        </span>
                        Thông tin cán bộ viên chức
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Mã cán bộ</h4>
                                <p class="text-gray-800 font-semibold">{{ $staff->staff_id }}</p>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Phòng/Khoa</h4>
                                <p class="text-gray-800">{{ $staff->department }}</p>
                            </div>
                        </div>

                        <div>
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Chức vụ</h4>
                                <p class="text-gray-800">{{ $staff->position ?? 'Chưa cập nhật' }}</p>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Mã nhân viên</h4>
                                <p class="text-gray-800">{{ $staff->employee_code ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
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

            <!-- Statistics -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Thống kê lịch hẹn</h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Tổng số lịch hẹn</h4>
                                <p class="text-xs text-gray-500">Tất cả lịch hẹn đã đặt</p>
                            </div>
                            <span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                {{ $staff->staffAppointments->count() }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Đang chờ xác nhận</h4>
                                <p class="text-xs text-gray-500">Lịch hẹn chưa được xác nhận</p>
                            </div>
                            <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                                {{ $staff->staffAppointments->where('status', 'pending')->count() }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center p-3 bg-indigo-50 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Đã xác nhận</h4>
                                <p class="text-xs text-gray-500">Lịch hẹn đã được xác nhận</p>
                            </div>
                            <span class="px-2.5 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-semibold">
                                {{ $staff->staffAppointments->where('status', 'confirmed')->count() }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Đã hoàn thành</h4>
                                <p class="text-xs text-gray-500">Lịch hẹn đã hoàn thành</p>
                            </div>
                            <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                {{ $staff->staffAppointments->where('status', 'completed')->count() }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Đã hủy</h4>
                                <p class="text-xs text-gray-500">Lịch hẹn đã bị hủy</p>
                            </div>
                            <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                                {{ $staff->staffAppointments->where('status', 'cancelled')->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Thao tác nhanh</h3>

                    <div class="space-y-3">
                        <a href="{{ route('staff.appointments.create') }}" class="flex items-center p-3 bg-pink-50 text-pink-700 rounded-lg hover:bg-pink-100 transition">
                            <i class="fas fa-calendar-plus mr-4 text-pink-500"></i>
                            <span>Tạo lịch hẹn mới</span>
                        </a>

                        <a href="{{ route('staff.work-schedule') }}" class="flex items-center p-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition">
                            <i class="fas fa-calendar-week mr-4 text-purple-500"></i>
                            <span>Xem lịch làm việc</span>
                        </a>

                        <a href="{{ route('staff.profile.change-password') }}" class="flex items-center p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                            <i class="fas fa-key mr-4 text-blue-500"></i>
                            <span>Đổi mật khẩu</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
