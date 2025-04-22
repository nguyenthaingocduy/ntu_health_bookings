@extends('layouts.staff_new')

@section('title', 'Chi tiết lịch hẹn - Cán bộ viên chức')
@section('page-title', 'Chi tiết lịch hẹn')

@section('content')
<div class="container px-4 md:px-6 mx-auto grid">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div class="bg-gradient-to-r dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 w-full md:w-auto">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Chi tiết lịch hẹn</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Mã lịch hẹn: <span class="font-semibold text-black-600 dark:text-black-400">{{ substr($appointment->id, 0, 8) }}</span></p>
        </div>
        <div class="flex flex-wrap gap-3 w-full md:w-auto">
            <a href="{{ route('staff.appointments.edit', $appointment->id) }}" class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-black rounded-lg hover:from-amber-600 hover:to-orange-600 shadow-lg shadow-amber-200 dark:shadow-none transition-all duration-300 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Chỉnh sửa
            </a>
            <a href="{{ route('staff.appointments.index') }}" class="px-4 py-2.5 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-300 flex items-center shadow-md">
                <svg class="w-4 h-4 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 mb-6 rounded-r-lg animate-fadeIn" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <p>{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 mb-6 rounded-r-lg animate-fadeIn" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <p>{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <div class="grid gap-6 mb-8 md:grid-cols-3">
        <!-- Main Content -->
        <div class="col-span-full md:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-lg">
                <!-- Appointment Status Header -->
                <div class="px-6 py-5 border-b
                    @if($appointment->status == 'pending') bg-gradient-to-r from-amber-50 to-yellow-100 dark:from-amber-900/20 dark:to-yellow-900/30 border-amber-200 dark:border-amber-800
                    @elseif($appointment->status == 'confirmed') bg-gradient-to-r from-emerald-50 to-green-100 dark:from-emerald-900/20 dark:to-green-900/30 border-emerald-200 dark:border-emerald-800
                    @elseif($appointment->status == 'completed') bg-gradient-to-r from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/30 border-blue-200 dark:border-blue-800
                    @elseif($appointment->status == 'cancelled') bg-gradient-to-r from-red-50 to-rose-100 dark:from-red-900/20 dark:to-rose-900/30 border-red-200 dark:border-red-800
                    @endif
                ">
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div class="flex items-center">
                            @if($appointment->status == 'pending')
                                <div class="bg-amber-100 dark:bg-amber-900/50 p-2.5 rounded-lg mr-4 shadow-md">
                                    <svg class="w-6 h-6 text-amber-500 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-amber-700 dark:text-amber-400 text-lg">Chờ xác nhận</span>
                                    <p class="text-amber-600 dark:text-amber-400/80 text-sm mt-0.5">Lịch hẹn đang chờ xác nhận từ nhân viên</p>
                                </div>
                            @elseif($appointment->status == 'confirmed')
                                <div class="bg-emerald-100 dark:bg-emerald-900/50 p-2.5 rounded-lg mr-4 shadow-md">
                                    <svg class="w-6 h-6 text-emerald-500 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-emerald-700 dark:text-emerald-400 text-lg">Đã xác nhận</span>
                                    <p class="text-emerald-600 dark:text-emerald-400/80 text-sm mt-0.5">Lịch hẹn đã được xác nhận và sẵn sàng</p>
                                </div>
                            @elseif($appointment->status == 'completed')
                                <div class="bg-blue-100 dark:bg-blue-900/50 p-2.5 rounded-lg mr-4 shadow-md">
                                    <svg class="w-6 h-6 text-blue-500 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-blue-700 dark:text-blue-400 text-lg">Hoàn thành</span>
                                    <p class="text-blue-600 dark:text-blue-400/80 text-sm mt-0.5">Lịch hẹn đã được hoàn thành thành công</p>
                                </div>
                            @elseif($appointment->status == 'cancelled')
                                <div class="bg-red-100 dark:bg-red-900/50 p-2.5 rounded-lg mr-4 shadow-md">
                                    <svg class="w-6 h-6 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-red-700 dark:text-red-400 text-lg">Đã hủy</span>
                                    <p class="text-red-600 dark:text-red-400/80 text-sm mt-0.5">Lịch hẹn đã bị hủy</p>
                                </div>
                            @endif
                        </div>
                        <div>
                            @if(in_array($appointment->status, ['pending', 'confirmed']))
                                <button type="button" onclick="openCancelModal()" class="px-4 py-2 bg-gradient-to-r from-red-500 to-rose-600 text-white text-sm rounded-lg hover:from-red-600 hover:to-rose-700 shadow-lg shadow-red-200/50 dark:shadow-none transition-all duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Hủy lịch hẹn
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Appointment Details -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-gray-200 dark:border-gray-700 px-5 py-4">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase flex items-center">
                                    <svg class="w-5 h-5 mr-2.5 text-blue-500 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Thông tin lịch hẹn
                                </h4>
                            </div>
                            <div class="p-5">
                                <div class="space-y-5">
                                    <div class="flex items-start">
                                        <div class="bg-blue-100 dark:bg-blue-900/50 p-2.5 rounded-lg mr-4 shadow-md flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Mã lịch hẹn</p>
                                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ substr($appointment->id, 0, 8) }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="bg-blue-100 dark:bg-blue-900/50 p-2.5 rounded-lg mr-4 shadow-md flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Ngày đăng ký</p>
                                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($appointment->date_register)->format('d/m/Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="bg-blue-100 dark:bg-blue-900/50 p-2.5 rounded-lg mr-4 shadow-md flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Ngày hẹn</p>
                                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="bg-blue-100 dark:bg-blue-900/50 p-2.5 rounded-lg mr-4 shadow-md flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Giờ hẹn</p>
                                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $appointment->timeAppointment ? $appointment->timeAppointment->started_time : 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="bg-blue-100 dark:bg-blue-900/50 p-2.5 rounded-lg mr-4 shadow-md flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.168 1.168a4 4 0 01-2.929 6.54h-2.118a4 4 0 01-2.227-.616c-.569-.354-1.073-.862-1.427-1.427a4.02 4.02 0 01-.616-2.227c0-1.11.45-2.118 1.17-2.83l1.168-1.168A3 3 0 009 8.172z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Dịch vụ</p>
                                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $appointment->service->name }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="bg-blue-100 dark:bg-blue-900/50 p-2.5 rounded-lg mr-4 shadow-md flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Giá dịch vụ</p>
                                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ number_format($appointment->service->price, 0, ',', '.') }} VNĐ</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                            <div class="bg-gradient-to-r from-pink-50 to-purple-100 dark:from-pink-900/20 dark:to-purple-900/20 border-b border-gray-200 dark:border-gray-700 px-5 py-4 flex justify-between items-center">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase flex items-center">
                                    <svg class="w-5 h-5 mr-2.5 text-pink-500 dark:text-pink-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                    Thông tin khách hàng
                                </h4>
                                <a href="{{ route('staff.appointments.edit-customer', $appointment->id) }}" class="px-3.5 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white text-xs rounded-lg hover:from-pink-600 hover:to-purple-700 shadow-lg shadow-pink-200/50 dark:shadow-none transition-all duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Sửa thông tin
                                </a>
                            </div>

                            <div class="p-5">
                                <!-- Customer Profile Header -->
                                <div class="flex items-center mb-6 pb-5 border-b border-gray-100 dark:border-gray-700">
                                    <div class="relative w-20 h-20 mr-5 rounded-full bg-gradient-to-r from-pink-100 to-purple-100 dark:from-pink-900/40 dark:to-purple-900/40 border-2 border-pink-200 dark:border-pink-700 shadow-lg overflow-hidden flex-shrink-0 transform hover:scale-105 transition-transform duration-300">
                                        <img class="object-cover w-full h-full rounded-full" src="https://ui-avatars.com/api/?name={{ $appointment->customer->first_name }}+{{ $appointment->customer->last_name }}&background=f9a8d4&color=ffffff&size=80" alt="{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}" loading="lazy" />
                                        <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 dark:text-white text-xl mb-1">{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                            </svg>
                                            {{ $appointment->customer->email }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Customer Details -->
                                <div class="space-y-5">
                                    <div class="flex items-start">
                                        <div class="bg-pink-100 dark:bg-pink-900/50 p-2.5 rounded-lg mr-4 shadow-md flex-shrink-0">
                                            <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Số điện thoại</p>
                                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $appointment->customer->phone }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="bg-pink-100 dark:bg-pink-900/50 p-2.5 rounded-lg mr-4 shadow-md flex-shrink-0">
                                            <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                @if($appointment->customer->gender == 'male')
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                @elseif($appointment->customer->gender == 'female')
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9c0-.46.074-.893.208-1.303.199-.61.77-1.297 1.666-1.797.197-.1.42-.193.665-.275A7 7 0 0110 6a7 7 0 017 7 1 1 0 11-2 0 5 5 0 00-5-5 5 5 0 00-5 5a1 1 0 01-2 0z" clip-rule="evenodd"></path>
                                                @else
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                @endif
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Giới tính</p>
                                            <p class="font-semibold text-gray-800 dark:text-gray-200">
                                                @if($appointment->customer->gender == 'male')
                                                    Nam
                                                @elseif($appointment->customer->gender == 'female')
                                                    Nữ
                                                @else
                                                    Khác
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="bg-pink-100 dark:bg-pink-900/50 p-2.5 rounded-lg mr-4 shadow-md flex-shrink-0">
                                            <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Ngày sinh</p>
                                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $appointment->customer->birthday ? \Carbon\Carbon::parse($appointment->customer->birthday)->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="bg-pink-100 dark:bg-pink-900/50 p-2.5 rounded-lg mr-4 shadow-md flex-shrink-0">
                                            <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Địa chỉ</p>
                                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $appointment->customer->address ?: 'Chưa cập nhật' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($appointment->notes || $appointment->cancellation_reason)
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($appointment->notes)
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                            <div class="bg-gradient-to-r from-gray-50 to-slate-100 dark:from-gray-900/30 dark:to-slate-900/30 border-b border-gray-200 dark:border-gray-700 px-5 py-4">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase flex items-center">
                                    <svg class="w-5 h-5 mr-2.5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Ghi chú
                                </h4>
                            </div>
                            <div class="p-5">
                                <p class="text-gray-700 dark:text-gray-300">{{ $appointment->notes }}</p>
                            </div>
                        </div>
                        @endif

                        @if($appointment->cancellation_reason)
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-red-200 dark:border-red-800/50 shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                            <div class="bg-gradient-to-r from-red-50 to-rose-100 dark:from-red-900/20 dark:to-rose-900/20 border-b border-red-200 dark:border-red-800/50 px-5 py-4">
                                <h4 class="text-sm font-medium text-red-700 dark:text-red-400 uppercase flex items-center">
                                    <svg class="w-5 h-5 mr-2.5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Lý do hủy
                                </h4>
                            </div>
                            <div class="p-5">
                                <p class="text-gray-700 dark:text-gray-300">{{ $appointment->cancellation_reason }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-100 dark:from-indigo-900/20 dark:to-purple-900/20 border-b border-gray-200 dark:border-gray-700 px-5 py-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase flex items-center">
                        <svg class="w-5 h-5 mr-2.5 text-indigo-500 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                        </svg>
                        Thao tác
                    </h4>
                </div>
                <div class="p-5">
                    <div class="flex flex-wrap gap-3">
                        @if($appointment->status == 'pending')
                        <form action="{{ route('staff.appointments.update', $appointment->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="service_id" value="{{ $appointment->service_id }}">
                            <input type="hidden" name="date_appointments" value="{{ $appointment->date_appointments }}">
                            <input type="hidden" name="time_appointments_id" value="{{ $appointment->time_appointments_id }}">
                            <input type="hidden" name="notes" value="{{ $appointment->notes }}">
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 shadow-lg shadow-green-200/50 dark:shadow-none transition-all duration-300 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Xác nhận lịch hẹn
                            </button>
                        </form>
                        @endif

                        @if($appointment->status == 'confirmed')
                        <form action="{{ route('staff.appointments.update', $appointment->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="service_id" value="{{ $appointment->service_id }}">
                            <input type="hidden" name="date_appointments" value="{{ $appointment->date_appointments }}">
                            <input type="hidden" name="time_appointments_id" value="{{ $appointment->time_appointments_id }}">
                            <input type="hidden" name="notes" value="{{ $appointment->notes }}">
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 shadow-lg shadow-blue-200/50 dark:shadow-none transition-all duration-300 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Đánh dấu hoàn thành
                            </button>
                        </form>
                        @endif

                        <a href="{{ route('staff.appointments.edit', $appointment->id) }}" class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-yellow-500 text-black rounded-lg hover:from-amber-600 hover:to-yellow-600 shadow-lg shadow-amber-200/50 dark:shadow-none transition-all duration-300 flex items-center">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Chỉnh sửa lịch hẹn
                        </a>

                        <a href="{{ route('staff.appointments.edit-customer', $appointment->id) }}" class="px-4 py-2.5 bg-gradient-to-r from-pink-500 to-purple-600 text-black rounded-lg hover:from-pink-600 hover:to-purple-700 shadow-lg shadow-pink-200/50 dark:shadow-none transition-all duration-300 flex items-center font-medium">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Chỉnh sửa khách hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-span-full md:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="bg-gradient-to-r from-teal-50 to-emerald-100 dark:from-teal-900/20 dark:to-emerald-900/20 border-b border-gray-200 dark:border-gray-700 px-5 py-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase flex items-center">
                        <svg class="w-5 h-5 mr-2.5 text-teal-500 dark:text-teal-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.168 1.168a4 4 0 01-2.929 6.54h-2.118a4 4 0 01-2.227-.616c-.569-.354-1.073-.862-1.427-1.427a4.02 4.02 0 01-.616-2.227c0-1.11.45-2.118 1.17-2.83l1.168-1.168A3 3 0 009 8.172z" clip-rule="evenodd"></path>
                        </svg>
                        Thông tin dịch vụ
                    </h4>
                </div>
                <div class="p-5">
                    <div class="mb-4">
                        <div class="relative overflow-hidden rounded-lg mb-4 shadow-lg group">
                            <img src="{{ $appointment->service->image_url ?? 'https://via.placeholder.com/300x150?text=Dịch+vụ' }}" alt="{{ $appointment->service->name }}" class="w-full h-40 object-cover rounded-lg transform transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-80"></div>
                            <div class="absolute bottom-0 left-0 p-4">
                                <span class="px-2 py-1 bg-teal-500 text-white text-xs rounded-full shadow-md">{{ number_format($appointment->service->price, 0, ',', '.') }} VNĐ</span>
                            </div>
                        </div>
                        <h5 class="font-bold text-gray-800 dark:text-white text-lg">{{ $appointment->service->name }}</h5>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-teal-500 dark:text-teal-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                            </svg>
                            Giá dịch vụ
                        </p>
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg backdrop-blur-sm">
                        <p class="mb-3">{{ Str::limit($appointment->service->description, 150) }}</p>
                        <a href="#" class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 font-medium flex items-center group">
                            <svg class="w-4 h-4 mr-1.5 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Xem chi tiết dịch vụ
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-gray-200 dark:border-gray-700 px-5 py-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase flex items-center">
                        <svg class="w-5 h-5 mr-2.5 text-blue-500 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Hướng dẫn
                    </h4>
                </div>
                <div class="p-5">
                    <div class="mb-5">
                        <h5 class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <svg class="w-4 h-4 mr-2 text-blue-500 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                            </svg>
                            Quy trình khám
                        </h5>
                        <ol class="list-decimal list-inside text-sm text-gray-600 dark:text-gray-300 ml-2 space-y-2">
                            <li class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">Đến trước giờ hẹn 15 phút</li>
                            <li class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">Xuất trình giấy tờ tùy thân</li>
                            <li class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">Làm thủ tục tại quầy tiếp nhận</li>
                            <li class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">Chờ đến lượt khám theo hướng dẫn</li>
                            <li class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">Nhận kết quả và thanh toán</li>
                        </ol>
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-900/30 p-4 rounded-lg shadow-inner">
                        <h5 class="flex items-center text-sm font-medium text-amber-700 dark:text-amber-400 mb-3">
                            <svg class="w-4 h-4 mr-2 text-amber-500 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Lưu ý
                        </h5>
                        <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-300 ml-2 space-y-2">
                            <li>Mang theo giấy tờ tùy thân</li>
                            <li>Có thể hủy lịch hẹn trước 24 giờ</li>
                            <li>Nếu cần hỗ trợ, vui lòng liên hệ số điện thoại: <strong class="text-amber-700 dark:text-amber-400">(0258) 2471303</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Appointment Modal -->
<div id="cancelModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-90 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('staff.appointments.cancel', $appointment->id) }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/50 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                                Hủy lịch hẹn
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Bạn có chắc chắn muốn hủy lịch hẹn này? Hành động này không thể hoàn tác.
                                </p>
                                <div class="mt-4">
                                    <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lý do hủy</label>
                                    <textarea 
                                        name="cancellation_reason" 
                                        id="cancellation_reason" 
                                        rows="3" 
                                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm focus:border-pink-500 dark:focus:border-pink-400 focus:ring focus:ring-pink-500 dark:focus:ring-pink-400 focus:ring-opacity-50 dark:text-white transition-colors duration-200"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-red-500 to-rose-600 text-base font-medium text-black hover:from-red-600 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-300">
                        Hủy lịch hẹn
                    </button>
                    <button type="button" onclick="closeCancelModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 dark:focus:ring-offset-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-300">
                        Đóng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openCancelModal() {
        document.getElementById('cancelModal').classList.remove('hidden');
        document.getElementById('cancelModal').classList.add('flex');
        setTimeout(() => {
            document.getElementById('cancellation_reason').focus();
        }, 100);
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
        document.getElementById('cancelModal').classList.remove('flex');
    }
    
    // Đóng modal khi click bên ngoài
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('cancelModal');
        if (e.target === modal) {
            closeCancelModal();
        }
    });
    
    // Đóng modal khi nhấn ESC
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('cancelModal').classList.contains('hidden')) {
            closeCancelModal();
        }
    });
</script>
@endsection