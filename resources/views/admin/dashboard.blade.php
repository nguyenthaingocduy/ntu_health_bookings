@extends('layouts.admin')

@section('title', 'Tổng quan')

@section('header', 'Tổng quan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-pink-100 text-pink-500">
                <i class="fas fa-users fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Khách hàng</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $totalCustomers }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                <i class="fas fa-user-tie fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Nhân viên</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $totalEmployees }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500">
                <i class="fas fa-spa fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Dịch vụ</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $totalServices }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                <i class="fas fa-calendar fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Cuộc hẹn</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $totalAppointments }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700">Cuộc hẹn gần đây</h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dịch vụ</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày hẹn</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentAppointments as $appointment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($appointment->user)->first_name }} {{ optional($appointment->user)->last_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($appointment->service)->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->date_appointments }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($appointment->status)
                                        @case('pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Chờ xác nhận
                                            </span>
                                            @break
                                        @case('confirmed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Đã xác nhận
                                            </span>
                                            @break
                                        @case('completed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Hoàn thành
                                            </span>
                                            @break
                                        @case('cancelled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Đã hủy
                                            </span>
                                            @break
                                        @default
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $appointment->status }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700">Thống kê trạng thái</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Chờ xác nhận</span>
                            <span class="text-sm font-medium text-gray-700">{{ $pendingAppointments }}</span>
                        </div>
                        <div class="overflow-hidden h-2 text-xs flex rounded bg-yellow-200">
                            <div style="width: {{ ($pendingAppointments / max($totalAppointments, 1)) * 100 }}%"
                                 class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-yellow-500">
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Đã xác nhận</span>
                            <span class="text-sm font-medium text-gray-700">{{ $confirmedAppointments }}</span>
                        </div>
                        <div class="overflow-hidden h-2 text-xs flex rounded bg-blue-200">
                            <div style="width: {{ ($confirmedAppointments / max($totalAppointments, 1)) * 100 }}%"
                                 class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500">
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Hoàn thành</span>
                            <span class="text-sm font-medium text-gray-700">{{ $completedAppointments }}</span>
                        </div>
                        <div class="overflow-hidden h-2 text-xs flex rounded bg-green-200">
                            <div style="width: {{ ($completedAppointments / max($totalAppointments, 1)) * 100 }}%"
                                 class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500">
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Đã hủy</span>
                            <span class="text-sm font-medium text-gray-700">{{ $canceledAppointments }}</span>
                        </div>
                        <div class="overflow-hidden h-2 text-xs flex rounded bg-red-200">
                            <div style="width: {{ ($canceledAppointments / max($totalAppointments, 1)) * 100 }}%"
                                 class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thêm phần thống kê nhân viên và khách hàng -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
    <!-- Thống kê nhân viên có nhiều lịch hẹn nhất -->
    <div>
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700">Nhân viên có nhiều lịch hẹn nhất</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($staffAppointments as $staff)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center text-pink-500 mr-3">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">
                                        @if($staff->employee)
                                            {{ $staff->employee->first_name }} {{ $staff->employee->last_name }}
                                        @else
                                            Nhân viên #{{ $staff->employee_id }}
                                        @endif
                                    </span>
                                </div>
                                <span class="text-sm font-medium bg-pink-100 text-pink-700 px-2 py-1 rounded-full">
                                    {{ $staff->total }} lịch hẹn
                                </span>
                            </div>
                            <div class="overflow-hidden h-2 text-xs flex rounded bg-pink-200">
                                <div style="width: {{ ($staff->total / max($staffAppointments->max('total'), 1)) * 100 }}%"
                                     class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-pink-500">
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Không có dữ liệu</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê khách hàng có nhiều lịch hẹn nhất -->
    <div>
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700">Khách hàng thân thiết</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($topCustomers as $customer)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 mr-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">
                                        @if($customer->user)
                                            {{ $customer->user->first_name }} {{ $customer->user->last_name }}
                                        @else
                                            Khách hàng #{{ $customer->customer_id }}
                                        @endif
                                    </span>
                                </div>
                                <span class="text-sm font-medium bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                                    {{ $customer->total }} lịch hẹn
                                </span>
                            </div>
                            <div class="overflow-hidden h-2 text-xs flex rounded bg-blue-200">
                                <div style="width: {{ ($customer->total / max($topCustomers->max('total'), 1)) * 100 }}%"
                                     class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500">
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Không có dữ liệu</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection