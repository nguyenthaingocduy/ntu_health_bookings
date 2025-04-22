@extends('layouts.staff_new')

@section('title', 'Chỉnh sửa lịch hẹn - Cán bộ viên chức')
@section('page-title', 'Chỉnh sửa lịch hẹn')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<style>
    .select2-container--default .select2-selection--single {
        height: 42px;
        border-color: #e2e8f0;
        border-radius: 0.375rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px;
        padding-left: 1rem;
        color: #4a5568;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px;
    }
    .select2-dropdown {
        border-color: #e2e8f0;
        border-radius: 0.375rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #ec4899;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-700">Chỉnh sửa lịch hẹn</h3>
            <p class="text-sm text-gray-500">Cập nhật thông tin lịch hẹn</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('staff.appointments.show', $appointment->id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Xem chi tiết
            </a>
            <a href="{{ route('staff.appointments.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid gap-6 mb-8 md:grid-cols-3">
        <!-- Appointment Form -->
        <div class="col-span-2">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-pink-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                    <h4 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        Chỉnh sửa thông tin lịch hẹn
                    </h4>
                </div>
                <div class="p-6">
                    <form action="{{ route('staff.appointments.update', $appointment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Khách hàng</label>
                            <div class="p-3 bg-gray-50 rounded-md border border-gray-200">
                                <div class="flex items-center">
                                    <div class="relative hidden w-10 h-10 mr-3 rounded-full md:block">
                                        <img class="object-cover w-full h-full rounded-full" src="https://ui-avatars.com/api/?name={{ $appointment->customer->first_name }}+{{ $appointment->customer->last_name }}&background=random" alt="" loading="lazy" />
                                        <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-700">{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $appointment->customer->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Dịch vụ <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="service_id" id="service_id" class="service-select w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 @error('service_id') border-red-500 @enderror" required>
                                    <option value="">-- Chọn dịch vụ --</option>
                                    @foreach($services as $serviceItem)
                                        <option value="{{ $serviceItem->id }}" {{ (old('service_id', $appointment->service_id) == $serviceItem->id) ? 'selected' : '' }}>
                                            {{ $serviceItem->name }} - {{ number_format($serviceItem->price, 0, ',', '.') }} VNĐ
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('service_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="date_appointments" class="block text-sm font-medium text-gray-700 mb-1">Ngày hẹn <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="date_appointments" id="date_appointments" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 @error('date_appointments') border-red-500 @enderror" placeholder="Chọn ngày" value="{{ old('date_appointments', $appointment->date_appointments) }}" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('date_appointments')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="time_appointments_id" class="block text-sm font-medium text-gray-700 mb-1">Giờ hẹn <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="time_appointments_id" id="time_appointments_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 @error('time_appointments_id') border-red-500 @enderror" required>
                                    <option value="">-- Chọn giờ hẹn --</option>
                                    @foreach($times as $time)
                                        <option value="{{ $time->id }}" {{ old('time_appointments_id', $appointment->time_appointments_id) == $time->id ? 'selected' : '' }}>
                                            {{ $time->started_time }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('time_appointments_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 @error('status') border-red-500 @enderror" required>
                                    <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                    <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                            <div class="relative">
                                <textarea name="notes" id="notes" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 @error('notes') border-red-500 @enderror" placeholder="Nhập ghi chú hoặc yêu cầu đặc biệt (nếu có)">{{ old('notes', $appointment->notes) }}</textarea>
                            </div>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('staff.appointments.show', $appointment->id) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-150 flex items-center shadow-sm border border-gray-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Hủy
                            </a>
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-medium rounded-lg hover:shadow-lg transition-all duration-150 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Cập nhật lịch hẹn
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Information Sidebar -->
        <div class="col-span-1">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <h4 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Thông tin lịch hẹn
                    </h4>
                </div>
                <div class="p-6">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mã lịch hẹn:</span>
                            <span class="font-medium">{{ substr($appointment->id, 0, 8) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ngày đăng ký:</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($appointment->date_register)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Trạng thái hiện tại:</span>
                            <span class="font-medium">
                                @if($appointment->status == 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">
                                        Chờ xác nhận
                                    </span>
                                @elseif($appointment->status == 'confirmed')
                                    <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                        Đã xác nhận
                                    </span>
                                @elseif($appointment->status == 'completed')
                                    <span class="px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">
                                        Hoàn thành
                                    </span>
                                @elseif($appointment->status == 'cancelled')
                                    <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                                        Đã hủy
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                    <h4 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Hướng dẫn
                    </h4>
                </div>
                <div class="p-6">
                    <div class="space-y-4 text-sm">
                        <div>
                            <h5 class="flex items-center font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Thay đổi trạng thái
                            </h5>
                            <ul class="list-disc list-inside text-gray-600 ml-2">
                                <li class="mb-1"><strong>Chờ xác nhận</strong>: Lịch hẹn mới được tạo</li>
                                <li class="mb-1"><strong>Đã xác nhận</strong>: Lịch hẹn đã được xác nhận</li>
                                <li class="mb-1"><strong>Hoàn thành</strong>: Lịch hẹn đã được thực hiện</li>
                                <li><strong>Đã hủy</strong>: Lịch hẹn đã bị hủy</li>
                            </ul>
                    </div>

                        <div>
                            <h5 class="flex items-center font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Lưu ý
                            </h5>
                            <ul class="list-disc list-inside text-gray-600 ml-2">
                                <li class="mb-1">Khi thay đổi trạng thái, khách hàng sẽ nhận được thông báo</li>
                                <li class="mb-1">Kiểm tra kỹ thông tin trước khi cập nhật</li>
                                <li>Nếu muốn hủy lịch hẹn, hãy sử dụng nút "Hủy lịch hẹn" ở trang chi tiết</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        $('.service-select').select2({
            placeholder: "Chọn dịch vụ",
            allowClear: true
        });

        // Initialize date picker with better formatting
        const datePicker = flatpickr("#date_appointments", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            minDate: "today",
            locale: "vn",
            disableMobile: "true",
            allowInput: true,
            onReady: function() {
                // Fix for date display on page load
                const dateValue = "{{ $appointment->date_appointments }}";
                if (dateValue) {
                    this.setDate(dateValue, false);
                }
            }
        });

        // Handle date and service change
        const dateInput = document.getElementById('date_appointments');
        const serviceSelect = document.getElementById('service_id');
        const timeSelect = document.getElementById('time_appointments_id');
        const currentTimeId = "{{ $appointment->time_appointments_id }}";

        function updateAvailableTimeSlots() {
            const date = dateInput.value;
            const serviceId = serviceSelect.value;

            if (!date || !serviceId) return;

            // Disable time select while loading
            timeSelect.disabled = true;

            // Fetch available time slots
            fetch(`/api/check-available-slots?date=${date}&service_id=${serviceId}&exclude_appointment_id={{ $appointment->id }}`)
                .then(response => response.json())
                .then(data => {
                    // Clear current options
                    timeSelect.innerHTML = '<option value="">-- Chọn giờ hẹn --</option>';

                    // Always add the current time slot
                    let currentTimeAdded = false;

                    if (data.success && data.available_slots.length > 0) {
                        // Add available time slots
                        data.available_slots.forEach(slot => {
                            const option = document.createElement('option');
                            option.value = slot.id;
                            option.textContent = slot.started_time;

                            if (slot.id == currentTimeId) {
                                option.selected = true;
                                currentTimeAdded = true;
                            }

                            timeSelect.appendChild(option);
                        });
                    }

                    // If the current time slot wasn't in the available slots, add it separately
                    if (!currentTimeAdded && currentTimeId) {
                        fetch(`/api/time-slots/${currentTimeId}`)
                            .then(response => response.json())
                            .then(timeData => {
                                if (timeData.success) {
                                    const option = document.createElement('option');
                                    option.value = timeData.time_slot.id;
                                    option.textContent = timeData.time_slot.started_time;
                                    option.selected = true;
                                    timeSelect.appendChild(option);
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching current time slot:', error);
                            });
                    }

                    if (timeSelect.options.length <= 1) {
                        // No available slots
                        const option = document.createElement('option');
                        option.disabled = true;
                        option.textContent = 'Không có khung giờ trống cho ngày này';
                        timeSelect.appendChild(option);
                    }

                    // Enable time select
                    timeSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error fetching time slots:', error);
                    timeSelect.innerHTML = '<option value="">-- Lỗi khi tải khung giờ --</option>';
                    timeSelect.disabled = false;
                });
        }

        // Add event listeners
        dateInput.addEventListener('change', updateAvailableTimeSlots);
        serviceSelect.addEventListener('change', updateAvailableTimeSlots);

        // Initial update
        updateAvailableTimeSlots();
    });
</script>
@endsection
