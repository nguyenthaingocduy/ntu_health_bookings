@extends('layouts.staff_tailwind')

@section('title', 'Trang chủ - Cán bộ viên chức')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Trang chủ cán bộ viên chức</h1>
    <p class="text-gray-600">Chào mừng trở lại, {{ Auth::user()->first_name }}!</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Upcoming Appointments -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-600 rounded-md p-3">
                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-blue-100 truncate">
                            Lịch hẹn sắp tới
                        </dt>
                        <dd>
                            <div class="text-lg font-semibold text-white">
                                {{ $upcomingAppointmentsCount }}
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-blue-600 px-4 py-3">
            <a href="{{ route('staff.appointments.index') }}" class="text-sm text-blue-100 hover:text-white flex justify-between items-center">
                Xem chi tiết
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Completed Appointments -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-600 rounded-md p-3">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-green-100 truncate">
                            Lịch hẹn đã hoàn thành
                        </dt>
                        <dd>
                            <div class="text-lg font-semibold text-black">
                                {{ $completedAppointmentsCount }}
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-green-600 px-4 py-3">
            <a href="{{ route('staff.appointments.index', ['status' => 'completed']) }}" class="text-sm text-green-100 hover:text-white flex justify-between items-center">
                Xem chi tiết
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Today's Appointments -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-600 rounded-md p-3">
                    <i class="fas fa-calendar-day text-white text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-purple-100 truncate">
                            Lịch hẹn hôm nay
                        </dt>
                        <dd>
                            <div class="text-lg font-semibold text-white">
                                {{ $todayAppointmentsCount }}
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-purple-600 px-4 py-3">
            <a href="{{ route('staff.work-schedule') }}" class="text-sm text-purple-100 hover:text-white flex justify-between items-center">
                Xem lịch làm việc
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-lg shadow-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-amber-600 rounded-md p-3">
                    <i class="fas fa-coins text-white text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-amber-100 truncate">
                            Doanh thu tháng này
                        </dt>
                        <dd>
                            <div class="text-lg font-semibold text-white">
                                {{ number_format($monthlyRevenue, 0, ',', '.') }} VNĐ
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-amber-600 px-4 py-3">
            <a href="{{ route('staff.statistics') }}" class="text-sm text-amber-100 hover:text-white flex justify-between items-center">
                Xem thống kê
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Today's Schedule -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-calendar-day text-blue-500 mr-2"></i>
                    Lịch làm việc hôm nay
                </h2>
                <a href="{{ route('staff.work-schedule') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                    Xem tất cả
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="p-6">
                @if($todayAppointments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dịch vụ</th>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                    <th class="px-4 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($todayAppointments as $appointment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $appointment->timeAppointment ? $appointment->timeAppointment->started_time : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
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
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $appointment->service->name }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($appointment->status == 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Chờ xác nhận
                                            </span>
                                        @elseif($appointment->status == 'confirmed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Đã xác nhận
                                            </span>
                                        @elseif($appointment->status == 'completed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Hoàn thành
                                            </span>
                                        @elseif($appointment->status == 'cancelled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Đã hủy
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
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
                    <div class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-500 mb-4">
                            <i class="fas fa-calendar-check text-2xl"></i>
                        </div>
                        <h3 class="text-gray-500 text-lg font-medium mb-2">Không có lịch hẹn hôm nay</h3>
                        <p class="text-gray-400 mb-4">Bạn không có lịch hẹn nào được đặt cho ngày hôm nay.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mt-8">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-history text-blue-500 mr-2"></i>
                    Lịch hẹn gần đây
                </h2>
                <a href="{{ route('staff.appointments.index') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                    Xem tất cả
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="p-6">
                @if($recentAppointments->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentAppointments as $appointment)
                            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <div class="flex-shrink-0">
                                    @if($appointment->status == 'pending')
                                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-yellow-100 text-yellow-600">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                    @elseif($appointment->status == 'confirmed')
                                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 text-blue-600">
                                            <i class="fas fa-calendar-check"></i>
                                        </span>
                                    @elseif($appointment->status == 'completed')
                                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-green-100 text-green-600">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    @elseif($appointment->status == 'cancelled')
                                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-red-100 text-red-600">
                                            <i class="fas fa-times-circle"></i>
                                        </span>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm font-medium text-gray-900">{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <div class="text-sm text-gray-500">{{ $appointment->service->name }}</div>
                                        <div class="text-xs font-medium">
                                            @if($appointment->status == 'pending')
                                                <span class="text-yellow-600">Chờ xác nhận</span>
                                            @elseif($appointment->status == 'confirmed')
                                                <span class="text-blue-600">Đã xác nhận</span>
                                            @elseif($appointment->status == 'completed')
                                                <span class="text-green-600">Hoàn thành</span>
                                            @elseif($appointment->status == 'cancelled')
                                                <span class="text-red-600">Đã hủy</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('staff.appointments.show', $appointment->id) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-500 mb-4">
                            <i class="fas fa-calendar-times text-2xl"></i>
                        </div>
                        <h3 class="text-gray-500 text-lg font-medium mb-2">Không có lịch hẹn gần đây</h3>
                        <p class="text-gray-400">Bạn chưa có lịch hẹn nào được đặt gần đây.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-8">
        <!-- Monthly Revenue Chart -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                    Doanh thu theo tháng
                </h2>
            </div>
            <div class="p-6">
                <canvas id="revenueChart" height="200"></canvas>
            </div>
        </div>

        <!-- Services -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-spa text-blue-500 mr-2"></i>
                    Dịch vụ phổ biến
                </h2>
            </div>
            <div class="p-6">
                @if($popularServices->count() > 0)
                    <div class="space-y-4">
                        @foreach($popularServices as $service)
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-md bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-spa text-blue-600"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                                        <div class="text-sm font-medium text-blue-600">{{ number_format($service->price, 0, ',', '.') }} VNĐ</div>
                                    </div>
                                    <div class="mt-1 flex items-center">
                                        <div class="flex-grow bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, ($service->appointments_count / max(1, $maxAppointments)) * 100) }}%"></div>
                                        </div>
                                        <span class="ml-2 text-xs text-gray-500">{{ $service->appointments_count }} lượt</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-gray-500">Chưa có dữ liệu dịch vụ</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-bolt text-blue-500 mr-2"></i>
                    Thao tác nhanh
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <a href="{{ route('staff.appointments.create') }}" class="flex items-center p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                    <i class="fas fa-calendar-plus mr-3 text-blue-500"></i>
                    <span>Tạo lịch hẹn mới</span>
                </a>
                <a href="{{ route('staff.work-schedule') }}" class="flex items-center p-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition">
                    <i class="fas fa-calendar-week mr-3 text-purple-500"></i>
                    <span>Xem lịch làm việc</span>
                </a>
                <a href="{{ route('staff.statistics') }}" class="flex items-center p-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition">
                    <i class="fas fa-chart-bar mr-3 text-green-500"></i>
                    <span>Xem báo cáo thống kê</span>
                </a>
                <a href="{{ route('staff.profile.index') }}" class="flex items-center p-3 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 transition">
                    <i class="fas fa-user-edit mr-3 text-amber-500"></i>
                    <span>Cập nhật thông tin cá nhân</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueData['labels']) !!},
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: {!! json_encode($revenueData['data']) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
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
@endsection
