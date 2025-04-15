@extends('layouts.staff_tailwind')

@section('title', 'Thống kê - Cán bộ viên chức')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Thống kê</h1>
        <p class="text-gray-600">Theo dõi số lượng khách hàng, doanh thu và hiệu suất</p>
    </div>
    <div class="mt-4 md:mt-0">
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-calendar-alt mr-2"></i>
                {{ $period }}
                <i class="fas fa-chevron-down ml-2"></i>
            </button>
            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10"
                 x-cloak>
                <div class="py-1">
                    <a href="{{ route('staff.statistics', ['period' => 'today']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('period') == 'today' ? 'bg-gray-100' : '' }}">Hôm nay</a>
                    <a href="{{ route('staff.statistics', ['period' => 'yesterday']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('period') == 'yesterday' ? 'bg-gray-100' : '' }}">Hôm qua</a>
                    <a href="{{ route('staff.statistics', ['period' => 'this_week']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('period') == 'this_week' ? 'bg-gray-100' : '' }}">Tuần này</a>
                    <a href="{{ route('staff.statistics', ['period' => 'last_week']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('period') == 'last_week' ? 'bg-gray-100' : '' }}">Tuần trước</a>
                    <a href="{{ route('staff.statistics', ['period' => 'this_month']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('period') == 'this_month' || !request('period') ? 'bg-gray-100' : '' }}">Tháng này</a>
                    <a href="{{ route('staff.statistics', ['period' => 'last_month']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('period') == 'last_month' ? 'bg-gray-100' : '' }}">Tháng trước</a>
                    <a href="{{ route('staff.statistics', ['period' => 'this_year']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('period') == 'this_year' ? 'bg-gray-100' : '' }}">Năm nay</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Appointments -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                    <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Tổng số lịch hẹn
                        </dt>
                        <dd>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ $stats['total_appointments'] }}
                            </div>
                        </dd>
                    </dl>
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
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Tổng số khách hàng
                        </dt>
                        <dd>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ $stats['total_customers'] }}
                            </div>
                        </dd>
                    </dl>
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
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-amber-100 rounded-md p-3">
                    <i class="fas fa-coins text-amber-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Tổng doanh thu
                        </dt>
                        <dd>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ number_format($stats['total_revenue'], 0, ',', '.') }} VNĐ
                            </div>
                        </dd>
                    </dl>
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
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Doanh thu trung bình/lịch hẹn
                        </dt>
                        <dd>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ number_format($stats['avg_revenue'], 0, ',', '.') }} VNĐ
                            </div>
                        </dd>
                    </dl>
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
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Doanh thu theo thời gian</h2>
        </div>
        <div class="p-6">
            <canvas id="revenueChart" height="300"></canvas>
        </div>
    </div>

    <!-- Appointments by Status -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Lịch hẹn theo trạng thái</h2>
        </div>
        <div class="p-6">
            <canvas id="statusChart" height="300"></canvas>
        </div>
    </div>
</div>

<!-- Top Services and Customers -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Top Services -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Dịch vụ phổ biến nhất</h2>
        </div>
        <div class="p-6">
            @if(count($topServices) > 0)
                <div class="space-y-4">
                    @foreach($topServices as $service)
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-spa text-blue-600"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                                    <div class="text-sm font-medium text-blue-600">{{ number_format($service->total_revenue, 0, ',', '.') }} VNĐ</div>
                                </div>
                                <div class="mt-1 flex items-center">
                                    <div class="flex-grow bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($service->count / $topServices->max('count')) * 100 }}%"></div>
                                    </div>
                                    <span class="ml-2 text-xs text-gray-500">{{ $service->count }} lượt</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6">
                    <p class="text-gray-500">Không có dữ liệu</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Top Customers -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Khách hàng thân thiết nhất</h2>
        </div>
        <div class="p-6">
            @if(count($topCustomers) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lần đặt</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng chi tiêu</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lần cuối</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($topCustomers as $customer)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 font-medium">{{ substr($customer->first_name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $customer->phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $customer->appointment_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
                <div class="text-center py-6">
                    <p class="text-gray-500">Không có dữ liệu</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueChart['labels']) !!},
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: {!! json_encode($revenueChart['data']) !!},
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
        
        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Chờ xác nhận', 'Đã xác nhận', 'Hoàn thành', 'Đã hủy'],
                datasets: [{
                    data: [
                        {{ $statusChart['pending'] }},
                        {{ $statusChart['confirmed'] }},
                        {{ $statusChart['completed'] }},
                        {{ $statusChart['cancelled'] }}
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
                            boxWidth: 12
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
@endsection
