@extends('layouts.staff_new')

@section('title', 'Thống kê - Cán bộ viên chức')
@section('page-title', 'Thống kê')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="bg-white overflow-hidden shadow-xl rounded-2xl mb-8 border border-gray-100">
        <div class="p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row items-center justify-between">
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-r from-pink-500 to-purple-600 flex items-center justify-center mr-4 shadow-lg">
                        <i class="fas fa-chart-bar text-black text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Thống kê</h2>
                        <p class="text-gray-600">Theo dõi số lượng khách hàng, doanh thu và hiệu suất</p>
                    </div>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition">
                        <i class="fas fa-calendar-alt mr-2 text-pink-500"></i>
                        {{ $periodName }}
                        <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-51 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10 border border-gray-100"
                         style="display: none;">
                        <div class="py-1">
                            <a href="{{ route('staff.statistics', ['period' => 'today']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-700 {{ request('period') == 'today' ? 'bg-pink-50 text-pink-700' : '' }}">Hôm nay</a>
                            <a href="{{ route('staff.statistics', ['period' => 'yesterday']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-700 {{ request('period') == 'yesterday' ? 'bg-pink-50 text-pink-700' : '' }}">Hôm qua</a>
                            <a href="{{ route('staff.statistics', ['period' => 'this_week']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-700 {{ request('period') == 'this_week' ? 'bg-pink-50 text-pink-700' : '' }}">Tuần này</a>
                            <a href="{{ route('staff.statistics', ['period' => 'last_week']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-700 {{ request('period') == 'last_week' ? 'bg-pink-50 text-pink-700' : '' }}">Tuần trước</a>
                            <a href="{{ route('staff.statistics', ['period' => 'this_month']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-700 {{ request('period') == 'this_month' || !request('period') ? 'bg-pink-50 text-pink-700' : '' }}">Tháng này</a>
                            <a href="{{ route('staff.statistics', ['period' => 'last_month']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-700 {{ request('period') == 'last_month' ? 'bg-pink-50 text-pink-700' : '' }}">Tháng trước</a>
                            <a href="{{ route('staff.statistics', ['period' => 'this_year']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-700 {{ request('period') == 'this_year' ? 'bg-pink-50 text-pink-700' : '' }}">Năm nay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Appointments -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl transform transition duration-300 hover:scale-105 border border-gray-100">
            <div class="p-6 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-blue-100 opacity-40"></div>
                <div class="flex items-center justify-between relative">
                    <div>
                        <div class="text-4xl font-extrabold text-gray-800 mb-0">
                            {{ $stats['total_appointments'] ?? 0 }}
                        </div>
                        <div class="text-gray-600 font-medium text-lg">Tổng số lịch hẹn</div>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md">
                        <i class="fas fa-calendar-check text-black text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="{{ $stats['appointment_change'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                        <i class="fas {{ $stats['appointment_change'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ abs($stats['appointment_change']) }}%
                    </span>
                    <span class="text-gray-500 ml-2">so với kỳ trước</span>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl transform transition duration-300 hover:scale-105 border border-gray-100">
            <div class="p-6 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-green-100 opacity-40"></div>
                <div class="flex items-center justify-between relative">
                    <div>
                        <div class="text-4xl font-extrabold text-gray-800 mb-1">
                            {{ $stats['total_customers'] ?? 0 }}
                        </div>
                        <div class="text-gray-600 font-medium text-lg">Tổng số khách hàng</div>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center shadow-md">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="{{ $stats['customer_change'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                        <i class="fas {{ $stats['customer_change'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ abs($stats['customer_change']) }}%
                    </span>
                    <span class="text-gray-500 ml-2">so với kỳ trước</span>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl transform transition duration-300 hover:scale-105 border border-gray-100">
            <div class="p-6 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-amber-100 opacity-40"></div>
                <div class="flex items-center justify-between relative">
                    <div>
                        <div class="text-4xl font-extrabold text-gray-800 mb-1">
                            {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="text-gray-600 font-medium text-lg">Tổng doanh thu (VNĐ)</div>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-md">
                        <i class="fas fa-coins text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="{{ $stats['revenue_change'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                        <i class="fas {{ $stats['revenue_change'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ abs($stats['revenue_change']) }}%
                    </span>
                    <span class="text-gray-500 ml-2">so với kỳ trước</span>
                </div>
            </div>
        </div>

        <!-- Average Revenue Per Appointment -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl transform transition duration-300 hover:scale-105 border border-gray-100">
            <div class="p-6 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-purple-100 opacity-40"></div>
                <div class="flex items-center justify-between relative">
                    <div>
                        <div class="text-4xl font-extrabold text-gray-800 mb-1">
                            {{ number_format($stats['avg_revenue'] ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="text-gray-600 font-medium text-lg">Doanh thu TB/lịch (VNĐ)</div>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-md">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="{{ $stats['avg_change'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                        <i class="fas {{ $stats['avg_change'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ abs($stats['avg_change']) }}%
                    </span>
                    <span class="text-gray-500 ml-2">so với kỳ trước</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
            <div class="p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center mb-6">
                    <span class="bg-amber-100 text-amber-600 p-2 rounded-lg mr-3">
                        <i class="fas fa-chart-line"></i>
                    </span>
                    Doanh thu theo thời gian
                </h2>
                <div class="h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Appointments by Status -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
            <div class="p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center mb-6">
                    <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                        <i class="fas fa-chart-pie"></i>
                    </span>
                    Lịch hẹn theo trạng thái
                </h2>
                <div class="h-80">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Services and Customers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Top Services -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
            <div class="p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center mb-6">
                    <span class="bg-pink-100 text-pink-600 p-2 rounded-lg mr-3">
                        <i class="fas fa-spa"></i>
                    </span>
                    Dịch vụ phổ biến nhất
                </h2>
                
                @if(isset($topServices) && count($topServices) > 0)
                    <div class="space-y-6">
                        @foreach($topServices as $service)
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-pink-500 to-purple-600 flex items-center justify-center shadow-md mr-3">
                                            <i class="fas fa-spa text-white"></i>
                                        </div>
                                        <h3 class="font-bold text-gray-800">{{ $service->name }}</h3>
                                    </div>
                                    <span class="font-semibold text-pink-600">{{ number_format($service->total_revenue, 0, ',', '.') }} VNĐ</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="flex-grow bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-gradient-to-r from-pink-500 to-purple-600 h-2.5 rounded-full" style="width: {{ ($service->count / $topServices->max('count')) * 100 }}%"></div>
                                    </div>
                                    <span class="ml-3 text-sm text-gray-600 min-w-[60px] text-right">{{ $service->count }} lượt</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10 bg-gray-50 rounded-xl">
                        <div class="w-20 h-20 bg-gradient-to-r from-pink-200 to-purple-200 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner">
                            <i class="fas fa-spa text-pink-500 text-2xl"></i>
                        </div>
                        <h3 class="text-gray-700 font-semibold text-xl mb-2">Không có dữ liệu</h3>
                        <p class="text-gray-500">Chưa có dữ liệu về các dịch vụ phổ biến.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Customers -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
            <div class="p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center mb-6">
                    <span class="bg-purple-100 text-purple-600 p-2 rounded-lg mr-3">
                        <i class="fas fa-user-friends"></i>
                    </span>
                    Khách hàng thân thiết nhất
                </h2>
                
                @if(isset($topCustomers) && count($topCustomers) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">Khách hàng</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lần đặt</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng chi tiêu</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">Lần cuối</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($topCustomers as $customer)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-r from-pink-100 to-purple-100 rounded-full flex items-center justify-center">
                                                    <span class="text-pink-600 font-medium">{{ substr($customer->first_name, 0, 1) }}</span>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $customer->phone }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                                {{ $customer->appointment_count }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            {{ number_format($customer->total_spent, 0, ',', '.') }} VNĐ
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($customer->last_appointment)->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-10 bg-gray-50 rounded-xl">
                        <div class="w-20 h-20 bg-gradient-to-r from-pink-200 to-purple-200 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner">
                            <i class="fas fa-users text-purple-500 text-2xl"></i>
                        </div>
                        <h3 class="text-gray-700 font-semibold text-xl mb-2">Không có dữ liệu</h3>
                        <p class="text-gray-500">Chưa có dữ liệu về khách hàng thân thiết.</p>
                    </div>
                @endif
            </div>
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
                labels: {!! json_encode($revenueChart['labels'] ?? []) !!},
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: {!! json_encode($revenueChart['data'] ?? []) !!},
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
        
        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Chờ xác nhận', 'Đã xác nhận', 'Hoàn thành', 'Đã hủy'],
                datasets: [{
                    data: [
                        {{ $statusChart['pending'] ?? 0 }},
                        {{ $statusChart['confirmed'] ?? 0 }},
                        {{ $statusChart['completed'] ?? 0 }},
                        {{ $statusChart['cancelled'] ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgba(251, 191, 36, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            boxWidth: 12,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endpush
