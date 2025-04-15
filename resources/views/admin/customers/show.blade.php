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
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-purple-500 to-indigo-600 px-6 py-4">
                    <h2 class="text-white text-lg font-semibold">Thông tin khách hàng</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-col items-center mb-6">
                        <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-user text-gray-500 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold">{{ $customer->first_name }} {{ $customer->last_name }}</h3>
                        <p class="text-gray-500">Khách hàng</p>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-3">Thông tin liên hệ</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-envelope text-indigo-500"></i>
                                </div>
                                <span class="text-gray-700">{{ $customer->email }}</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-phone text-indigo-500"></i>
                                </div>
                                <span class="text-gray-700">{{ $customer->phone }}</span>
                            </div>
                            @if($customer->address)
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-map-marker-alt text-indigo-500"></i>
                                </div>
                                <span class="text-gray-700">{{ $customer->address }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-3">Thông tin tài khoản</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Ngày đăng ký</span>
                                <span class="text-gray-700">{{ $customer->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Trạng thái</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $customer->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $customer->status == 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-indigo-600 px-6 py-4">
                    <h2 class="text-white text-lg font-semibold">Thống kê</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition duration-300">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 mr-4">
                                    <i class="fas fa-calendar text-blue-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase">Tổng lịch hẹn</p>
                                    <p class="text-xl font-semibold">{{ $statistics['total'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition duration-300">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 mr-4">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase">Hoàn thành</p>
                                    <p class="text-xl font-semibold">{{ $statistics['completed'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition duration-300">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-cyan-100 mr-4">
                                    <i class="fas fa-clipboard-check text-cyan-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase">Đã xác nhận</p>
                                    <p class="text-xl font-semibold">{{ $statistics['confirmed'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition duration-300">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-amber-100 mr-4">
                                    <i class="fas fa-clock text-amber-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase">Chờ xác nhận</p>
                                    <p class="text-xl font-semibold">{{ $statistics['pending'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments History -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-indigo-600 px-6 py-4">
                    <h2 class="text-white text-lg font-semibold">Lịch sử đặt lịch</h2>
                </div>
                <div class="p-6">
                    @if(count($appointments) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
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
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $appointment->code }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $appointment->service->name }}</div>
                                            <div class="text-sm text-gray-500">{{ number_format($appointment->service->price, 0, ',', '.') }}đ</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $appointment->date_appointments->format('d/m/Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ optional($appointment->timeAppointment)->started_time }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            @if($appointment->employee)
                                                {{ $appointment->employee->first_name }} {{ $appointment->employee->last_name }}
                                            @else
                                                <span class="text-gray-400 italic">Chưa phân công</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $appointment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                   ($appointment->status == 'confirmed' ? 'bg-blue-100 text-blue-800' :
                                                   ($appointment->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))
                                                }}">
                                                {{ $appointment->status_text }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition duration-200">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="bg-gray-100 rounded-full p-6 mb-4">
                                <i class="fas fa-calendar-times text-gray-400 text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">Chưa có lịch hẹn nào</h3>
                            <p class="text-gray-500">Khách hàng này chưa đặt lịch hẹn nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
