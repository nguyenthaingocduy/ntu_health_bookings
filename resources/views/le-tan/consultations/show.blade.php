@extends('layouts.le-tan')

@section('title', 'Chi tiết tư vấn dịch vụ')

@section('header', 'Chi tiết tư vấn dịch vụ')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chi tiết tư vấn dịch vụ</h1>
            <p class="text-sm text-gray-500 mt-1">Xem thông tin chi tiết về buổi tư vấn dịch vụ</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('le-tan.consultations.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
            @if($consultation->status == 'pending')
            <a href="{{ route('le-tan.consultations.edit', $consultation->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Chỉnh sửa
            </a>
            <a href="{{ route('le-tan.consultations.convert', $consultation->id) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Đặt lịch
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Thông tin tư vấn -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-pink-50 to-pink-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        Thông tin tư vấn
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Trạng thái</h4>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($consultation->status == 'pending') bg-yellow-100 text-yellow-800 
                                @elseif($consultation->status == 'converted') bg-green-100 text-green-800 
                                @else bg-red-100 text-red-800 @endif">
                                @if($consultation->status == 'pending') Đang chờ 
                                @elseif($consultation->status == 'converted') Đã chuyển đổi 
                                @else Đã hủy @endif
                            </span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Ngày đề xuất</h4>
                            <p class="text-gray-900">{{ $consultation->recommended_date ? $consultation->recommended_date->format('d/m/Y') : 'Chưa xác định' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Ngày tạo</h4>
                            <p class="text-gray-900">{{ $consultation->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Người tạo</h4>
                            <p class="text-gray-900">{{ $consultation->createdBy->first_name }} {{ $consultation->createdBy->last_name }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Ghi chú tư vấn</h4>
                        <div class="bg-gray-50 p-4 rounded-lg mt-2">
                            <p class="text-gray-900 whitespace-pre-line">{{ $consultation->notes }}</p>
                        </div>
                    </div>

                    @if($consultation->status == 'converted' && $consultation->appointment)
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Lịch hẹn đã đặt</h4>
                        <div class="bg-green-50 p-4 rounded-lg mt-2 border border-green-100">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-green-800">
                                    Đã chuyển đổi thành lịch hẹn vào ngày 
                                    <span class="font-medium">{{ $consultation->appointment->appointment_date->format('d/m/Y') }}</span>
                                    lúc 
                                    <span class="font-medium">{{ $consultation->appointment->timeSlot->start_time }}</span>
                                </p>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('le-tan.appointments.show', $consultation->appointment->id) }}" class="text-green-600 hover:text-green-800 font-medium text-sm flex items-center">
                                    Xem chi tiết lịch hẹn
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Thông tin khách hàng và dịch vụ -->
        <div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
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
                            <img class="h-16 w-16 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ $consultation->customer->first_name }}&background=0D8ABC&color=fff&size=128" alt="{{ $consultation->customer->first_name }}">
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-900">{{ $consultation->customer->first_name }} {{ $consultation->customer->last_name }}</h4>
                            <p class="text-gray-500">{{ $consultation->customer->gender == 'male' ? 'Nam' : 'Nữ' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-3 mt-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Email</h4>
                            <p class="text-gray-900">{{ $consultation->customer->email }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Số điện thoại</h4>
                            <p class="text-gray-900">{{ $consultation->customer->phone ?? 'Chưa cập nhật' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Ngày sinh</h4>
                            <p class="text-gray-900">{{ $consultation->customer->date_of_birth ? $consultation->customer->date_of_birth->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('le-tan.customers.show', $consultation->customer->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center">
                            Xem chi tiết khách hàng
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Thông tin dịch vụ
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-16 w-16">
                            <img class="h-16 w-16 rounded-lg object-cover" src="{{ $consultation->service->image_url ?? 'https://via.placeholder.com/150' }}" alt="{{ $consultation->service->name }}">
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-900">{{ $consultation->service->name }}</h4>
                            <p class="text-gray-500">{{ $consultation->service->category->name ?? 'Không có danh mục' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-3 mt-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Giá dịch vụ</h4>
                            <p class="text-gray-900 font-medium">{{ number_format($consultation->service->price, 0, ',', '.') }} VNĐ</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Thời gian thực hiện</h4>
                            <p class="text-gray-900">{{ $consultation->service->duration }} phút</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('le-tan.services.show', $consultation->service->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center">
                            Xem chi tiết dịch vụ
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
