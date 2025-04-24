@extends('layouts.staff_dashboard')

@section('title', 'Trang chủ Lễ tân')

@section('header', 'Trang chủ Lễ tân')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Trang chủ Lễ tân</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý lịch hẹn, khách hàng và dịch vụ</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('receptionist.appointments.create') }}" class="flex items-center px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors duration-150 shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tạo lịch hẹn mới
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-pink-50 to-pink-100 px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    Lịch hẹn hôm nay
                </h3>
            </div>
            <div class="p-6">
                <div class="text-center text-3xl font-bold text-pink-500">{{ $todayAppointmentsCount ?? 0 }}</div>
                <div class="text-center text-sm text-gray-500 mt-1">Lịch hẹn</div>
                <div class="mt-4">
                    <a href="{{ route('receptionist.appointments.index') }}" class="block w-full text-center px-4 py-2 bg-pink-100 text-pink-700 rounded-lg hover:bg-pink-200 transition-colors duration-150">
                        Xem tất cả
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                    Khách hàng
                </h3>
            </div>
            <div class="p-6">
                <div class="text-center text-3xl font-bold text-blue-500">{{ $customersCount ?? 0 }}</div>
                <div class="text-center text-sm text-gray-500 mt-1">Tổng số khách hàng</div>
                <div class="mt-4">
                    <a href="{{ route('receptionist.customers.index') }}" class="block w-full text-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-150">
                        Quản lý khách hàng
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    Thanh toán
                </h3>
            </div>
            <div class="p-6">
                <div class="text-center text-3xl font-bold text-green-500">{{ $todayPaymentsCount ?? 0 }}</div>
                <div class="text-center text-sm text-gray-500 mt-1">Thanh toán hôm nay</div>
                <div class="mt-4">
                    <a href="{{ route('receptionist.payments.index') }}" class="block w-full text-center px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors duration-150">
                        Xem thanh toán
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-pink-50 to-pink-100 px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    Lịch hẹn sắp tới
                </h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Khách hàng
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dịch vụ
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Thời gian
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(isset($upcomingAppointments) && count($upcomingAppointments) > 0)
                                @foreach($upcomingAppointments as $appointment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $appointment->customer->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $appointment->customer->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $appointment->service->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $appointment->appointment_date->format('d/m/Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $appointment->time_slot->start_time }} - {{ $appointment->time_slot->end_time }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('receptionist.appointments.show', $appointment->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Chi tiết</a>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Không có lịch hẹn sắp tới
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <a href="{{ route('receptionist.appointments.index') }}" class="block w-full text-center px-4 py-2 bg-pink-100 text-pink-700 rounded-lg hover:bg-pink-200 transition-colors duration-150">
                        Xem tất cả lịch hẹn
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Thông báo gần đây
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @if(isset($recentNotifications) && count($recentNotifications) > 0)
                        @foreach($recentNotifications as $notification)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                    <p class="mt-1 text-sm text-gray-500">{{ $notification->message }}</p>
                                    <p class="mt-1 text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-sm text-gray-500 py-4">
                            Không có thông báo gần đây
                        </div>
                    @endif
                </div>
                <div class="mt-4">
                    <a href="{{ route('receptionist.notifications.index') }}" class="block w-full text-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-150">
                        Xem tất cả thông báo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
