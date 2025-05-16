@extends('layouts.nvkt-new')

@section('title', 'Lịch làm việc')

@section('header', 'Lịch làm việc')

@push('styles')
<style>
    .schedule-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .schedule-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        position: relative;
    }
    
    .schedule-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    
    .schedule-body {
        padding: 1.5rem;
    }
    
    .schedule-nav {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    
    .schedule-nav-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 8px;
        color: white;
        cursor: pointer;
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.2s;
    }
    
    .schedule-nav-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    
    .schedule-days {
        display: flex;
        margin-bottom: 1rem;
        overflow-x: auto;
    }
    
    .schedule-day {
        align-items: center;
        border-radius: 8px;
        display: flex;
        flex: 1;
        flex-direction: column;
        font-weight: 500;
        margin: 0 0.25rem;
        padding: 0.75rem;
        text-align: center;
        min-width: 80px;
    }
    
    .schedule-day.today {
        background: #f0f9ff;
        border: 2px solid #3b82f6;
        color: #3b82f6;
    }
    
    .schedule-day-name {
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    
    .schedule-day-date {
        font-size: 1.25rem;
    }
    
    .schedule-day-month {
        font-size: 0.75rem;
        opacity: 0.8;
    }
    
    .time-slot {
        background: #f9fafb;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        padding: 0.75rem;
        transition: all 0.2s;
    }
    
    .time-slot.scheduled {
        background: #dcfce7;
        border: 1px solid #22c55e;
    }
    
    .time-slot-header {
        align-items: center;
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    
    .time-slot-time {
        font-weight: 500;
    }
    
    .time-slot-status {
        background: #e5e7eb;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
    }
    
    .time-slot-status.scheduled {
        background: #dcfce7;
        color: #166534;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Lịch làm việc</h1>
            <p class="text-sm text-gray-500 mt-1">Xem lịch làm việc được phân công</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('nvkt.dashboard') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    <div class="schedule-container">
        <div class="schedule-header">
            <div class="schedule-nav">
                <h3>Lịch làm việc: {{ $startOfWeek->format('d/m/Y') }} - {{ $endOfWeek->format('d/m/Y') }}</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('nvkt.work-schedules.index', ['date' => $startOfWeek->copy()->subWeek()->format('Y-m-d')]) }}" class="schedule-nav-btn">
                        <i class="fas fa-chevron-left mr-1"></i> Tuần trước
                    </a>
                    <a href="{{ route('nvkt.work-schedules.index', ['date' => Carbon\Carbon::today()->format('Y-m-d')]) }}" class="schedule-nav-btn">
                        Hôm nay
                    </a>
                    <a href="{{ route('nvkt.work-schedules.index', ['date' => $startOfWeek->copy()->addWeek()->format('Y-m-d')]) }}" class="schedule-nav-btn">
                        Tuần sau <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
            </div>
            
            <div class="schedule-days mt-4">
                @foreach($dates as $date)
                    <div class="schedule-day {{ $date->isToday() ? 'today' : '' }}">
                        <div class="schedule-day-name">{{ $date->locale('vi')->dayName }}</div>
                        <div class="schedule-day-date">{{ $date->format('d') }}</div>
                        <div class="schedule-day-month">{{ $date->locale('vi')->monthName }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="schedule-body">
            <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                @foreach($schedule as $dateStr => $daySchedule)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                            <h3 class="font-medium text-gray-700">
                                {{ $daySchedule['date']->locale('vi')->dayName }} ({{ $daySchedule['date']->format('d/m') }})
                            </h3>
                        </div>
                        
                        <div class="p-4">
                            @if(count($daySchedule['time_slots']) > 0)
                                @foreach($daySchedule['time_slots'] as $timeSlot)
                                    <div class="time-slot {{ $timeSlot['is_scheduled'] ? 'scheduled' : '' }}">
                                        <div class="time-slot-header">
                                            <span class="time-slot-time">{{ $timeSlot['start_time'] }} - {{ $timeSlot['end_time'] }}</span>
                                            <span class="time-slot-status {{ $timeSlot['is_scheduled'] ? 'scheduled' : '' }}">
                                                {{ $timeSlot['is_scheduled'] ? 'Đã phân công' : 'Trống' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4 text-gray-500">
                                    Không có khung giờ nào
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
