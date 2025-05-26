@extends('layouts.admin')

@section('title', 'Thống kê doanh thu')

@section('content')
<style>
@media print {
    .no-print {
        display: none !important;
    }

    .print-break {
        page-break-before: always;
    }

    .container {
        max-width: none !important;
        padding: 0 !important;
    }

    .shadow-md {
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }

    .bg-gradient-to-r {
        background: #f3f4f6 !important;
        color: #374151 !important;
    }

    .text-white {
        color: #374151 !important;
    }
}
</style>
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Thống kê doanh thu</h1>
        <div class="flex space-x-2 no-print">
            <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-print mr-2"></i> In báo cáo
            </button>
            <a href="{{ route('admin.revenue.export', request()->query()) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                <i class="fas fa-download mr-2"></i> Xuất Excel
            </a>
        </div>
    </div>

    <!-- Bộ lọc thời gian -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8 no-print">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Bộ lọc thời gian</h3>
        <form method="GET" action="{{ route('admin.revenue.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition">
                        <i class="fas fa-search mr-2"></i> Lọc
                    </button>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('admin.revenue.index') }}" class="w-full text-center bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition">
                        <i class="fas fa-times mr-2"></i> Xóa bộ lọc
                    </a>
                </div>
            </div>

            <!-- Quick filter buttons -->
            <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
                <span class="text-sm font-medium text-gray-700 mr-2">Lọc nhanh:</span>
                <button type="button" onclick="setQuickFilter('today')" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition">
                    Hôm nay
                </button>
                <button type="button" onclick="setQuickFilter('yesterday')" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition">
                    Hôm qua
                </button>
                <button type="button" onclick="setQuickFilter('this_week')" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition">
                    Tuần này
                </button>
                <button type="button" onclick="setQuickFilter('last_week')" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition">
                    Tuần trước
                </button>
                <button type="button" onclick="setQuickFilter('this_month')" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition">
                    Tháng này
                </button>
                <button type="button" onclick="setQuickFilter('last_month')" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition">
                    Tháng trước
                </button>
                <button type="button" onclick="setQuickFilter('this_year')" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition">
                    Năm này
                </button>
            </div>
        </form>
    </div>

    <!-- Tổng quan doanh thu -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Hôm nay</p>
                    <p class="text-2xl font-bold">{{ number_format($revenueOverview['today'], 0, ',', '.') }} VNĐ</p>
                </div>
                <div class="bg-blue-400 rounded-full p-3">
                    <i class="fas fa-calendar-day text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Tuần này</p>
                    <p class="text-2xl font-bold">{{ number_format($revenueOverview['this_week'], 0, ',', '.') }} VNĐ</p>
                </div>
                <div class="bg-green-400 rounded-full p-3">
                    <i class="fas fa-calendar-week text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Tháng này</p>
                    <p class="text-2xl font-bold">{{ number_format($revenueOverview['this_month'], 0, ',', '.') }} VNĐ</p>
                </div>
                <div class="bg-yellow-400 rounded-full p-3">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Năm nay</p>
                    <p class="text-2xl font-bold">{{ number_format($revenueOverview['this_year'], 0, ',', '.') }} VNĐ</p>
                </div>
                <div class="bg-purple-400 rounded-full p-3">
                    <i class="fas fa-calendar text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê khoảng thời gian được chọn -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            Doanh thu từ {{ $startDate->format('d/m/Y') }} đến {{ $endDate->format('d/m/Y') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="text-center">
                <p class="text-gray-600 text-sm">Tổng doanh thu</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($periodRevenue['total_revenue'], 0, ',', '.') }} VNĐ</p>
            </div>
            <div class="text-center">
                <p class="text-gray-600 text-sm">Từ lịch hẹn</p>
                <p class="text-xl font-semibold text-green-600">{{ number_format($periodRevenue['appointment_revenue'], 0, ',', '.') }} VNĐ</p>
            </div>
            <div class="text-center">
                <p class="text-gray-600 text-sm">Từ hóa đơn</p>
                <p class="text-xl font-semibold text-yellow-600">{{ number_format($periodRevenue['invoice_revenue'], 0, ',', '.') }} VNĐ</p>
            </div>
            <div class="text-center">
                <p class="text-gray-600 text-sm">Số lịch hẹn</p>
                <p class="text-xl font-semibold text-purple-600">{{ number_format($periodRevenue['appointment_count']) }}</p>
            </div>
            <div class="text-center">
                <p class="text-gray-600 text-sm">Trung bình/lịch hẹn</p>
                <p class="text-xl font-semibold text-pink-600">{{ number_format($periodRevenue['average_per_appointment'], 0, ',', '.') }} VNĐ</p>
            </div>
        </div>
    </div>

    <!-- Biểu đồ doanh thu -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Biểu đồ doanh thu 30 ngày -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Doanh thu 30 ngày gần đây</h3>
            <canvas id="dailyRevenueChart" width="400" height="200"></canvas>
        </div>

        <!-- Biểu đồ doanh thu 12 tháng -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Doanh thu 12 tháng gần đây</h3>
            <canvas id="monthlyRevenueChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Doanh thu theo dịch vụ và nhân viên -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Doanh thu theo dịch vụ -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Top 10 dịch vụ có doanh thu cao nhất</h3>
                <div class="text-sm text-gray-500">
                    {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
                </div>
            </div>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($serviceRevenue as $index => $service)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                {{ $index + 1 }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-800 truncate">{{ $service->service->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">{{ $service->appointment_count }} lịch hẹn</p>
                            </div>
                        </div>
                        <div class="text-right ml-4">
                            <p class="font-bold text-blue-600">{{ number_format($service->total_revenue, 0, ',', '.') }} VNĐ</p>
                            <p class="text-xs text-gray-500">
                                {{ number_format($service->total_revenue / max($service->appointment_count, 1), 0, ',', '.') }} VNĐ/lịch hẹn
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Không có dữ liệu dịch vụ trong khoảng thời gian này</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Doanh thu theo nhân viên -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Top 10 nhân viên có doanh thu cao nhất</h3>
                <div class="text-sm text-gray-500">
                    {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
                </div>
            </div>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($employeeRevenue as $index => $employee)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                {{ $index + 1 }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-800 truncate">
                                    {{ $employee->employee ? $employee->employee->first_name . ' ' . $employee->employee->last_name : 'N/A' }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $employee->appointment_count }} lịch hẹn</p>
                            </div>
                        </div>
                        <div class="text-right ml-4">
                            <p class="font-bold text-green-600">{{ number_format($employee->total_revenue, 0, ',', '.') }} VNĐ</p>
                            <p class="text-xs text-gray-500">
                                {{ number_format($employee->total_revenue / max($employee->appointment_count, 1), 0, ',', '.') }} VNĐ/lịch hẹn
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-user-tie text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Không có dữ liệu nhân viên trong khoảng thời gian này</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Thông tin bổ sung -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Thông tin bổ sung</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-percentage text-2xl text-blue-600"></i>
                </div>
                <h4 class="font-semibold text-gray-800 mb-2">Tỷ lệ hoàn thành</h4>
                <p class="text-2xl font-bold text-blue-600">
                    @if($periodRevenue['appointment_count'] > 0)
                        {{ number_format(($periodRevenue['appointment_count'] / ($periodRevenue['appointment_count'] + 0)) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </p>
                <p class="text-sm text-gray-600">Lịch hẹn hoàn thành</p>
            </div>

            <div class="text-center">
                <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-chart-line text-2xl text-green-600"></i>
                </div>
                <h4 class="font-semibold text-gray-800 mb-2">Tăng trưởng</h4>
                <p class="text-2xl font-bold text-green-600">+0%</p>
                <p class="text-sm text-gray-600">So với kỳ trước</p>
            </div>

            <div class="text-center">
                <div class="bg-yellow-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-star text-2xl text-yellow-600"></i>
                </div>
                <h4 class="font-semibold text-gray-800 mb-2">Dịch vụ phổ biến</h4>
                <p class="text-lg font-bold text-yellow-600">
                    {{ $serviceRevenue->first()->service->name ?? 'N/A' }}
                </p>
                <p class="text-sm text-gray-600">Dịch vụ có doanh thu cao nhất</p>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Quick filter functions
function setQuickFilter(period) {
    const today = new Date();
    let startDate, endDate;

    switch(period) {
        case 'today':
            startDate = endDate = today;
            break;
        case 'yesterday':
            startDate = endDate = new Date(today.getTime() - 24 * 60 * 60 * 1000);
            break;
        case 'this_week':
            const startOfWeek = new Date(today);
            startOfWeek.setDate(today.getDate() - today.getDay() + 1); // Monday
            startDate = startOfWeek;
            endDate = today;
            break;
        case 'last_week':
            const lastWeekEnd = new Date(today);
            lastWeekEnd.setDate(today.getDate() - today.getDay());
            const lastWeekStart = new Date(lastWeekEnd);
            lastWeekStart.setDate(lastWeekEnd.getDate() - 6);
            startDate = lastWeekStart;
            endDate = lastWeekEnd;
            break;
        case 'this_month':
            startDate = new Date(today.getFullYear(), today.getMonth(), 1);
            endDate = today;
            break;
        case 'last_month':
            startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            endDate = new Date(today.getFullYear(), today.getMonth(), 0);
            break;
        case 'this_year':
            startDate = new Date(today.getFullYear(), 0, 1);
            endDate = today;
            break;
    }

    document.getElementById('start_date').value = startDate.toISOString().split('T')[0];
    document.getElementById('end_date').value = endDate.toISOString().split('T')[0];

    // Auto submit form
    document.querySelector('form').submit();
}

document.addEventListener('DOMContentLoaded', function() {
    // Dữ liệu cho biểu đồ ngày
    const dailyData = @json($dailyRevenue);
    const dailyLabels = dailyData.map(item => item.date_formatted);
    const dailyValues = dailyData.map(item => item.revenue);

    // Biểu đồ doanh thu hàng ngày
    const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: dailyValues,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + ' VNĐ';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VNĐ';
                        }
                    }
                }
            }
        }
    });

    // Dữ liệu cho biểu đồ tháng
    const monthlyData = @json($monthlyRevenue);
    const monthlyLabels = monthlyData.map(item => item.month_formatted);
    const monthlyValues = monthlyData.map(item => item.revenue);

    // Biểu đồ doanh thu hàng tháng
    const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: monthlyValues,
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + ' VNĐ';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VNĐ';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
