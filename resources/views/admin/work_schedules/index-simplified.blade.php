@extends('layouts.admin')

@section('title', 'Phân công lịch làm việc')

@section('header', 'Phân công lịch làm việc')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Phân công lịch làm việc</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý lịch làm việc của nhân viên kỹ thuật</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.work-schedules.view-week') }}" class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-150">
                <i class="fas fa-calendar-week mr-2"></i>
                Xem lịch tuần
            </a>
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
                    <a href="{{ route('admin.work-schedules.index', ['date' => $dates[0]->copy()->subWeek()->format('Y-m-d')]) }}" 
                       class="flex items-center px-3 py-1 bg-pink-600 hover:bg-pink-700 rounded-lg transition-colors duration-150">
                        <i class="fas fa-chevron-left mr-1"></i> Tuần trước
                    </a>
                    <a href="{{ route('admin.work-schedules.index', ['date' => Carbon\Carbon::today()->format('Y-m-d')]) }}" 
                       class="flex items-center px-3 py-1 bg-pink-600 hover:bg-pink-700 rounded-lg transition-colors duration-150">
                        Hôm nay
                    </a>
                    <a href="{{ route('admin.work-schedules.index', ['date' => $dates[0]->copy()->addWeek()->format('Y-m-d')]) }}" 
                       class="flex items-center px-3 py-1 bg-pink-600 hover:bg-pink-700 rounded-lg transition-colors duration-150">
                        Tuần sau <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="font-medium">Đã xảy ra lỗi:</p>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

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

    <!-- Work Schedule Form -->
    <form id="schedule-form" action="{{ route('admin.work-schedules.store') }}" method="POST">
        @csrf
        
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
                                <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Tất cả
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($technicians as $technician)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="https://ui-avatars.com/api/?name={{ $technician->first_name }}&background=0D8ABC&color=fff" 
                                                 alt="{{ $technician->first_name }}">
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
                                </td>
                                
                                @foreach($dates as $date)
                                <td class="px-6 py-4 whitespace-nowrap text-center {{ $date->isToday() ? 'bg-blue-50' : '' }}">
                                    <div class="flex flex-col space-y-2">
                                        @php
                                            $morningScheduled = isset($workSchedules[$technician->id][$date->format('Y-m-d')]['morning']);
                                            $afternoonScheduled = isset($workSchedules[$technician->id][$date->format('Y-m-d')]['afternoon']);
                                            $fullDayId = "full-day-{$technician->id}-{$date->format('Y-m-d')}";
                                            $morningId = "morning-{$technician->id}-{$date->format('Y-m-d')}";
                                            $afternoonId = "afternoon-{$technician->id}-{$date->format('Y-m-d')}";
                                        @endphp
                                        
                                        <div class="flex items-center justify-center">
                                            <label for="{{ $fullDayId }}" class="flex items-center cursor-pointer">
                                                <input type="checkbox" id="{{ $fullDayId }}" 
                                                       class="full-day-checkbox h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded transition-colors duration-150"
                                                       data-user-id="{{ $technician->id }}"
                                                       data-date="{{ $date->format('Y-m-d') }}"
                                                       data-type="full-day"
                                                       {{ ($morningScheduled && $afternoonScheduled) ? 'checked' : '' }}>
                                                
                                                <span class="ml-2 text-xs font-medium text-gray-700">Cả ngày</span>
                                                
                                                @if($morningScheduled && $afternoonScheduled)
                                                    @php $index = time() + rand(1, 1000); @endphp
                                                    <input type="hidden" name="schedules[{{ $index }}][user_id]" value="{{ $technician->id }}">
                                                    <input type="hidden" name="schedules[{{ $index }}][date]" value="{{ $date->format('Y-m-d') }}">
                                                    <input type="hidden" name="schedules[{{ $index }}][type]" value="full-day">
                                                @endif
                                            </label>
                                        </div>
                                        
                                        <div class="flex items-center justify-center">
                                            <label for="{{ $morningId }}" class="flex items-center cursor-pointer">
                                                <input type="checkbox" id="{{ $morningId }}" 
                                                       class="half-day-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors duration-150"
                                                       data-user-id="{{ $technician->id }}"
                                                       data-date="{{ $date->format('Y-m-d') }}"
                                                       data-type="morning"
                                                       {{ $morningScheduled ? 'checked' : '' }}>
                                                
                                                <span class="ml-2 text-xs text-gray-700">Sáng</span>
                                                
                                                @if($morningScheduled && !$afternoonScheduled)
                                                    @php $index = time() + rand(1, 1000); @endphp
                                                    <input type="hidden" name="schedules[{{ $index }}][user_id]" value="{{ $technician->id }}">
                                                    <input type="hidden" name="schedules[{{ $index }}][date]" value="{{ $date->format('Y-m-d') }}">
                                                    <input type="hidden" name="schedules[{{ $index }}][type]" value="morning">
                                                @endif
                                            </label>
                                        </div>
                                        
                                        <div class="flex items-center justify-center">
                                            <label for="{{ $afternoonId }}" class="flex items-center cursor-pointer">
                                                <input type="checkbox" id="{{ $afternoonId }}" 
                                                       class="half-day-checkbox h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded transition-colors duration-150"
                                                       data-user-id="{{ $technician->id }}"
                                                       data-date="{{ $date->format('Y-m-d') }}"
                                                       data-type="afternoon"
                                                       {{ $afternoonScheduled ? 'checked' : '' }}>
                                                
                                                <span class="ml-2 text-xs text-gray-700">Chiều</span>
                                                
                                                @if($afternoonScheduled && !$morningScheduled)
                                                    @php $index = time() + rand(1, 1000); @endphp
                                                    <input type="hidden" name="schedules[{{ $index }}][user_id]" value="{{ $technician->id }}">
                                                    <input type="hidden" name="schedules[{{ $index }}][date]" value="{{ $date->format('Y-m-d') }}">
                                                    <input type="hidden" name="schedules[{{ $index }}][type]" value="afternoon">
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                @endforeach
                                
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="checkbox" 
                                                   class="select-all-days h-5 w-5 text-pink-600 focus:ring-pink-500 border-gray-300 rounded transition-colors duration-150"
                                                   data-user-id="{{ $technician->id }}">
                                            <span class="ml-2 text-xs font-medium text-gray-700">Tất cả</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors duration-150 flex items-center" id="saveButton">
                        <i class="fas fa-save mr-2"></i>
                        Lưu lịch làm việc
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@php
function getDayName($index) {
    $days = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'];
    return $days[$index];
}
@endphp

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fullDayCheckboxes = document.querySelectorAll('.full-day-checkbox');
        const halfDayCheckboxes = document.querySelectorAll('.half-day-checkbox');
        const selectAllDaysCheckboxes = document.querySelectorAll('.select-all-days');
        const saveButton = document.getElementById('saveButton');
        
        // Xử lý sự kiện khi checkbox "Cả ngày" được chọn/bỏ chọn
        fullDayCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const userId = this.dataset.userId;
                const date = this.dataset.date;
                const parentLabel = this.closest('label');
                
                // Tìm các checkbox buổi sáng và buổi chiều tương ứng
                const morningCheckbox = document.getElementById(`morning-${userId}-${date}`);
                const afternoonCheckbox = document.getElementById(`afternoon-${userId}-${date}`);
                
                if (this.checked) {
                    // Nếu checkbox "Cả ngày" được chọn, chọn cả buổi sáng và buổi chiều
                    morningCheckbox.checked = true;
                    afternoonCheckbox.checked = true;
                    
                    // Thêm input hidden cho "Cả ngày"
                    const index = Date.now() + Math.floor(Math.random() * 1000);
                    
                    const userIdInput = document.createElement('input');
                    userIdInput.type = 'hidden';
                    userIdInput.name = `schedules[${index}][user_id]`;
                    userIdInput.value = userId;
                    
                    const dateInput = document.createElement('input');
                    dateInput.type = 'hidden';
                    dateInput.name = `schedules[${index}][date]`;
                    dateInput.value = date;
                    
                    const typeInput = document.createElement('input');
                    typeInput.type = 'hidden';
                    typeInput.name = `schedules[${index}][type]`;
                    typeInput.value = 'full-day';
                    
                    parentLabel.appendChild(userIdInput);
                    parentLabel.appendChild(dateInput);
                    parentLabel.appendChild(typeInput);
                    
                    // Xóa input hidden của buổi sáng và buổi chiều nếu có
                    const morningLabel = morningCheckbox.closest('label');
                    const afternoonLabel = afternoonCheckbox.closest('label');
                    
                    morningLabel.querySelectorAll('input[type="hidden"]').forEach(input => input.remove());
                    afternoonLabel.querySelectorAll('input[type="hidden"]').forEach(input => input.remove());
                } else {
                    // Nếu checkbox "Cả ngày" bị bỏ chọn, bỏ chọn cả buổi sáng và buổi chiều
                    morningCheckbox.checked = false;
                    afternoonCheckbox.checked = false;
                    
                    // Xóa input hidden
                    parentLabel.querySelectorAll('input[type="hidden"]').forEach(input => input.remove());
                    
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
                            type: 'full-day'
                        })
                    });
                }
                
                // Cập nhật trạng thái của checkbox "Tất cả"
                updateSelectAllCheckbox(userId);
            });
        });
        
        // Xử lý sự kiện khi checkbox buổi sáng hoặc buổi chiều được chọn/bỏ chọn
        halfDayCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const userId = this.dataset.userId;
                const date = this.dataset.date;
                const type = this.dataset.type;
                const parentLabel = this.closest('label');
                
                // Tìm checkbox "Cả ngày" tương ứng
                const fullDayCheckbox = document.getElementById(`full-day-${userId}-${date}`);
                
                // Tìm checkbox buổi còn lại
                const otherType = type === 'morning' ? 'afternoon' : 'morning';
                const otherCheckbox = document.getElementById(`${otherType}-${userId}-${date}`);
                
                if (this.checked) {
                    // Nếu checkbox được chọn, thêm input hidden
                    const index = Date.now() + Math.floor(Math.random() * 1000);
                    
                    const userIdInput = document.createElement('input');
                    userIdInput.type = 'hidden';
                    userIdInput.name = `schedules[${index}][user_id]`;
                    userIdInput.value = userId;
                    
                    const dateInput = document.createElement('input');
                    dateInput.type = 'hidden';
                    dateInput.name = `schedules[${index}][date]`;
                    dateInput.value = date;
                    
                    const typeInput = document.createElement('input');
                    typeInput.type = 'hidden';
                    typeInput.name = `schedules[${index}][type]`;
                    typeInput.value = type;
                    
                    parentLabel.appendChild(userIdInput);
                    parentLabel.appendChild(dateInput);
                    parentLabel.appendChild(typeInput);
                    
                    // Nếu cả buổi sáng và buổi chiều đều được chọn, chọn "Cả ngày"
                    if (otherCheckbox.checked) {
                        fullDayCheckbox.checked = true;
                        
                        // Thêm input hidden cho "Cả ngày"
                        const fullDayIndex = Date.now() + Math.floor(Math.random() * 1000);
                        const fullDayLabel = fullDayCheckbox.closest('label');
                        
                        const fullDayUserIdInput = document.createElement('input');
                        fullDayUserIdInput.type = 'hidden';
                        fullDayUserIdInput.name = `schedules[${fullDayIndex}][user_id]`;
                        fullDayUserIdInput.value = userId;
                        
                        const fullDayDateInput = document.createElement('input');
                        fullDayDateInput.type = 'hidden';
                        fullDayDateInput.name = `schedules[${fullDayIndex}][date]`;
                        fullDayDateInput.value = date;
                        
                        const fullDayTypeInput = document.createElement('input');
                        fullDayTypeInput.type = 'hidden';
                        fullDayTypeInput.name = `schedules[${fullDayIndex}][type]`;
                        fullDayTypeInput.value = 'full-day';
                        
                        fullDayLabel.appendChild(fullDayUserIdInput);
                        fullDayLabel.appendChild(fullDayDateInput);
                        fullDayLabel.appendChild(fullDayTypeInput);
                        
                        // Xóa input hidden của buổi sáng và buổi chiều
                        parentLabel.querySelectorAll('input[type="hidden"]').forEach(input => input.remove());
                        otherCheckbox.closest('label').querySelectorAll('input[type="hidden"]').forEach(input => input.remove());
                    }
                } else {
                    // Nếu checkbox bị bỏ chọn, xóa input hidden
                    parentLabel.querySelectorAll('input[type="hidden"]').forEach(input => input.remove());
                    
                    // Bỏ chọn "Cả ngày"
                    if (fullDayCheckbox.checked) {
                        fullDayCheckbox.checked = false;
                        fullDayCheckbox.closest('label').querySelectorAll('input[type="hidden"]').forEach(input => input.remove());
                        
                        // Nếu buổi còn lại vẫn được chọn, thêm input hidden cho buổi đó
                        if (otherCheckbox.checked) {
                            const otherIndex = Date.now() + Math.floor(Math.random() * 1000);
                            const otherLabel = otherCheckbox.closest('label');
                            
                            const otherUserIdInput = document.createElement('input');
                            otherUserIdInput.type = 'hidden';
                            otherUserIdInput.name = `schedules[${otherIndex}][user_id]`;
                            otherUserIdInput.value = userId;
                            
                            const otherDateInput = document.createElement('input');
                            otherDateInput.type = 'hidden';
                            otherDateInput.name = `schedules[${otherIndex}][date]`;
                            otherDateInput.value = date;
                            
                            const otherTypeInput = document.createElement('input');
                            otherTypeInput.type = 'hidden';
                            otherTypeInput.name = `schedules[${otherIndex}][type]`;
                            otherTypeInput.value = otherType;
                            
                            otherLabel.appendChild(otherUserIdInput);
                            otherLabel.appendChild(otherDateInput);
                            otherLabel.appendChild(otherTypeInput);
                        }
                    }
                    
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
                            type: type
                        })
                    });
                }
                
                // Cập nhật trạng thái của checkbox "Tất cả"
                updateSelectAllCheckbox(userId);
            });
        });
        
        // Xử lý sự kiện khi checkbox "Tất cả" được chọn/bỏ chọn
        selectAllDaysCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const userId = this.dataset.userId;
                const fullDayCheckboxes = document.querySelectorAll(`.full-day-checkbox[data-user-id="${userId}"]`);
                
                fullDayCheckboxes.forEach(cb => {
                    if (cb.checked !== this.checked) {
                        cb.checked = this.checked;
                        cb.dispatchEvent(new Event('change'));
                    }
                });
            });
        });
        
        // Cập nhật trạng thái của checkbox "Tất cả" dựa trên trạng thái của các checkbox khác
        function updateSelectAllCheckbox(userId) {
            const fullDayCheckboxes = document.querySelectorAll(`.full-day-checkbox[data-user-id="${userId}"]`);
            const selectAllCheckbox = document.querySelector(`.select-all-days[data-user-id="${userId}"]`);
            
            const allChecked = Array.from(fullDayCheckboxes).every(cb => cb.checked);
            const anyChecked = Array.from(fullDayCheckboxes).some(cb => cb.checked);
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = anyChecked && !allChecked;
        }
        
        // Khởi tạo trạng thái ban đầu cho các checkbox "Tất cả"
        selectAllDaysCheckboxes.forEach(checkbox => {
            const userId = checkbox.dataset.userId;
            updateSelectAllCheckbox(userId);
        });
        
        // Xử lý sự kiện khi form được submit
        const scheduleForm = document.getElementById('schedule-form');
        scheduleForm.addEventListener('submit', function(e) {
            // Kiểm tra xem có lịch nào được chọn không
            const scheduledInputs = document.querySelectorAll('input[name^="schedules["]');
            
            if (scheduledInputs.length === 0) {
                e.preventDefault(); // Ngăn form submit
                
                // Hiển thị thông báo lỗi
                alert('Vui lòng chọn ít nhất một ngày làm việc để phân công lịch.');
                return false;
            }
            
            // Hiển thị trạng thái đang lưu
            saveButton.disabled = true;
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang lưu...';
            
            return true;
        });
    });
</script>
@endpush
@endsection
