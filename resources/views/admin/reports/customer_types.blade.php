@extends('layouts.admin')

@section('title', 'Báo cáo phân bố khách hàng theo loại')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-2xl font-medium text-gray-700 mb-6">Báo cáo phân bố khách hàng theo loại</h3>
    
    <!-- Tổng quan -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h4 class="text-lg font-medium text-gray-700 mb-4">Tổng quan</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">Tổng số khách hàng</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($totalCustomers) }}</p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">Số loại khách hàng</p>
                <p class="text-2xl font-bold text-gray-800">{{ $customerTypes->count() }}</p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">Loại khách hàng phổ biến nhất</p>
                @php
                    $mostPopular = $customerTypes->sortByDesc('users_count')->first();
                @endphp
                <p class="text-2xl font-bold" style="color: {{ $mostPopular->color_code }}">{{ $mostPopular->type_name }}</p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">Loại khách hàng ít phổ biến nhất</p>
                @php
                    $leastPopular = $customerTypes->sortBy('users_count')->first();
                @endphp
                <p class="text-2xl font-bold" style="color: {{ $leastPopular->color_code }}">{{ $leastPopular->type_name }}</p>
            </div>
        </div>
    </div>
    
    <!-- Biểu đồ phân bố -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h4 class="text-lg font-medium text-gray-700 mb-4">Phân bố khách hàng theo loại</h4>
            <div class="h-64">
                <canvas id="customerTypeDistribution"></canvas>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h4 class="text-lg font-medium text-gray-700 mb-4">Tăng trưởng khách hàng theo loại (6 tháng gần nhất)</h4>
            <div class="h-64">
                <canvas id="customerTypeGrowth"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Bảng chi tiết -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h4 class="text-lg font-medium text-gray-700 mb-4">Chi tiết phân bố khách hàng theo loại</h4>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại khách hàng</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phần trăm</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chi tiêu trung bình</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lần đặt lịch trung bình</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($customerTypes as $type)
                    <tr>
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <div class="h-4 w-4 rounded-full mr-2" style="background-color: {{ $type->color_code }}"></div>
                                <span class="font-medium">{{ $type->type_name }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">{{ number_format($type->users_count) }}</td>
                        <td class="py-3 px-4">{{ $type->percentage }}%</td>
                        <td class="py-3 px-4">{{ number_format($type->average_spending, 0, ',', '.') }} VNĐ</td>
                        <td class="py-3 px-4">{{ number_format($type->average_appointments, 1) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Thông tin chi tiết về từng loại khách hàng -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h4 class="text-lg font-medium text-gray-700 mb-4">Thông tin chi tiết về loại khách hàng</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($customerTypes as $type)
            <div class="border rounded-lg p-4" style="border-color: {{ $type->color_code }}">
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-lg font-medium" style="color: {{ $type->color_code }}">{{ $type->type_name }}</h5>
                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $type->users_count }} khách hàng</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Giảm giá:</span>
                        <span class="font-medium">{{ $type->formatted_discount }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Mức ưu tiên:</span>
                        <span class="font-medium">{{ $type->priority_level }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Chi tiêu tối thiểu:</span>
                        <span class="font-medium">{{ number_format($type->min_spending, 0, ',', '.') }} VNĐ</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Trạng thái:</span>
                        <span class="font-medium">{{ $type->is_active ? 'Đang kích hoạt' : 'Đã vô hiệu hóa' }}</span>
                    </div>
                </div>
                
                <p class="text-gray-600 text-sm mb-4">{{ $type->description }}</p>
                
                <a href="{{ route('admin.customer-types.edit', $type->id) }}" class="text-pink-600 hover:text-pink-700 text-sm">
                    <i class="fas fa-edit mr-1"></i> Chỉnh sửa
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Biểu đồ phân bố khách hàng theo loại
        const distributionCtx = document.getElementById('customerTypeDistribution').getContext('2d');
        new Chart(distributionCtx, {
            type: 'pie',
            data: {
                labels: [
                    @foreach($customerTypes as $type)
                    '{{ $type->type_name }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($customerTypes as $type)
                        {{ $type->users_count }},
                        @endforeach
                    ],
                    backgroundColor: [
                        @foreach($customerTypes as $type)
                        '{{ $type->color_code }}',
                        @endforeach
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
        
        // Biểu đồ tăng trưởng khách hàng theo loại
        const growthCtx = document.getElementById('customerTypeGrowth').getContext('2d');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($months as $month)
                    '{{ $month }}',
                    @endforeach
                ],
                datasets: [
                    @foreach($chartData as $typeId => $data)
                    {
                        label: '{{ $data['label'] }}',
                        data: [
                            @foreach($data['data'] as $count)
                            {{ $count }},
                            @endforeach
                        ],
                        borderColor: '{{ $data['color'] }}',
                        backgroundColor: '{{ $data['color'] }}20',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.1
                    },
                    @endforeach
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
