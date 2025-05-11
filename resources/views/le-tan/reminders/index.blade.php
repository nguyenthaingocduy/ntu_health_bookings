@extends('layouts.le-tan')

@section('title', 'Quản lý nhắc lịch hẹn')

@section('header', 'Quản lý nhắc lịch hẹn')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Quản lý nhắc lịch hẹn</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý các nhắc nhở lịch hẹn cho khách hàng</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('le-tan.reminders.create') }}" class="flex items-center px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors duration-150 shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tạo nhắc nhở mới
            </a>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('le-tan.reminders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                <select id="status" name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Đã gửi</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                </select>
            </div>
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Tên khách hàng, dịch vụ..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors duration-150">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Lọc
                </button>
                <a href="{{ route('le-tan.reminders.index') }}" class="ml-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150">
                    Đặt lại
                </a>
            </div>
        </form>
    </div>

    <!-- Danh sách nhắc nhở -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Loại nhắc
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
                    @forelse($reminders as $reminder)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ $reminder->appointment->customer->first_name }}&background=0D8ABC&color=fff" alt="{{ $reminder->appointment->customer->first_name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $reminder->appointment->customer->first_name }} {{ $reminder->appointment->customer->last_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $reminder->appointment->customer->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $reminder->appointment->service->name }}</div>
                            <div class="text-sm text-gray-500">{{ $reminder->appointment->date_appointments->format('d/m/Y') }} - {{ $reminder->appointment->timeSlot ? $reminder->appointment->timeSlot->start_time : ($reminder->appointment->timeAppointment ? $reminder->appointment->timeAppointment->started_time : 'N/A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $reminder->reminder_date->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($reminder->reminder_type == 'email') bg-blue-100 text-blue-800
                                @elseif($reminder->reminder_type == 'sms') bg-purple-100 text-purple-800
                                @else bg-indigo-100 text-indigo-800 @endif">
                                @if($reminder->reminder_type == 'email') Email
                                @elseif($reminder->reminder_type == 'sms') SMS
                                @else Email & SMS @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($reminder->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($reminder->status == 'sent') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                @if($reminder->status == 'pending') Đang chờ
                                @elseif($reminder->status == 'sent') Đã gửi
                                @else Thất bại @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('le-tan.reminders.show', $reminder->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                Chi tiết
                            </a>
                            @if($reminder->status == 'pending')
                            <a href="{{ route('le-tan.reminders.edit', $reminder->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                Sửa
                            </a>
                            <form action="{{ route('le-tan.reminders.send', $reminder->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                    Gửi ngay
                                </button>
                            </form>
                            <form action="{{ route('le-tan.reminders.destroy', $reminder->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Bạn có chắc chắn muốn xóa nhắc nhở này?')">
                                    Xóa
                                </button>
                            </form>
                            @else
                            <form action="{{ route('le-tan.reminders.send', $reminder->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                    Gửi lại
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            Không có dữ liệu nhắc nhở lịch hẹn
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $reminders->links() }}
        </div>
    </div>
</div>
@endsection
