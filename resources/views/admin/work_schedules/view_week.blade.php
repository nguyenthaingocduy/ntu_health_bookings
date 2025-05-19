@extends('layouts.admin')

@section('title', 'Xem lịch làm việc')

@section('header', 'Xem lịch làm việc')

@push('styles')
<style>
    .schedule-cell {
        min-height: 60px;
    }

    .schedule-item {
        background-color: #e0f2fe;
        border: 1px solid #3b82f6;
        border-radius: 0.375rem;
        padding: 0.5rem;
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
    }

    .schedule-item:hover {
        background-color: #bfdbfe;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .time-slot-display {
        font-weight: 600;
        color: #374151;
    }
</style>
@endpush

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Lịch làm việc</h2>
            <p class="mt-1 text-sm text-gray-600">Xem lịch làm việc của nhân viên kỹ thuật</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.work-schedules.weekly-assignment') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-calendar-alt mr-2"></i>
                Phân công lịch làm việc
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6">
        <div class="mb-4 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-calendar-week text-blue-500 mr-2"></i>
                Tuần: {{ $dates[0]->format('d/m/Y') }} - {{ $dates[6]->format('d/m/Y') }}
            </h3>

            <div class="flex space-x-2">
                <a href="{{ route('admin.work-schedules.view-week', ['date' => $previousWeek]) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-chevron-left mr-2"></i>
                    Tuần trước
                </a>
                <a href="{{ route('admin.work-schedules.view-week', ['date' => $nextWeek]) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg">
                    Tuần sau
                    <i class="fas fa-chevron-right ml-2"></i>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Khung giờ
                        </th>
                        @foreach($dates as $date)
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium {{ $date->isToday() ? 'text-blue-600 bg-blue-50' : 'text-gray-500' }} uppercase tracking-wider">
                                <div class="font-medium">{{ $date->locale('vi')->dayName }}</div>
                                <div class="font-bold">{{ $date->format('d/m') }}</div>
                                @if($date->isToday())
                                    <div class="mt-1 inline-block px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs">Hôm nay</div>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($timeSlots as $timeSlot)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm time-slot-display">
                                    {{ $timeSlot->formatted_time }}
                                </div>
                            </td>
                            @foreach($dates as $date)
                                <td class="px-6 py-4 {{ $date->isToday() ? 'bg-blue-50' : '' }}">
                                    <div class="schedule-cell">
                                        @php
                                            $hasSchedule = false;
                                        @endphp

                                        @foreach($technicians as $technician)
                                            @php
                                                $dateStr = $date->format('Y-m-d');
                                                $hasUserSchedules = isset($workSchedules[$technician->id]);
                                                $hasDateSchedules = $hasUserSchedules && isset($workSchedules[$technician->id][$dateStr]);
                                                $hasTimeSlotSchedules = $hasDateSchedules && isset($workSchedules[$technician->id][$dateStr][$timeSlot->id]);
                                            @endphp

                                            @if($hasTimeSlotSchedules)
                                                @php $hasSchedule = true; @endphp
                                                <div class="schedule-item">
                                                    <div class="flex items-center">
                                                        <img class="h-6 w-6 rounded-full mr-2" src="https://ui-avatars.com/api/?name={{ $technician->first_name }}&background=3b82f6&color=fff" alt="{{ $technician->first_name }}">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $technician->first_name }} {{ $technician->last_name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                        @if(!$hasSchedule && request()->has('debug'))
                                            <div class="text-xs text-gray-400">
                                                Không có lịch
                                                <div>Date: {{ $date->format('Y-m-d') }}</div>
                                                <div>TimeSlot: {{ $timeSlot->id }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-8 bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="fas fa-users text-blue-500 mr-2"></i>
            Danh sách nhân viên kỹ thuật
        </h3>

        @if(request()->has('debug'))
        <div class="mb-4 p-4 bg-gray-100 rounded-lg">
            <h4 class="text-md font-medium text-gray-800 mb-2">Thông tin debug:</h4>
            <div class="text-sm">
                <p>Số lượng nhân viên: {{ count($technicians) }}</p>
                <p>Số lượng khung giờ: {{ count($timeSlots) }}</p>
                <p>Số lượng ngày: {{ count($dates) }}</p>
                <p>Dữ liệu lịch làm việc: {{ $workSchedules->isEmpty() ? 'Không có dữ liệu' : 'Có dữ liệu' }}</p>
                @if(!$workSchedules->isEmpty())
                    <p>Số lượng nhân viên có lịch: {{ $workSchedules->count() }}</p>
                    <p>ID nhân viên có lịch: {{ implode(', ', $workSchedules->keys()->toArray()) }}</p>
                @endif
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($technicians as $technician)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="p-4 flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <img class="h-12 w-12 rounded-full" src="https://ui-avatars.com/api/?name={{ $technician->first_name }}&background=3b82f6&color=fff" alt="{{ $technician->first_name }}">
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $technician->first_name }} {{ $technician->last_name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $technician->email }}
                            </div>
                            <div class="mt-1 flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-user-md mr-1"></i>
                                    Nhân viên kỹ thuật
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
