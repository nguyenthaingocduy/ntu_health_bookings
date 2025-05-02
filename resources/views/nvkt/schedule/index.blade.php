@extends('layouts.nvkt-new')

@section('title', 'Lịch làm việc')

@section('header', 'Lịch làm việc')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Lịch làm việc</h1>
            <p class="text-sm text-gray-500 mt-1">Xem lịch làm việc và các buổi hẹn</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('nvkt.dashboard') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
            <a href="{{ route('nvkt.schedule', ['date' => $startOfWeek->copy()->subWeek()->format('Y-m-d')]) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Tuần trước
            </a>
            <a href="{{ route('nvkt.schedule', ['date' => Carbon\Carbon::today()->format('Y-m-d')]) }}" class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors duration-150">
                Hôm nay
            </a>
            <a href="{{ route('nvkt.schedule', ['date' => $startOfWeek->copy()->addWeek()->format('Y-m-d')]) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                Tuần sau
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Lịch làm việc từ {{ $startOfWeek->format('d/m/Y') }} đến {{ $endOfWeek->format('d/m/Y') }}
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            Thời gian
                        </th>
                        @for ($day = 0; $day < 7; $day++)
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $startOfWeek->copy()->addDays($day)->format('D') }}<br>
                            {{ $startOfWeek->copy()->addDays($day)->format('d/m') }}
                            @if($startOfWeek->copy()->addDays($day)->isToday())
                            <span class="block mt-1 text-xs text-indigo-600 font-bold">Hôm nay</span>
                            @endif
                        </th>
                        @endfor
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($schedule as $timeSlotId => $timeSlotData)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $timeSlotData['time_slot']->start_time->format('H:i') }} - {{ $timeSlotData['time_slot']->end_time->format('H:i') }}
                            </div>
                        </td>
                        @foreach($timeSlotData['days'] as $dayData)
                        <td class="px-2 py-4 text-center">
                            @if($dayData['appointments']->count() > 0)
                                @foreach($dayData['appointments'] as $appointment)
                                <a href="{{ route('nvkt.sessions.show', $appointment->id) }}" class="block mb-2 p-2 rounded-lg
                                    @if($appointment->status == 'completed') bg-green-100 border border-green-300 text-green-800
                                    @elseif($appointment->status == 'in_progress') bg-purple-100 border border-purple-300 text-purple-800
                                    @elseif($appointment->status == 'confirmed') bg-blue-100 border border-blue-300 text-blue-800
                                    @elseif($appointment->status == 'pending') bg-yellow-100 border border-yellow-300 text-yellow-800
                                    @elseif($appointment->status == 'cancelled') bg-red-100 border border-red-300 text-red-800
                                    @else bg-gray-100 border border-gray-300 text-gray-800
                                    @endif
                                    hover:shadow-md transition-shadow duration-150">
                                    <div class="text-xs font-medium">{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}</div>
                                    <div class="text-xs">{{ Str::limit($appointment->service->name, 15) }}</div>
                                </a>
                                @endforeach
                            @else
                            <div class="text-xs text-gray-400">Không có lịch hẹn</div>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow-md p-4 flex items-center">
            <div class="w-4 h-4 rounded-full bg-blue-100 border border-blue-300 mr-2"></div>
            <span class="text-sm text-gray-700">Đã xác nhận</span>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 flex items-center">
            <div class="w-4 h-4 rounded-full bg-yellow-100 border border-yellow-300 mr-2"></div>
            <span class="text-sm text-gray-700">Chờ xác nhận</span>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 flex items-center">
            <div class="w-4 h-4 rounded-full bg-purple-100 border border-purple-300 mr-2"></div>
            <span class="text-sm text-gray-700">Đang thực hiện</span>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 flex items-center">
            <div class="w-4 h-4 rounded-full bg-green-100 border border-green-300 mr-2"></div>
            <span class="text-sm text-gray-700">Đã hoàn thành</span>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 flex items-center">
            <div class="w-4 h-4 rounded-full bg-red-100 border border-red-300 mr-2"></div>
            <span class="text-sm text-gray-700">Đã hủy</span>
        </div>
    </div>
</div>
@endsection
