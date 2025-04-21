@extends('layouts.staff_new')

@section('title', 'Chỉnh sửa thông tin khách hàng - Cán bộ viên chức')
@section('page-title', 'Chỉnh sửa thông tin khách hàng')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-xl font-semibold text-gray-800">Chỉnh sửa thông tin khách hàng</h3>
            <p class="text-sm text-gray-500 mt-1">Cập nhật thông tin khách hàng cho lịch hẹn #{{ substr($appointment->id, 0, 8) }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('staff.appointments.show', $appointment->id) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-150 flex items-center shadow-sm border border-gray-200">
                <svg class="w-4 h-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
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

    <div class="grid gap-6 mb-8 md:grid-cols-3">
        <!-- Customer Form -->
        <div class="col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-pink-50 to-pink-100 border-b border-gray-200 px-5 py-4">
                    <h4 class="font-semibold text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-2.5 text-pink-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                        Thông tin khách hàng
                    </h4>
                </div>
                <div class="p-6">
                    <form action="{{ route('staff.appointments.update-customer', $appointment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid gap-6 mb-8 md:grid-cols-2">
                            <div class="relative">
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Họ</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $appointment->customer->first_name) }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors" required>
                                @error('first_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="relative">
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Tên</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $appointment->customer->last_name) }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors" required>
                                @error('last_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="relative">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <div class="relative">
                                    <input type="email" id="email" value="{{ $appointment->customer->email }}"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-500" disabled>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Email không thể thay đổi</p>
                            </div>

                            <div class="relative">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                                <div class="relative">
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $appointment->customer->phone) }}"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors" required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="relative">
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Giới tính</label>
                                <div class="relative">
                                    <select id="gender" name="gender" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors appearance-none">
                                        <option value="male" {{ old('gender', $appointment->customer->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ old('gender', $appointment->customer->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="other" {{ old('gender', $appointment->customer->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('gender')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="relative">
                                <label for="birthday" class="block text-sm font-medium text-gray-700 mb-2">Ngày sinh</label>
                                <div class="relative">
                                    <input type="text" id="birthday" name="birthday" value="{{ old('birthday', $appointment->customer->birthday) }}"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('birthday')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                            <div class="relative">
                                <input type="text" id="address" name="address" value="{{ old('address', $appointment->customer->address) }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors duration-150 flex items-center shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Cập nhật thông tin khách hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Appointment Info -->
        <div class="col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-pink-50 to-pink-100 border-b border-gray-200 px-5 py-4">
                    <h4 class="font-semibold text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-2.5 text-pink-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        Thông tin lịch hẹn
                    </h4>
                </div>
                <div class="p-6">
                    <div class="space-y-5">
                        <div class="flex items-start">
                            <div class="bg-pink-100 p-2.5 rounded-lg mr-4 shadow-sm">
                                <svg class="w-4 h-4 text-pink-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Mã lịch hẹn</p>
                                <p class="font-medium text-gray-800 text-base">{{ substr($appointment->id, 0, 8) }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-pink-100 p-2.5 rounded-lg mr-4 shadow-sm">
                                <svg class="w-4 h-4 text-pink-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.168 1.168a4 4 0 01-2.929 6.54h-2.118a4 4 0 01-2.227-.616c-.569-.354-1.073-.862-1.427-1.427a4.02 4.02 0 01-.616-2.227c0-1.11.45-2.118 1.17-2.83l1.168-1.168A3 3 0 009 8.172z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Dịch vụ</p>
                                <p class="font-medium text-gray-800 text-base">{{ $appointment->service->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-pink-100 p-2.5 rounded-lg mr-4 shadow-sm">
                                <svg class="w-4 h-4 text-pink-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Ngày hẹn</p>
                                <p class="font-medium text-gray-800 text-base">{{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-pink-100 p-2.5 rounded-lg mr-4 shadow-sm">
                                <svg class="w-4 h-4 text-pink-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Giờ hẹn</p>
                                <p class="font-medium text-gray-800 text-base">{{ $appointment->timeAppointment->time }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-pink-100 p-2.5 rounded-lg mr-4 shadow-sm">
                                <svg class="w-4 h-4 text-pink-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Trạng thái</p>
                                <div>
                                    @if($appointment->status == 'pending')
                                    <span class="px-2.5 py-1.5 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                        Chờ xác nhận
                                    </span>
                                    @elseif($appointment->status == 'confirmed')
                                    <span class="px-2.5 py-1.5 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                        Đã xác nhận
                                    </span>
                                    @elseif($appointment->status == 'completed')
                                    <span class="px-2.5 py-1.5 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        Đã hoàn thành
                                    </span>
                                    @elseif($appointment->status == 'cancelled')
                                    <span class="px-2.5 py-1.5 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                        Đã hủy
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('staff.appointments.edit', $appointment->id) }}" class="block w-full px-5 py-3 bg-pink-500 text-white text-center rounded-lg hover:bg-pink-600 transition-colors duration-150 flex items-center justify-center shadow-md mt-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Chỉnh sửa lịch hẹn
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date picker for birthday
        flatpickr("#birthday", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            locale: "vn",
            disableMobile: "true"
        });
    });
</script>
@endsection
