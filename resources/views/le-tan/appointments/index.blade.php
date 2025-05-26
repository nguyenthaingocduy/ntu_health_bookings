@extends('layouts.le-tan')

@section('title', 'Quản lý lịch hẹn')

@section('header', 'Quản lý lịch hẹn')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Quản lý lịch hẹn</h1>
            <p class="text-sm text-gray-500 mt-1">Xem và quản lý các lịch hẹn của khách hàng</p>
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
            <a href="{{ route('le-tan.dashboard') }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    <!-- Enhanced Filters -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                </svg>
                Tìm kiếm và lọc lịch hẹn
            </h3>

            <form action="{{ route('le-tan.appointments.index') }}" method="GET" class="space-y-4">
                <!-- Search Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                               placeholder="Mã lịch hẹn, tên khách hàng, email, số điện thoại, dịch vụ..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                    </div>
                    @if(isset($services))
                    <div>
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">Dịch vụ</label>
                        <select name="service_id" id="service_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                            <option value="">Tất cả dịch vụ</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>

                <!-- Filter Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                        <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            <option value="no-show" {{ request('status') == 'no-show' ? 'selected' : '' }}>Không đến</option>
                        </select>
                    </div>
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
                        <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
                        <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lọc nhanh</label>
                        <div class="grid grid-cols-2 gap-1">
                            <button type="button" onclick="setDateRange('today')" class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors">Hôm nay</button>
                            <button type="button" onclick="setDateRange('week')" class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors">Tuần này</button>
                            <button type="button" onclick="setDateRange('month')" class="px-2 py-1 text-xs bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition-colors">Tháng này</button>
                            <button type="button" onclick="setDateRange('all')" class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">Tất cả</button>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                    <button type="submit" class="flex items-center px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors duration-150 shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Tìm kiếm
                    </button>
                    <a href="{{ route('le-tan.appointments.index') }}" class="flex items-center px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Xóa bộ lọc
                    </a>
                    @if(request()->hasAny(['search', 'status', 'service_id', 'date_from', 'date_to']))
                    <div class="flex items-center px-3 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Đang áp dụng bộ lọc
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <!-- Help Section -->
    @if(!request()->hasAny(['search', 'status', 'service_id', 'date_from', 'date_to']) && $appointments->count() > 0)
    {{-- <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>💡 Mẹo:</strong> Để xem lại những lịch hẹn cũ, bạn có thể:
                </p>
                <ul class="mt-2 text-sm text-blue-600 list-disc list-inside space-y-1">
                    <li>Sử dụng <strong>ô tìm kiếm</strong> để tìm theo tên khách hàng, email, hoặc mã lịch hẹn</li>
                    <li>Chọn <strong>khoảng thời gian</strong> từ ngày - đến ngày để xem lịch hẹn trong khoảng thời gian cụ thể</li>
                    <li>Sử dụng <strong>nút lọc nhanh</strong> để xem lịch hẹn hôm nay, tuần này, hoặc tháng này</li>
                    <li>Lọc theo <strong>trạng thái</strong> để xem các lịch hẹn đã hoàn thành, đã hủy, v.v.</li>
                </ul>
            </div>
        </div>
    </div> --}}
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mã lịch hẹn
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Khách hàng
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dịch vụ
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày hẹn
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Giờ hẹn
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($appointments as $appointment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $appointment->appointment_code ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $appointment->customer->full_name ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $appointment->customer->email ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $appointment->service->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($appointment->date_appointments && $appointment->date_appointments instanceof \DateTime)
                                    {{ $appointment->date_appointments->format('d/m/Y') }}
                                @elseif(is_string($appointment->date_appointments))
                                    {{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($appointment->timeSlot && $appointment->timeSlot->start_time instanceof \DateTime && $appointment->timeSlot->end_time instanceof \DateTime)
                                    {{ $appointment->timeSlot->start_time->format('H:i') }} - {{ $appointment->timeSlot->end_time->format('H:i') }}
                                @elseif($appointment->timeSlot && is_string($appointment->timeSlot->start_time) && is_string($appointment->timeSlot->end_time))
                                    {{ \Carbon\Carbon::parse($appointment->timeSlot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->timeSlot->end_time)->format('H:i') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
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
                                        Đã hoàn thành
                                    </span>
                                @elseif($appointment->status == 'cancelled')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Đã hủy
                                    </span>
                                @elseif($appointment->status == 'no-show')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Không đến
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $appointment->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('le-tan.appointments.show', $appointment->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                @if($appointment->status == 'pending' || $appointment->status == 'confirmed')
                                <a href="{{ route('le-tan.appointments.edit', $appointment->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                @endif
                                @if($appointment->status == 'pending')
                                <a href="{{ route('le-tan.appointments.assign-staff', $appointment->id) }}" class="text-green-600 hover:text-green-900 mr-3" title="Xác nhận và phân công nhân viên">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </a>
                                @endif

                                @if($appointment->status == 'pending' || $appointment->status == 'confirmed')
                                <button onclick="confirmCancel('{{ $appointment->id }}')" class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                                <form id="cancel-form-{{ $appointment->id }}" action="{{ route('le-tan.appointments.cancel', $appointment->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('PUT')
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    @if(request()->hasAny(['search', 'status', 'service_id', 'date_from', 'date_to']))
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Không tìm thấy lịch hẹn</h3>
                                        <p class="text-gray-500 mb-4">Không có lịch hẹn nào phù hợp với tiêu chí tìm kiếm của bạn.</p>
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <a href="{{ route('le-tan.appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                                Xóa bộ lọc
                                            </a>
                                            <a href="{{ route('le-tan.appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Tạo lịch hẹn mới
                                            </a>
                                        </div>
                                    @else
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có lịch hẹn nào</h3>
                                        <p class="text-gray-500 mb-4">Hệ thống chưa có lịch hẹn nào. Hãy tạo lịch hẹn đầu tiên.</p>
                                        <a href="{{ route('le-tan.appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Tạo lịch hẹn đầu tiên
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Results Summary -->
            @if($appointments->total() > 0)
            <div class="mt-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4 bg-gray-50 rounded-lg">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">{{ $appointments->total() }}</span> lịch hẹn được tìm thấy
                    @if(request()->hasAny(['search', 'status', 'service_id', 'date_from', 'date_to']))
                        <span class="text-blue-600">(đã lọc)</span>
                    @endif
                </div>
                <div class="text-sm text-gray-500">
                    Hiển thị {{ $appointments->firstItem() }} - {{ $appointments->lastItem() }} của {{ $appointments->total() }} kết quả
                </div>
            </div>
            @endif

            <!-- Pagination -->
            <div class="mt-6">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function confirmCancel(id) {
        if (confirm('Bạn có chắc chắn muốn hủy lịch hẹn này không?')) {
            document.getElementById('cancel-form-' + id).submit();
        }
    }

    // Date range quick filters
    function setDateRange(range) {
        const today = new Date();
        const dateFromInput = document.getElementById('date_from');
        const dateToInput = document.getElementById('date_to');

        let fromDate, toDate;

        switch(range) {
            case 'today':
                fromDate = toDate = today.toISOString().split('T')[0];
                break;
            case 'week':
                const startOfWeek = new Date(today);
                startOfWeek.setDate(today.getDate() - today.getDay());
                const endOfWeek = new Date(startOfWeek);
                endOfWeek.setDate(startOfWeek.getDate() + 6);
                fromDate = startOfWeek.toISOString().split('T')[0];
                toDate = endOfWeek.toISOString().split('T')[0];
                break;
            case 'month':
                const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                fromDate = startOfMonth.toISOString().split('T')[0];
                toDate = endOfMonth.toISOString().split('T')[0];
                break;
            case 'all':
                fromDate = toDate = '';
                break;
        }

        dateFromInput.value = fromDate;
        dateToInput.value = toDate;
    }

    // Auto-submit form when date inputs change (optional)
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action="{{ route('le-tan.appointments.index') }}"]');
        const autoSubmitInputs = ['status', 'service_id'];

        autoSubmitInputs.forEach(inputName => {
            const input = document.querySelector(`[name="${inputName}"]`);
            if (input) {
                input.addEventListener('change', function() {
                    // Optional: Auto-submit on select change
                    // form.submit();
                });
            }
        });
    });
</script>
@endsection
@endsection
