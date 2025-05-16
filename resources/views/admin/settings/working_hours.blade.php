@extends('layouts.admin')

@section('title', 'Quản lý lịch làm việc')

@section('header', 'Quản lý lịch làm việc')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-900">Cài đặt giờ làm việc</h2>
    <p class="mt-1 text-sm text-gray-600">Thiết lập giờ làm việc cho từng ngày trong tuần</p>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6">
        <form action="{{ route('admin.settings.update-working-hours') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                @foreach($workingHours as $index => $day)
                <div class="p-4 border rounded-lg {{ $day->is_closed ? 'bg-gray-50' : 'bg-white' }}">
                    <input type="hidden" name="working_hours[{{ $index }}][id]" value="{{ $day->id }}">
                    
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $day->day_name }}</h3>
                        <div class="flex items-center">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="working_hours[{{ $index }}][is_closed]" 
                                    class="form-checkbox h-5 w-5 text-pink-600 rounded" 
                                    {{ $day->is_closed ? 'checked' : '' }}
                                    onchange="toggleTimeInputs(this, {{ $index }})">
                                <span class="ml-2 text-gray-700">Đóng cửa</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="time-inputs-{{ $index }}">
                        <div>
                            <label for="open_time_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-1">
                                Giờ mở cửa
                            </label>
                            <input type="time" id="open_time_{{ $index }}" 
                                name="working_hours[{{ $index }}][open_time]" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50"
                                value="{{ $day->open_time ? $day->open_time->format('H:i') : '' }}"
                                {{ $day->is_closed ? 'disabled' : '' }}>
                        </div>
                        
                        <div>
                            <label for="close_time_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-1">
                                Giờ đóng cửa
                            </label>
                            <input type="time" id="close_time_{{ $index }}" 
                                name="working_hours[{{ $index }}][close_time]" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50"
                                value="{{ $day->close_time ? $day->close_time->format('H:i') : '' }}"
                                {{ $day->is_closed ? 'disabled' : '' }}>
                        </div>
                        
                        <div>
                            <label for="note_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-1">
                                Ghi chú
                            </label>
                            <input type="text" id="note_{{ $index }}" 
                                name="working_hours[{{ $index }}][note]" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50"
                                value="{{ $day->note }}">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded-lg">
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Thông tin bổ sung -->
<div class="mt-8 bg-white rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin bổ sung</h3>
    
    <div class="space-y-4">
        <div class="flex items-start">
            <div class="flex-shrink-0 mt-0.5">
                <svg class="h-5 w-5 text-pink-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-gray-900">Giờ làm việc ảnh hưởng đến:</h4>
                <p class="mt-1 text-sm text-gray-600">
                    Giờ làm việc sẽ ảnh hưởng đến việc khách hàng có thể đặt lịch hẹn trong khoảng thời gian nào. 
                    Hệ thống sẽ chỉ cho phép đặt lịch trong giờ làm việc đã thiết lập.
                </p>
            </div>
        </div>
        
        <div class="flex items-start">
            <div class="flex-shrink-0 mt-0.5">
                <svg class="h-5 w-5 text-pink-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-gray-900">Đóng cửa:</h4>
                <p class="mt-1 text-sm text-gray-600">
                    Khi bạn đánh dấu "Đóng cửa" cho một ngày, khách hàng sẽ không thể đặt lịch hẹn vào ngày đó.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleTimeInputs(checkbox, index) {
        const timeInputsContainer = document.getElementById(`time-inputs-${index}`);
        const openTimeInput = document.getElementById(`open_time_${index}`);
        const closeTimeInput = document.getElementById(`close_time_${index}`);
        
        if (checkbox.checked) {
            // Đóng cửa - disable inputs
            openTimeInput.disabled = true;
            closeTimeInput.disabled = true;
            timeInputsContainer.classList.add('opacity-50');
        } else {
            // Mở cửa - enable inputs
            openTimeInput.disabled = false;
            closeTimeInput.disabled = false;
            timeInputsContainer.classList.remove('opacity-50');
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($workingHours as $index => $day)
            @if($day->is_closed)
                toggleTimeInputs(document.querySelector('input[name="working_hours[{{ $index }}][is_closed]"]'), {{ $index }});
            @endif
        @endforeach
    });
</script>
@endsection
