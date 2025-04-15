@extends('layouts.admin')

@section('title', 'Chi tiết nhân viên')

@section('header', 'Chi tiết nhân viên')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Employee Information -->
    <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center">
                    <img src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}" 
                        class="w-20 h-20 rounded-full object-cover mr-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $employee->name }}</h2>
                        <p class="text-gray-500">{{ $employee->clinic->name }}</p>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-sm
                    {{ $employee->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $employee->status == 'active' ? 'Đang làm việc' : 'Đã nghỉ việc' }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Thông tin liên hệ</h3>
                    <div class="space-y-2">
                        <p class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 w-6"></i>
                            <span class="text-gray-600">{{ $employee->email }}</span>
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-phone text-gray-400 w-6"></i>
                            <span class="text-gray-600">{{ $employee->phone }}</span>
                        </p>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-2">Dịch vụ có thể thực hiện</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($employee->services as $service)
                        <span class="px-3 py-1 bg-pink-100 text-pink-500 rounded-full text-sm">
                            {{ $service->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.employees.edit', $employee) }}" 
                    class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                    <i class="fas fa-edit mr-2"></i>Chỉnh sửa
                </a>
                <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition"
                        onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">
                        <i class="fas fa-trash mr-2"></i>Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Thống kê</h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Tổng số lịch hẹn</p>
                    <p class="text-2xl font-bold">{{ $statistics['total_appointments'] }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Lịch hẹn trong tháng</p>
                    <p class="text-2xl font-bold">{{ $statistics['monthly_appointments'] }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Tỷ lệ hoàn thành</p>
                    <p class="text-2xl font-bold">{{ number_format($statistics['completion_rate'], 1) }}%</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Đánh giá trung bình</p>
                    <div class="flex items-center">
                        <p class="text-2xl font-bold mr-2">{{ number_format($statistics['average_rating'], 1) }}</p>
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $statistics['average_rating'] ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Appointments -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Lịch hẹn gần đây</h3>
            
            <div class="space-y-4">
                @forelse($recentAppointments as $appointment)
                <div class="border-b pb-4 last:border-0 last:pb-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold">{{ $appointment->customer->name }}</p>
                            <p class="text-sm text-gray-500">{{ $appointment->service->name }}</p>
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $appointment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                               ($appointment->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : 
                               ($appointment->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                            {{ $appointment->status == 'pending' ? 'Chờ xác nhận' : 
                               ($appointment->status == 'confirmed' ? 'Đã xác nhận' : 
                               ($appointment->status == 'completed' ? 'Hoàn thành' : 'Đã hủy')) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $appointment->appointment_date->format('d/m/Y H:i') }}
                    </p>
                </div>
                @empty
                <p class="text-gray-500 text-center">Chưa có lịch hẹn nào</p>
                @endforelse
            </div>
            
            @if($recentAppointments->hasMorePages())
            <div class="mt-4 text-center">
                <a href="{{ route('admin.appointments.index', ['employee' => $employee->id]) }}" 
                    class="text-pink-500 hover:text-pink-600">
                    Xem tất cả
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 