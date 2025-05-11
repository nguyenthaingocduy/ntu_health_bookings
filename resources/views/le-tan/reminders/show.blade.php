@extends('layouts.le-tan')

@section('title', 'Chi tiết nhắc lịch hẹn')

@section('header', 'Chi tiết nhắc lịch hẹn')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chi tiết nhắc lịch hẹn</h1>
            <p class="text-sm text-gray-500 mt-1">Xem thông tin chi tiết về nhắc nhở lịch hẹn</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('le-tan.reminders.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
            @if($reminder->status == 'pending')
            <a href="{{ route('le-tan.reminders.edit', $reminder->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Chỉnh sửa
            </a>
            <form action="{{ route('le-tan.reminders.send', $reminder->id) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-150 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Gửi ngay
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Thông tin nhắc nhở -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        Thông tin nhắc nhở
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Trạng thái</h4>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($reminder->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($reminder->status == 'sent') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                @if($reminder->status == 'pending') Đang chờ
                                @elseif($reminder->status == 'sent') Đã gửi
                                @else Thất bại @endif
                            </span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Loại nhắc nhở</h4>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($reminder->reminder_type == 'email') bg-blue-100 text-blue-800
                                @elseif($reminder->reminder_type == 'sms') bg-purple-100 text-purple-800
                                @else bg-indigo-100 text-indigo-800 @endif">
                                @if($reminder->reminder_type == 'email') Email
                                @elseif($reminder->reminder_type == 'sms') SMS
                                @else Email & SMS @endif
                            </span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Ngày giờ nhắc</h4>
                            <p class="text-gray-900">{{ $reminder->reminder_date->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Ngày tạo</h4>
                            <p class="text-gray-900">{{ $reminder->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @if($reminder->sent_at)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Ngày gửi</h4>
                            <p class="text-gray-900">{{ $reminder->sent_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Người tạo</h4>
                            <p class="text-gray-900">{{ $reminder->createdBy ? $reminder->createdBy->first_name . ' ' . $reminder->createdBy->last_name : 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Nội dung nhắc nhở</h4>
                        <div class="bg-gray-50 p-4 rounded-lg mt-2">
                            <p class="text-gray-900 whitespace-pre-line">{{ $reminder->message }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin lịch hẹn và khách hàng -->
        <div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-pink-50 to-pink-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Thông tin lịch hẹn
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Dịch vụ</h4>
                            <p class="text-gray-900 font-medium">{{ $reminder->appointment->service->name }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Ngày hẹn</h4>
                            <p class="text-gray-900">{{ $reminder->appointment->date_appointments->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Giờ hẹn</h4>
                            <p class="text-gray-900">{{ $reminder->appointment->timeSlot ? $reminder->appointment->timeSlot->start_time : ($reminder->appointment->timeAppointment ? $reminder->appointment->timeAppointment->started_time : 'N/A') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Trạng thái lịch hẹn</h4>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($reminder->appointment->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($reminder->appointment->status == 'confirmed') bg-blue-100 text-blue-800
                                @elseif($reminder->appointment->status == 'completed') bg-green-100 text-green-800
                                @elseif($reminder->appointment->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($reminder->appointment->status == 'pending') Đang chờ
                                @elseif($reminder->appointment->status == 'confirmed') Đã xác nhận
                                @elseif($reminder->appointment->status == 'completed') Đã hoàn thành
                                @elseif($reminder->appointment->status == 'cancelled') Đã hủy
                                @else Không đến @endif
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('le-tan.appointments.show', $reminder->appointment->id) }}" class="text-pink-600 hover:text-pink-800 font-medium text-sm flex items-center">
                            Xem chi tiết lịch hẹn
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Thông tin khách hàng
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-16 w-16">
                            <img class="h-16 w-16 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ $reminder->appointment->customer->first_name }}&background=0D8ABC&color=fff&size=128" alt="{{ $reminder->appointment->customer->first_name }}">
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-900">{{ $reminder->appointment->customer->first_name }} {{ $reminder->appointment->customer->last_name }}</h4>
                            <p class="text-gray-500">{{ $reminder->appointment->customer->gender == 'male' ? 'Nam' : 'Nữ' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-3 mt-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Email</h4>
                            <p class="text-gray-900">{{ $reminder->appointment->customer->email }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Số điện thoại</h4>
                            <p class="text-gray-900">{{ $reminder->appointment->customer->phone ?? 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('le-tan.customers.show', $reminder->appointment->customer->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center">
                            Xem chi tiết khách hàng
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
