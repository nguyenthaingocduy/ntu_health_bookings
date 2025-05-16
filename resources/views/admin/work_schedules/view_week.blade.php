@extends('layouts.admin')

@section('title', 'Xem lịch làm việc theo tuần')

@section('header', 'Xem lịch làm việc theo tuần')

@push('styles')
<style>
    .schedule-cell {
        min-height: 60px;
    }

    .schedule-item {
        background-color: #dcfce7;
        border: 1px solid #22c55e;
        border-radius: 0.375rem;
        padding: 0.5rem;
        margin-bottom: 0.5rem;
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
        <h2 class="text-2xl font-semibold text-gray-900">Lịch làm việc theo tuần</h2>
        <div class="flex space-x-2">
            <a href="{{ route('admin.work-schedules.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-calendar-alt mr-2"></i>
                Phân công lịch làm việc
            </a>
        </div>
    </div>
    <p class="mt-1 text-sm text-gray-600">Xem lịch làm việc của nhân viên kỹ thuật theo tuần</p>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6">
        <div class="mb-4 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Tuần: {{ $dates[0]->format('d/m/Y') }} - {{ $dates[6]->format('d/m/Y') }}</h3>

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
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $date->format('D') }}<br>
                                {{ $date->format('d/m') }}
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
                                <td class="px-6 py-4">
                                    <div class="schedule-cell">
                                        @foreach($technicians as $technician)
                                            @if(isset($workSchedules[$technician->id][$date->format('Y-m-d')][$timeSlot->id]))
                                                <div class="schedule-item">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $technician->first_name }} {{ $technician->last_name }}
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
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
        <h3 class="text-lg font-medium text-gray-900 mb-4">Danh sách nhân viên kỹ thuật</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($technicians as $technician)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-4 flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <img class="h-12 w-12 rounded-full" src="https://ui-avatars.com/api/?name={{ $technician->first_name }}&background=0D8ABC&color=fff" alt="{{ $technician->first_name }}">
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $technician->first_name }} {{ $technician->last_name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $technician->email }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
