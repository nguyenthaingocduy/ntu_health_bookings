@extends('layouts.le-tan')

@section('title', 'Trang chủ Lễ tân')

@section('header', 'Trang chủ Lễ tân')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Trang chủ Lễ tân</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý lịch hẹn, khách hàng và thanh toán</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @can('appointments.create')
            <a href="{{ route('le-tan.appointments.create') }}" class="flex items-center px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors duration-150 shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tạo lịch hẹn mới
            </a>
            @endcan

            @can('customers.create')
            <a href="{{ route('le-tan.customers.index') }}?action=create" class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-150 shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Thêm khách hàng
            </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-dashboard.stat-card
            title="Lịch hẹn hôm nay"
            value="{{ $todayAppointmentsCount ?? 0 }}"
            color="pink"
            icon='<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
            </svg>'
            link="{{ route('le-tan.appointments.index') }}"
            linkText="Xem tất cả"
        >
            Lịch hẹn
        </x-dashboard.stat-card>

        <x-dashboard.stat-card
            title="Khách hàng"
            value="{{ $customersCount ?? 0 }}"
            color="blue"
            icon='<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
            </svg>'
            link="{{ route('le-tan.customers.index') }}"
            linkText="Quản lý khách hàng"
        >
            Tổng số khách hàng
        </x-dashboard.stat-card>

        <x-dashboard.stat-card
            title="Tư vấn đang chờ"
            value="{{ $pendingConsultationsCount ?? 0 }}"
            color="indigo"
            icon='<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
            </svg>'
            link="{{ route('le-tan.consultations.index') }}"
            linkText="Xem tư vấn"
        >
            Tư vấn dịch vụ
        </x-dashboard.stat-card>

        <x-dashboard.stat-card
            title="Nhắc nhở đang chờ"
            value="{{ $pendingRemindersCount ?? 0 }}"
            color="yellow"
            icon='<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
            </svg>'
            link="{{ route('le-tan.reminders.index') }}"
            linkText="Xem nhắc nhở"
        >
            Nhắc lịch hẹn
        </x-dashboard.stat-card>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <x-dashboard.stat-card
            title="Thanh toán hôm nay"
            value="{{ $todayPaymentsCount ?? 0 }}"
            color="green"
            icon='<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
            </svg>'
            link="{{ route('le-tan.payments.index') }}"
            linkText="Xem thanh toán"
        >
            Thanh toán
        </x-dashboard.stat-card>

        <x-dashboard.stat-card
            title="Hóa đơn tháng này"
            value="{{ $monthlyInvoicesCount ?? 0 }}"
            color="purple"
            icon='<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
            </svg>'
            link="{{ route('le-tan.invoices.index') }}"
            linkText="Xem hóa đơn"
        >
            Hóa đơn
        </x-dashboard.stat-card>

        <x-dashboard.stat-card
            title="Doanh thu tháng này"
            value="{{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }} VNĐ"
            color="red"
            icon='<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
            </svg>'
            link="{{ route('le-tan.payments.index') }}"
            linkText="Xem chi tiết"
        >
            Doanh thu
        </x-dashboard.stat-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <x-dashboard.data-table
            title="Lịch hẹn sắp tới"
            color="pink"
            icon='<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
            </svg>'
            link="{{ route('le-tan.appointments.index') }}"
            linkText="Xem tất cả lịch hẹn"
            emptyText="Không có lịch hẹn sắp tới"
        >
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Khách hàng
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dịch vụ
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thời gian
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($upcomingAppointments) && count($upcomingAppointments) > 0)
                            @foreach($upcomingAppointments as $appointment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $appointment->customer->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $appointment->customer->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $appointment->service->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $appointment->date_appointments ? $appointment->date_appointments->format('d/m/Y') : 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $appointment->timeAppointment ? $appointment->timeAppointment->started_time : 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @can('appointments.view')
                                    <a href="{{ route('le-tan.appointments.show', $appointment->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Chi tiết</a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Không có lịch hẹn sắp tới
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </x-dashboard.data-table>

        <x-dashboard.data-table
            title="Nhắc nhở lịch hẹn sắp tới"
            color="yellow"
            icon='<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
            </svg>'
            link="{{ route('le-tan.reminders.index') }}"
            linkText="Xem tất cả nhắc nhở"
            emptyText="Không có nhắc nhở lịch hẹn sắp tới"
        >
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Khách hàng
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lịch hẹn
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày nhắc
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($upcomingReminders) && count($upcomingReminders) > 0)
                            @foreach($upcomingReminders as $reminder)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $reminder->appointment && $reminder->appointment->customer ? $reminder->appointment->customer->full_name : 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $reminder->appointment && $reminder->appointment->service ? $reminder->appointment->service->name : 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $reminder->appointment && $reminder->appointment->date_appointments ? $reminder->appointment->date_appointments->format('d/m/Y') : 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $reminder->reminder_date->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('le-tan.reminders.show', $reminder->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Chi tiết</a>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Không có nhắc nhở lịch hẹn sắp tới
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </x-dashboard.data-table>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <x-dashboard.data-table
            title="Tư vấn dịch vụ gần đây"
            color="indigo"
            icon='<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
            </svg>'
            link="{{ route('le-tan.consultations.index') }}"
            linkText="Xem tất cả tư vấn"
            emptyText="Không có tư vấn dịch vụ gần đây"
        >
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Khách hàng
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dịch vụ
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($recentConsultations) && count($recentConsultations) > 0)
                            @foreach($recentConsultations as $consultation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $consultation->customer ? $consultation->customer->full_name : 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $consultation->service ? $consultation->service->name : 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($consultation->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($consultation->status == 'converted') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        @if($consultation->status == 'pending') Đang chờ
                                        @elseif($consultation->status == 'converted') Đã chuyển đổi
                                        @else Đã hủy @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('le-tan.consultations.show', $consultation->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Chi tiết</a>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Không có tư vấn dịch vụ gần đây
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </x-dashboard.data-table>

        <x-dashboard.data-table
            title="Dịch vụ được đặt nhiều nhất"
            color="green"
            icon='<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>'
            link="{{ route('le-tan.services.index') }}"
            linkText="Xem tất cả dịch vụ"
            emptyText="Không có dữ liệu dịch vụ"
        >
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tên dịch vụ
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Số lượt đặt
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($topServices) && count($topServices) > 0)
                            @foreach($topServices as $service)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-medium text-gray-900">{{ $service->appointment_count }}</div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Không có dữ liệu dịch vụ
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </x-dashboard.data-table>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <x-dashboard.data-table
            title="Thông báo gần đây"
            color="blue"
            icon='<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>'
            emptyText="Không có thông báo gần đây"
        >
            <div class="space-y-4">
                @if(isset($recentNotifications) && count($recentNotifications) > 0)
                    @foreach($recentNotifications as $notification)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ $notification->message }}</p>
                                <p class="mt-1 text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-sm text-gray-500 py-4">
                        Không có thông báo gần đây
                    </div>
                @endif
            </div>
        </x-dashboard.data-table>
    </div>
</div>
@endsection
