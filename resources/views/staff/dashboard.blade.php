@extends('layouts.staff_dashboard')

@section('title', 'Trang chủ nhân viên')

@section('header', 'Trang chủ nhân viên')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Chào mừng, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-blue-100 rounded-lg p-6 shadow-sm">
            <div class="flex items-center">
                <div class="bg-blue-500 rounded-full p-3 mr-4">
                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Lịch hẹn hôm nay</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $todayAppointmentsCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-green-100 rounded-lg p-6 shadow-sm">
            <div class="flex items-center">
                <div class="bg-green-500 rounded-full p-3 mr-4">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tổng khách hàng</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $customersCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-purple-100 rounded-lg p-6 shadow-sm">
            <div class="flex items-center">
                <div class="bg-purple-500 rounded-full p-3 mr-4">
                    <i class="fas fa-spa text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Dịch vụ đang cung cấp</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $servicesCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Lịch hẹn sắp tới</h3>
        
        @if(isset($upcomingAppointments) && count($upcomingAppointments) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left">Khách hàng</th>
                            <th class="py-3 px-4 text-left">Dịch vụ</th>
                            <th class="py-3 px-4 text-left">Ngày</th>
                            <th class="py-3 px-4 text-left">Giờ</th>
                            <th class="py-3 px-4 text-left">Trạng thái</th>
                            <th class="py-3 px-4 text-left">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($upcomingAppointments as $appointment)
                            <tr>
                                <td class="py-3 px-4">{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</td>
                                <td class="py-3 px-4">{{ $appointment->service->name }}</td>
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($appointment->time->start_time)->format('H:i') }}</td>
                                <td class="py-3 px-4">
                                    @if($appointment->status == 'pending')
                                        <span class="bg-yellow-100 text-yellow-800 py-1 px-2 rounded-full text-xs">Chờ xác nhận</span>
                                    @elseif($appointment->status == 'confirmed')
                                        <span class="bg-green-100 text-green-800 py-1 px-2 rounded-full text-xs">Đã xác nhận</span>
                                    @elseif($appointment->status == 'cancelled')
                                        <span class="bg-red-100 text-red-800 py-1 px-2 rounded-full text-xs">Đã hủy</span>
                                    @elseif($appointment->status == 'completed')
                                        <span class="bg-blue-100 text-blue-800 py-1 px-2 rounded-full text-xs">Hoàn thành</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('staff.appointments.show', $appointment->id) }}" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-gray-50 rounded-lg p-6 text-center">
                <p class="text-gray-600">Không có lịch hẹn sắp tới</p>
            </div>
        @endif
    </div>
</div>
@endsection
