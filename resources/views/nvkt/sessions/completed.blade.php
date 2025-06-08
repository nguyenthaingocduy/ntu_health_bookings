@extends('layouts.nvkt-new')

@section('title', 'Phiên đã hoàn thành')

@section('header', 'Phiên đã hoàn thành')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Phiên đã hoàn thành</h1>
            <p class="text-sm text-gray-500 mt-1">Danh sách các buổi chăm sóc đã hoàn thành</p>
        </div>
        <div class="flex space-x-2">
            <form action="{{ route('nvkt.sessions.completed') }}" method="GET" class="flex items-center space-x-2">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Từ ngày</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 ">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Đến ngày</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 ">
                </div>
                <div class="pt-5">
                    <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors duration-150">
                        Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ngày & Giờ
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Khách hàng
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dịch vụ
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thời gian thực hiện
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Đánh giá
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($completedSessions as $session)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $session->date_appointments instanceof \Carbon\Carbon ? $session->date_appointments->format('d/m/Y') : $session->date_appointments }}</div>
                            <div class="text-sm text-gray-500">
                                @if($session->timeSlot)
                                    @php
                                        $startTime = $session->timeSlot->start_time;
                                        $endTime = $session->timeSlot->end_time;

                                        if (is_string($startTime)) {
                                            $startTime = substr($startTime, 0, 5);
                                        } else {
                                            $startTime = $startTime->format('H:i');
                                        }

                                        if (is_string($endTime)) {
                                            $endTime = substr($endTime, 0, 5);
                                        } else {
                                            $endTime = $endTime->format('H:i');
                                        }
                                    @endphp
                                    {{ $startTime . ' - ' . $endTime }}
                                @elseif($session->timeAppointment)
                                    {{ $session->timeAppointment->formatted_time ?? $session->timeAppointment->started_time }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    @if($session->customer)
                                        <img class="h-8 w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ $session->customer->first_name }}&background=0D8ABC&color=fff" alt="{{ $session->customer->first_name }}">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    @if($session->customer)
                                        <div class="text-sm font-medium text-gray-900">{{ $session->customer->first_name }} {{ $session->customer->last_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $session->customer->phone }}</div>
                                    @else
                                        <div class="text-sm font-medium text-gray-900">Khách hàng không tồn tại</div>
                                        <div class="text-sm text-gray-500">N/A</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($session->service)
                                <div class="text-sm text-gray-900">{{ $session->service->name }}</div>
                                <div class="text-sm text-gray-500">{{ number_format($session->final_price ?? $session->service->price ?? 0, 0, ',', '.') }} VNĐ</div>
                            @else
                                <div class="text-sm text-gray-900">Dịch vụ không tồn tại</div>
                                <div class="text-sm text-gray-500">{{ number_format($session->final_price ?? 0, 0, ',', '.') }} VNĐ</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($session->check_in_time && $session->check_out_time)
                                <div class="text-sm text-gray-900">
                                    @php
                                        $checkInTime = $session->check_in_time;
                                        $checkOutTime = $session->check_out_time;

                                        $checkInFormatted = is_string($checkInTime) ? substr($checkInTime, 0, 5) : $checkInTime->format('H:i');
                                        $checkOutFormatted = is_string($checkOutTime) ? substr($checkOutTime, 0, 5) : $checkOutTime->format('H:i');

                                        // Calculate duration in minutes
                                        if (!is_string($checkInTime) && !is_string($checkOutTime)) {
                                            $durationMinutes = $checkInTime->diffInMinutes($checkOutTime);
                                        } else {
                                            $durationMinutes = 'N/A';
                                        }
                                    @endphp
                                    {{ $checkInFormatted }} - {{ $checkOutFormatted }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $durationMinutes !== 'N/A' ? $durationMinutes . ' phút' : 'Không xác định' }}
                                </div>
                            @else
                                <div class="text-sm text-gray-500">Không có dữ liệu</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($session->rating)
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-900 mr-1">{{ $session->rating }}</div>
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                                @if($session->review)
                                    <div class="text-sm text-gray-500">{{ Str::limit($session->review, 30) }}</div>
                                @endif
                            @else
                                <div class="text-sm text-gray-500">Chưa đánh giá</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('nvkt.sessions.show', $session->id) }}" class="text-indigo-600 hover:text-indigo-900">Chi tiết</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            Không có phiên nào đã hoàn thành
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $completedSessions->links() }}
        </div>
    </div>
</div>
@endsection
