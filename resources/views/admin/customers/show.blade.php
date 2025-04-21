@extends('layouts.admin')

@section('title', 'Chi tiết khách hàng')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Chi tiết khách hàng</h1>
        <a href="{{ route('admin.customers.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-300 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer Information -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-gray-800 text-lg font-semibold">Thông tin khách hàng</h2>
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $customer->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $customer->status == 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                    </span>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user text-gray-400 text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">{{ $customer->first_name }} {{ $customer->last_name }}</h3>
                            <p class="text-gray-500 mt-1">Khách hàng</p>
                            <p class="text-gray-500 text-sm mt-1">Đăng ký: {{ $customer->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Thông tin liên hệ</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-envelope text-blue-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Email</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $customer->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-phone text-green-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Số điện thoại</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $customer->phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($customer->address)
                        <div class="mt-4 bg-gray-50 rounded-lg p-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-map-marker-alt text-yellow-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Địa chỉ</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $customer->address }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-gray-800 text-lg font-semibold">Thống kê lịch hẹn</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4 hover:shadow-sm transition duration-300">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 mr-4">
                                    <i class="fas fa-calendar text-blue-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Tổng lịch hẹn</p>
                                    <p class="text-xl font-semibold text-gray-800">{{ $statistics['total'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 hover:shadow-sm transition duration-300">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 mr-4">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Hoàn thành</p>
                                    <p class="text-xl font-semibold text-gray-800">{{ $statistics['completed'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 hover:shadow-sm transition duration-300">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-cyan-100 mr-4">
                                    <i class="fas fa-clipboard-check text-cyan-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Đã xác nhận</p>
                                    <p class="text-xl font-semibold text-gray-800">{{ $statistics['confirmed'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 hover:shadow-sm transition duration-300">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-amber-100 mr-4">
                                    <i class="fas fa-clock text-amber-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Chờ xác nhận</p>
                                    <p class="text-xl font-semibold text-gray-800">{{ $statistics['pending'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments History -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-gray-800 text-lg font-semibold">Lịch sử đặt lịch</h2>
                </div>
                <div class="p-6">
                    @if(count($appointments) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-100 rounded-lg overflow-hidden">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đặt lịch</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dịch vụ</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày & Giờ</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nhân viên</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($appointments as $appointment)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $appointment->code }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $appointment->service->name }}</div>
                                            <div class="text-sm text-gray-500 mt-1">{{ number_format($appointment->service->price, 0, ',', '.') }}đ</div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $appointment->date_appointments->format('d/m/Y') }}</div>
                                            <div class="text-sm text-gray-500 mt-1">{{ optional($appointment->timeAppointment)->started_time }}</div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if($appointment->employee)
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                                        <i class="fas fa-user-md text-blue-500 text-xs"></i>
                                                    </div>
                                                    <span class="text-sm text-gray-700">{{ $appointment->employee->first_name }} {{ $appointment->employee->last_name }}</span>
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic text-sm">Chưa phân công</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full
                                                {{ $appointment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                   ($appointment->status == 'confirmed' ? 'bg-blue-100 text-blue-800' :
                                                   ($appointment->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))
                                                }}">
                                                {{ $appointment->status_text }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-md transition duration-200 inline-flex items-center">
                                                <i class="fas fa-eye mr-1"></i> Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 bg-gray-50 rounded-lg">
                            <div class="bg-white rounded-full p-6 mb-4 shadow-sm">
                                <i class="fas fa-calendar-times text-gray-400 text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Chưa có lịch hẹn nào</h3>
                            <p class="text-gray-500 mb-4">Khách hàng này chưa đặt lịch hẹn nào</p>
                            <a href="{{ route('admin.appointments.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors duration-150 flex items-center">
                                <i class="fas fa-plus mr-2"></i> Tạo lịch hẹn mới
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
