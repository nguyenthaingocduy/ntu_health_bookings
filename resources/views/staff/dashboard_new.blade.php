@extends('layouts.staff_new')

@section('title', 'Trang chủ - Cán bộ viên chức')
@section('page-title', 'Trang chủ')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Section -->
    <div class="bg-white overflow-hidden shadow-xl rounded-2xl mb-8 border border-gray-100">
        <div class="p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row items-center">
                <div class="w-20 h-20 rounded-full bg-gradient-to-r from-pink-500 to-purple-600 flex items-center justify-center mr-6 mb-4 sm:mb-0 shadow-lg">
                    <i class="fas fa-user-md text-white text-3xl"></i>
                </div>
                <div class="text-center sm:text-left">
                    <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-purple-600 mb-2">Xin chào, {{ Auth::user()->first_name }}!</h2>
                    <p class="text-gray-600 text-lg">Chào mừng đến với hệ thống quản lý lịch hẹn khám sức khỏe dành cho cán bộ viên chức.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Profile Summary Card -->
    <div class="bg-gradient-to-r from-purple-100 to-pink-100 overflow-hidden shadow-lg rounded-2xl mb-8 border border-purple-200">
        <div class="p-6 sm:p-8">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="relative mr-5">
                        <img 
                            class="h-16 w-16 rounded-full object-cover border-4 border-white shadow-md" 
                            src="https://ui-avatars.com/api/?name={{ Auth::user()->first_name }}&background=f9a8d4&color=ffffff" 
                            alt="{{ Auth::user()->first_name }}">
                        <div class="absolute bottom-0 right-0 h-4 w-4 rounded-full bg-green-400 border-2 border-white"></div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>
                        <p class="text-gray-600">{{ Auth::user()->email }}</p>
                        <div class="flex items-center mt-1">
                            <span class="flex items-center text-purple-600 font-medium text-sm mr-4">
                                <i class="fas fa-user-tie mr-1"></i> Cán bộ viên chức
                            </span>
                            <span class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ Auth::user()->address ?? 'Chưa cập nhật' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('staff.profile.index') }}" class="inline-flex items-center px-4 py-2 bg-white rounded-lg border border-gray-200 shadow-sm text-pink-600 hover:bg-gray-50 transition">
                        <i class="fas fa-user-edit mr-2"></i> Cập nhật hồ sơ
                    </a>
                    <a href="{{ route('staff.appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 rounded-lg shadow-sm text-white hover:from-pink-600 hover:to-purple-700 transition">
                        <i class="fas fa-calendar-plus mr-2"></i> Tạo lịch hẹn
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <!-- Upcoming Appointments -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl transform transition duration-300 hover:scale-105 border border-gray-100">
            <div class="p-6 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-blue-100 opacity-40"></div>
                <div class="flex items-center justify-between relative">
                    <div>
                        <div class="text-4xl font-extrabold text-gray-800 mb-1">
                            {{ $upcomingAppointmentsCount ?? 0 }}
                        </div>
                        <div class="text-gray-600 font-medium text-lg">Lịch hẹn sắp tới</div>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                <a href="{{ route('staff.appointments.index') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center">
                    Xem tất cả lịch hẹn
                    <i class="fas fa-arrow-right ml-1 text-xs transition transform group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl transform transition duration-300 hover:scale-105 border border-gray-100">
            <div class="p-6 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-purple-100 opacity-40"></div>
                <div class="flex items-center justify-between relative">
                    <div>
                        <div class="text-4xl font-extrabold text-gray-800 mb-1">
                            {{ $todayAppointmentsCount ?? 0 }}
                        </div>
                        <div class="text-gray-600 font-medium text-lg">Lịch hẹn hôm nay</div>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-md">
                        <i class="fas fa-calendar-day text-white text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                <a href="{{ route('staff.work-schedule') }}" class="text-purple-600 hover:text-purple-700 font-medium text-sm flex items-center">
                    Xem lịch làm việc
                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>

        <!-- Completed Appointments -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl transform transition duration-300 hover:scale-105 border border-gray-100">
            <div class="p-6 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-green-100 opacity-40"></div>
                <div class="flex items-center justify-between relative">
                    <div>
                        <div class="text-4xl font-extrabold text-gray-800 mb-1">
                            {{ $completedAppointmentsCount ?? 0 }}
                        </div>
                        <div class="text-gray-600 font-medium text-lg">Lịch hẹn hoàn thành</div>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center shadow-md">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                <a href="{{ route('staff.appointments.index', ['status' => 'completed']) }}" class="text-green-600 hover:text-green-700 font-medium text-sm flex items-center">
                    Xem lịch hẹn hoàn thành
                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl transform transition duration-300 hover:scale-105 border border-gray-100">
            <div class="p-6 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-amber-100 opacity-40"></div>
                <div class="flex items-center justify-between relative">
                    <div>
                        <div class="text-4xl font-extrabold text-gray-800 mb-1">
                            {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="text-gray-600 font-medium text-lg">Doanh thu (VNĐ)</div>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-md">
                        <i class="fas fa-coins text-white text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                <a href="{{ route('staff.statistics') }}" class="text-amber-600 hover:text-amber-700 font-medium text-sm flex items-center">
                    Xem thống kê
                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Today's Schedule & Revenue Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Today's Schedule - 2/3 width on larger screens -->
        <div class="lg:col-span-2 bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
            <div class="p-6 sm:p-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <span class="bg-pink-100 text-pink-600 p-2 rounded-lg mr-3">
                            <i class="fas fa-calendar-day"></i>
                        </span>
                        Lịch làm việc hôm nay
                    </h2>
                    <a href="{{ route('staff.work-schedule') }}" class="text-pink-600 hover:text-pink-700 font-semibold flex items-center group">
                        Xem tất cả
                        <i class="fas fa-arrow-right ml-2 text-sm transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
                
                @if(isset($todayAppointments) && count($todayAppointments) > 0)
                    <div class="overflow-x-auto bg-white rounded-xl shadow-inner">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 rounded-tl-xl">
                                        Thời gian
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50">
                                        Khách hàng
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50">
                                        Dịch vụ
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50">
                                        Trạng thái
                                    </th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 rounded-tr-xl">
                                        Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($todayAppointments as $appointment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="text-gray-700 flex items-center">
                                            <span class="bg-blue-50 text-blue-600 p-1 rounded-lg mr-2">
                                                <i class="fas fa-clock text-xs"></i>
                                            </span>
                                            {{ $appointment->timeAppointment ? $appointment->timeAppointment->started_time : 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 font-medium">{{ substr($appointment->customer->first_name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $appointment->customer->phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $appointment->service->name }}</div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="px-3 py-1.5 inline-flex items-center text-xs leading-5 font-semibold rounded-full 
                                            {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $appointment->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            @if($appointment->status === 'pending')
                                                <i class="fas fa-clock mr-1"></i> Chờ xác nhận
                                            @elseif($appointment->status === 'confirmed')
                                                <i class="fas fa-check-circle mr-1"></i> Đã xác nhận
                                            @elseif($appointment->status === 'completed')
                                                <i class="fas fa-check-double mr-1"></i> Hoàn thành
                                            @elseif($appointment->status === 'cancelled')
                                                <i class="fas fa-times-circle mr-1"></i> Đã hủy
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('staff.appointments.show', $appointment->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(in_array($appointment->status, ['pending', 'confirmed']))
                                            <a href="{{ route('staff.appointments.complete', $appointment->id) }}" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-16 bg-gray-50 rounded-xl">
                        <div class="w-20 h-20 bg-gradient-to-r from-pink-200 to-purple-200 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner">
                            <i class="fas fa-calendar-times text-pink-500 text-2xl"></i>
                        </div>
                        <h3 class="text-gray-700 font-semibold text-xl mb-2">Không có lịch hẹn hôm nay</h3>
                        <p class="text-gray-500 mb-6 max-w-md mx-auto">Bạn không có lịch hẹn nào được đặt cho ngày hôm nay.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Revenue Chart - 1/3 width on larger screens -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
            <div class="p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center mb-6">
                    <span class="bg-amber-100 text-amber-600 p-2 rounded-lg mr-3">
                        <i class="fas fa-chart-line"></i>
                    </span>
                    Doanh thu
                </h2>
                
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('staff.statistics') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-medium rounded-lg hover:from-amber-600 hover:to-amber-700 transition">
                        <i class="fas fa-chart-bar mr-2"></i> Xem thống kê chi tiết
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
        <div class="bg-gradient-to-br from-pink-50 to-pink-100 overflow-hidden shadow-lg rounded-2xl transform transition duration-300 hover:shadow-xl border border-pink-100 relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-pink-200 rounded-full opacity-20 transform translate-x-10 -translate-y-20"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-pink-200 rounded-full opacity-20 transform -translate-x-10 translate-y-10"></div>
            <div class="p-8 relative">
                <div class="mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-r from-pink-500 to-pink-600 flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-week text-white text-xl"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Lịch làm việc</h3>
                <p class="text-gray-600 mb-8">
                    Xem lịch làm việc của bạn, quản lý các cuộc hẹn và theo dõi lịch trình hàng ngày. Dễ dàng cập nhật trạng thái các cuộc hẹn.
                </p>
                <a href="{{ route('staff.work-schedule') }}" 
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-500 to-pink-600 text-white font-medium rounded-full hover:from-pink-600 hover:to-pink-700 transition shadow-md group">
                    Xem lịch làm việc
                    <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-purple-100 overflow-hidden shadow-lg rounded-2xl transform transition duration-300 hover:shadow-xl border border-purple-100 relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-purple-200 rounded-full opacity-20 transform translate-x-10 -translate-y-20"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-purple-200 rounded-full opacity-20 transform -translate-x-10 translate-y-10"></div>
            <div class="p-8 relative">
                <div class="mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center shadow-lg">
                        <i class="fas fa-chart-bar text-white text-xl"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Thống kê</h3>
                <p class="text-gray-600 mb-8">
                    Theo dõi số lượng khách hàng, doanh thu và hiệu suất làm việc của bạn. Xem báo cáo chi tiết và phân tích dữ liệu.
                </p>
                <a href="{{ route('staff.statistics') }}" 
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white font-medium rounded-full hover:from-purple-600 hover:to-purple-700 transition shadow-md group">
                    Xem thống kê
                    <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Popular Services -->
    <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100 mb-10">
        <div class="p-6 sm:p-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <span class="bg-purple-100 text-purple-600 p-2 rounded-lg mr-3">
                        <i class="fas fa-spa"></i>
                    </span>
                    Dịch vụ phổ biến
                </h2>
            </div>
            
            @if(isset($popularServices) && count($popularServices) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($popularServices as $service)
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center shadow-md mr-4">
                                    <i class="fas fa-spa text-white"></i>
                                </div>
                                <h3 class="font-bold text-gray-800">{{ $service->name }}</h3>
                            </div>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-gray-600 text-sm">Số lượt đặt:</span>
                                <span class="font-semibold text-purple-600">{{ $service->appointments_count }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm">Giá dịch vụ:</span>
                                <span class="font-semibold text-pink-600">{{ number_format($service->price, 0, ',', '.') }} VNĐ</span>
                            </div>
                            <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full" style="width: {{ min(100, ($service->appointments_count / max(1, $maxAppointments)) * 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 bg-gray-50 rounded-xl">
                    <div class="w-20 h-20 bg-gradient-to-r from-purple-200 to-pink-200 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner">
                        <i class="fas fa-spa text-purple-500 text-2xl"></i>
                    </div>
                    <h3 class="text-gray-700 font-semibold text-xl mb-2">Chưa có dữ liệu dịch vụ</h3>
                    <p class="text-gray-500 max-w-md mx-auto">Chưa có dữ liệu về các dịch vụ phổ biến.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueData['labels'] ?? []) !!},
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: {!! json_encode($revenueData['data'] ?? []) !!},
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderColor: 'rgba(245, 158, 11, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(245, 158, 11, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 1,
                    pointRadius: 4,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.raw;
                                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' VNĐ';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return (value / 1000).toFixed(0) + 'K';
                                }
                                return value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
