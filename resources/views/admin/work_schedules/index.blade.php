@extends('layouts.admin')

@section('title', 'Phân công lịch làm việc')

@section('header', 'Phân công lịch làm việc')

@push('styles')
<style>
    /* Thiết kế mới cho lịch làm việc */
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

    .schedule-grid {
        display: grid;
        grid-template-columns: 200px repeat(7, 1fr);
        gap: 1px;
        background: #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
    }

    .schedule-cell {
        background: white;
        padding: 0.75rem;
        position: relative;
    }

    .schedule-cell.header {
        background: #f9fafb;
        font-weight: 500;
    }

    .schedule-cell.time {
        background: #f9fafb;
        display: flex;
        align-items: center;
        font-weight: 500;
    }

    .schedule-time-slot {
        background: #f9fafb;
        border-radius: 6px;
        cursor: pointer;
        margin: 0.25rem 0;
        padding: 0.5rem;
        text-align: center;
        transition: all 0.2s;
    }

    .schedule-time-slot:hover {
        background: #f3f4f6;
    }

    .schedule-time-slot.scheduled {
        background: #dcfce7;
        border: 1px solid #22c55e;
        color: #166534;
    }

    .schedule-time-text {
        font-family: 'Arial', sans-serif;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .technician-card {
        display: flex;
        align-items: center;
        padding: 0.75rem;
    }

    .technician-avatar {
        border-radius: 50%;
        height: 2.5rem;
        margin-right: 0.75rem;
        width: 2.5rem;
    }

    .technician-info {
        flex: 1;
    }

    .technician-name {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .technician-email {
        color: #6b7280;
        font-size: 0.75rem;
    }

    .save-button {
        background: linear-gradient(135deg, #f43f5e 0%, #ec4899 100%);
        border: none;
        border-radius: 8px;
        color: white;
        cursor: pointer;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.2s;
    }

    .save-button:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .view-week-button {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        border-radius: 8px;
        color: white;
        cursor: pointer;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .view-week-button:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .schedule-grid {
            grid-template-columns: 150px repeat(7, 1fr);
        }
    }

    @media (max-width: 768px) {
        .schedule-grid {
            display: block;
            overflow-x: auto;
        }
    }
</style>
@endpush

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Phân công lịch làm việc</h2>
            <p class="mt-1 text-sm text-gray-600">Quản lý lịch làm việc của nhân viên kỹ thuật</p>
        </div>
        <div>
            <a href="{{ route('admin.work-schedules.view-week') }}" class="view-week-button">
                <i class="fas fa-calendar-week mr-2"></i>
                Xem lịch tuần
            </a>
        </div>
    </div>
</div>

<div class="schedule-container">
    <div class="schedule-header">
        <div class="schedule-nav">
            <h3>Lịch làm việc: {{ $dates[0]->format('d/m/Y') }} - {{ $dates[6]->format('d/m/Y') }}</h3>
            <div class="flex space-x-2">
                <a href="{{ route('admin.work-schedules.index', ['date' => $dates[0]->copy()->subWeek()->format('Y-m-d')]) }}" class="schedule-nav-btn">
                    <i class="fas fa-chevron-left mr-1"></i> Tuần trước
                </a>
                <a href="{{ route('admin.work-schedules.index', ['date' => Carbon\Carbon::today()->format('Y-m-d')]) }}" class="schedule-nav-btn">
                    Hôm nay
                </a>
                <a href="{{ route('admin.work-schedules.index', ['date' => $dates[0]->copy()->addWeek()->format('Y-m-d')]) }}" class="schedule-nav-btn">
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
        <form id="schedule-form" action="{{ route('admin.work-schedules.store') }}" method="POST">
            @csrf
            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @foreach($technicians as $technician)
                <div class="mb-6 bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="technician-card border-b border-gray-200">
                        <img class="technician-avatar" src="https://ui-avatars.com/api/?name={{ $technician->first_name }}&background=0D8ABC&color=fff" alt="{{ $technician->first_name }}">
                        <div class="technician-info">
                            <div class="technician-name">{{ $technician->first_name }} {{ $technician->last_name }}</div>
                            <div class="technician-email">{{ $technician->email }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-7 gap-1 p-3">
                        @foreach($dates as $date)
                            <div class="p-2 {{ $date->isToday() ? 'bg-blue-50 rounded-lg' : '' }}">
                                <div class="text-xs font-medium text-center mb-2 {{ $date->isToday() ? 'text-blue-600' : 'text-gray-500' }}">
                                    {{ $date->format('d/m') }} ({{ substr($date->locale('vi')->dayName, 0, 3) }})
                                </div>

                                <div class="space-y-2">
                                    @foreach($timeSlots as $timeSlot)
                                        @php
                                            $isScheduled = isset($workSchedules[$technician->id][$date->format('Y-m-d')][$timeSlot->id]);
                                            $timeStart = is_string($timeSlot->start_time) ? substr($timeSlot->start_time, 0, 5) : $timeSlot->start_time->format('H:i');
                                            $timeEnd = is_string($timeSlot->end_time) ? substr($timeSlot->end_time, 0, 5) : $timeSlot->end_time->format('H:i');
                                        @endphp

                                        <div
                                            class="schedule-time-slot {{ $isScheduled ? 'scheduled' : '' }}"
                                            data-user-id="{{ $technician->id }}"
                                            data-date="{{ $date->format('Y-m-d') }}"
                                            data-time-slot-id="{{ $timeSlot->id }}"
                                        >
                                            <div class="schedule-time-text">{{ $timeStart }} - {{ $timeEnd }}</div>

                                            @if($isScheduled)
                                                @php $index = time() + rand(1, 1000); @endphp
                                                <input type="hidden" name="schedules[{{ $index }}][user_id]" value="{{ $technician->id }}">
                                                <input type="hidden" name="schedules[{{ $index }}][date]" value="{{ $date->format('Y-m-d') }}">
                                                <input type="hidden" name="schedules[{{ $index }}][time_slot_id]" value="{{ $timeSlot->id }}">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="mt-6 flex justify-end">
                <button type="submit" class="save-button" id="saveButton">
                    <i class="fas fa-save mr-2"></i>
                    Lưu lịch làm việc
                </button>
            </div>

            <!-- Thông báo lưu thành công -->
            @if(session('success'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
            @endif

            <!-- Thông báo lỗi -->
            @if(session('error'))
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
            @endif
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scheduleTimeSlots = document.querySelectorAll('.schedule-time-slot');
        const saveButton = document.getElementById('saveButton');

        // Thêm sự kiện click cho các khung giờ
        scheduleTimeSlots.forEach(slot => {
            slot.addEventListener('click', function() {
                const userId = this.dataset.userId;
                const date = this.dataset.date;
                const timeSlotId = this.dataset.timeSlotId;

                if (this.classList.contains('scheduled')) {
                    // Nếu đã lên lịch, xóa lịch
                    this.classList.remove('scheduled');

                    // Xóa các input hidden
                    const inputs = this.querySelectorAll('input[type="hidden"]');
                    inputs.forEach(input => input.remove());

                    // Hiệu ứng khi xóa lịch
                    this.style.animation = 'fadeOut 0.3s';
                    setTimeout(() => {
                        this.style.animation = '';
                    }, 300);

                    // Gọi API để xóa lịch làm việc
                    fetch('{{ route("admin.work-schedules.destroy") }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            user_id: userId,
                            date: date,
                            time_slot_id: timeSlotId
                        })
                    });
                } else {
                    // Nếu chưa lên lịch, thêm lịch
                    this.classList.add('scheduled');

                    // Hiệu ứng khi thêm lịch
                    this.style.animation = 'pulse 0.5s';
                    setTimeout(() => {
                        this.style.animation = '';
                    }, 500);

                    // Tạo index duy nhất cho mỗi lịch làm việc
                    const index = Date.now() + Math.floor(Math.random() * 1000);

                    // Thêm các input hidden
                    const userIdInput = document.createElement('input');
                    userIdInput.type = 'hidden';
                    userIdInput.name = `schedules[${index}][user_id]`;
                    userIdInput.value = userId;

                    const dateInput = document.createElement('input');
                    dateInput.type = 'hidden';
                    dateInput.name = `schedules[${index}][date]`;
                    dateInput.value = date;

                    const timeSlotIdInput = document.createElement('input');
                    timeSlotIdInput.type = 'hidden';
                    timeSlotIdInput.name = `schedules[${index}][time_slot_id]`;
                    timeSlotIdInput.value = timeSlotId;

                    this.appendChild(userIdInput);
                    this.appendChild(dateInput);
                    this.appendChild(timeSlotIdInput);
                }
            });
        });

        // Thêm sự kiện submit cho form
        const scheduleForm = document.getElementById('schedule-form');
        scheduleForm.addEventListener('submit', function(e) {
            // Kiểm tra xem có lịch nào được chọn không
            const scheduledInputs = document.querySelectorAll('input[name^="schedules["]');

            if (scheduledInputs.length === 0) {
                e.preventDefault(); // Ngăn form submit

                // Hiển thị thông báo lỗi
                const errorDiv = document.createElement('div');
                errorDiv.className = 'mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded';
                errorDiv.textContent = 'Vui lòng chọn ít nhất một khung giờ để phân công lịch làm việc.';

                // Thêm thông báo vào form
                const existingError = scheduleForm.querySelector('.bg-red-100');
                if (existingError) {
                    existingError.remove();
                }

                scheduleForm.appendChild(errorDiv);

                // Cuộn đến thông báo lỗi
                errorDiv.scrollIntoView({ behavior: 'smooth' });

                return false;
            }

            // Hiển thị trạng thái đang lưu
            saveButton.disabled = true;
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang lưu...';

            return true;
        });
    });
</script>

<style>
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    @keyframes fadeOut {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .fa-spin {
        animation: spin 1s linear infinite;
    }
</style>
@endpush
@endsection
