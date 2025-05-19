@extends('layouts.nvkt')

@section('title', 'Lịch làm việc')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Lịch làm việc</h1>
            <p class="text-sm text-gray-500 mt-1">Xem lịch làm việc được phân công</p>
        </div>
    </div>

    <!-- Week Navigation -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="bg-pink-500 text-white px-6 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <h2 class="text-xl font-semibold mb-3 md:mb-0">
                    Lịch làm việc: {{ $dates[0]->format('d/m/Y') }} - {{ $dates[6]->format('d/m/Y') }}
                </h2>
                <div class="flex space-x-2">
                    <a href="{{ route('nvkt.work-schedules.index', ['date' => $dates[0]->copy()->subWeek()->format('Y-m-d')]) }}" 
                       class="flex items-center px-3 py-1 bg-pink-600 hover:bg-pink-700 rounded-lg transition-colors duration-150">
                        <i class="fas fa-chevron-left mr-1"></i> Tuần trước
                    </a>
                    <a href="{{ route('nvkt.work-schedules.index', ['date' => Carbon\Carbon::today()->format('Y-m-d')]) }}" 
                       class="flex items-center px-3 py-1 bg-pink-600 hover:bg-pink-700 rounded-lg transition-colors duration-150">
                        Hôm nay
                    </a>
                    <a href="{{ route('nvkt.work-schedules.index', ['date' => $dates[0]->copy()->addWeek()->format('Y-m-d')]) }}" 
                       class="flex items-center px-3 py-1 bg-pink-600 hover:bg-pink-700 rounded-lg transition-colors duration-150">
                        Tuần sau <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <div class="ml-3">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Work Schedule Display -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-64">
                                Nhân viên
                            </th>
                            @foreach($dates as $index => $date)
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider {{ $date->isToday() ? 'bg-blue-50' : '' }}">
                                <div>{{ getDayName($index) }}</div>
                                <div class="font-bold {{ $date->isToday() ? 'text-blue-600' : 'text-gray-700' }}">{{ $date->format('d/m') }}</div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                             src="https://ui-avatars.com/api/?name={{ Auth::user()->first_name }}&background=0D8ABC&color=fff" 
                                             alt="{{ Auth::user()->first_name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ Auth::user()->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            @foreach($dates as $date)
                            <td class="px-6 py-4 whitespace-nowrap text-center {{ $date->isToday() ? 'bg-blue-50' : '' }}">
                                <div class="flex flex-col space-y-2">
                                    @php
                                        $morningScheduled = false;
                                        $afternoonScheduled = false;
                                        
                                        // Kiểm tra xem có lịch làm việc buổi sáng không
                                        foreach($timeSlots as $timeSlot) {
                                            $startTime = is_string($timeSlot->start_time) ? $timeSlot->start_time : $timeSlot->start_time->format('H:i:s');
                                            if (strtotime($startTime) < strtotime('12:00:00')) {
                                                if (isset($workSchedules[Auth::id()][$date->format('Y-m-d')][$timeSlot->id])) {
                                                    $morningScheduled = true;
                                                    break;
                                                }
                                            }
                                        }
                                        
                                        // Kiểm tra xem có lịch làm việc buổi chiều không
                                        foreach($timeSlots as $timeSlot) {
                                            $startTime = is_string($timeSlot->start_time) ? $timeSlot->start_time : $timeSlot->start_time->format('H:i:s');
                                            if (strtotime($startTime) >= strtotime('12:00:00')) {
                                                if (isset($workSchedules[Auth::id()][$date->format('Y-m-d')][$timeSlot->id])) {
                                                    $afternoonScheduled = true;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    
                                    <div class="flex items-center justify-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($morningScheduled && $afternoonScheduled) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            @if($morningScheduled && $afternoonScheduled)
                                                <i class="fas fa-check-circle mr-1"></i> Cả ngày
                                            @else
                                                <i class="fas fa-times-circle mr-1"></i> Cả ngày
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center justify-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $morningScheduled ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                            @if($morningScheduled)
                                                <i class="fas fa-check-circle mr-1"></i> Sáng
                                            @else
                                                <i class="fas fa-times-circle mr-1"></i> Sáng
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center justify-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $afternoonScheduled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            @if($afternoonScheduled)
                                                <i class="fas fa-check-circle mr-1"></i> Chiều
                                            @else
                                                <i class="fas fa-times-circle mr-1"></i> Chiều
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Chi tiết khung giờ làm việc</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Khung giờ
                                </th>
                                @foreach($dates as $index => $date)
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider {{ $date->isToday() ? 'bg-blue-50' : '' }}">
                                        {{ getDayName($index) }}<br>
                                        {{ $date->format('d/m') }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($timeSlots as $timeSlot)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            @php
                                                $startTime = is_string($timeSlot->start_time) ? substr($timeSlot->start_time, 0, 5) : $timeSlot->start_time->format('H:i');
                                                $endTime = is_string($timeSlot->end_time) ? substr($timeSlot->end_time, 0, 5) : $timeSlot->end_time->format('H:i');
                                            @endphp
                                            {{ $startTime }} - {{ $endTime }}
                                        </div>
                                    </td>
                                    @foreach($dates as $date)
                                        <td class="px-6 py-4 whitespace-nowrap text-center {{ $date->isToday() ? 'bg-blue-50' : '' }}">
                                            @if(isset($workSchedules[Auth::id()][$date->format('Y-m-d')][$timeSlot->id]))
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i> Có lịch
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-times-circle mr-1"></i> Không có lịch
                                                </span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@php
function getDayName($index) {
    $days = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'];
    return $days[$index];
}
@endphp
@endsection
