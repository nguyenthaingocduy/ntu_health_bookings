@extends('layouts.admin')

@section('title', 'Tạo lịch hẹn mới')

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
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #a0aec0 transparent transparent transparent;
    }
    .select2-dropdown {
        border-color: #e2e8f0;
        border-radius: 0.375rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border-color: #e2e8f0;
        border-radius: 0.375rem;
        padding: 0.5rem;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #f56565;
    }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
        background: #f56565;
        border-color: #f56565;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tạo lịch hẹn mới</h1>
            <p class="text-sm text-gray-500 mt-1">Đặt lịch hẹn cho khách hàng</p>
        </div>
        <a href="{{ route('admin.appointments.index') }}" class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-150 shadow-sm border border-gray-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Quay lại
        </a>
    </div>

    <nav class="mb-8">
        <ol class="flex text-sm text-gray-500">
            <li class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-pink-500">Tổng quan</a>
                <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </li>
            <li class="flex items-center">
                <a href="{{ route('admin.appointments.index') }}" class="hover:text-pink-500">Quản lý lịch hẹn</a>
                <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </li>
            <li class="text-pink-500 font-medium">Tạo lịch hẹn mới</li>
        </ol>
    </nav>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-pink-50 to-pink-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        Thông tin lịch hẹn
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.appointments.store') }}" method="POST" id="appointment-form">
                        @csrf

                        <div class="mb-6">
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Khách hàng <span class="text-red-500">*</span></label>
                            <select class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 customer-select @error('customer_id') border-red-500 @enderror" id="customer_id" name="customer_id" required>
                                <option value="">-- Chọn khách hàng --</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->full_name }} ({{ $customer->email }})</option>
                                @endforeach
                            </select>
                            @error('customer_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">Dịch vụ <span class="text-red-500">*</span></label>
                            <select class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 service-select @error('service_id') border-red-500 @enderror" id="service_id" name="service_id" required>
                                <option value="">-- Chọn dịch vụ --</option>
                                @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ isset($service) && $service->id == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} - {{ number_format($service->price, 0, ',', '.') }}đ
                                </option>
                                @endforeach
                            </select>
                            @error('service_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="date_appointments" class="block text-sm font-medium text-gray-700 mb-2">Ngày hẹn <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <input type="text" class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('date_appointments') border-red-500 @enderror" id="date_appointments" name="date_appointments" required>
                            </div>
                            @error('date_appointments')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="time_appointments_id" class="block text-sm font-medium text-gray-700 mb-2">Giờ hẹn <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3" id="time-slots-container">
                                <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-3 rounded">
                                    <p class="text-sm">Vui lòng chọn ngày hẹn trước</p>
                                </div>
                            </div>
                            @error('time_appointments_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái <span class="text-red-500">*</span></label>
                            <select class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('status') border-red-500 @enderror" id="status" name="status" required>
                                <option value="pending">Chờ xác nhận</option>
                                <option value="confirmed">Đã xác nhận</option>
                            </select>
                            @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                            <textarea class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('notes') border-red-500 @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end mt-8">
                            <a href="{{ route('admin.appointments.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150 mr-4">
                                Hủy
                            </a>
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-lg hover:from-pink-600 hover:to-pink-700 transition-colors duration-150 shadow-md flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                Lưu lịch hẹn
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 sticky top-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Hướng dẫn
                    </h3>
                </div>
                <div class="p-6">
                    <h5 class="flex items-center text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                        </svg>
                        Quy trình đặt lịch
                    </h5>
                    <ol class="list-decimal list-inside text-sm text-gray-600 ml-2 mb-6">
                        <li class="mb-1">Chọn khách hàng</li>
                        <li class="mb-1">Chọn dịch vụ</li>
                        <li class="mb-1">Chọn ngày hẹn</li>
                        <li class="mb-1">Chọn giờ hẹn</li>
                        <li class="mb-1">Chọn trạng thái</li>
                        <li class="mb-1">Nhập ghi chú (nếu có)</li>
                        <li>Lưu lịch hẹn</li>
                    </ol>

                    <h5 class="flex items-center text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        Lưu ý
                    </h5>
                    <ul class="list-disc list-inside text-sm text-gray-600 ml-2">
                        <li class="mb-1">Chỉ có thể đặt lịch cho khách hàng đã đăng ký tài khoản</li>
                        <li class="mb-1">Nếu chọn trạng thái "Đã xác nhận", khách hàng sẽ nhận được thông báo ngay lập tức</li>
                        <li>Khung giờ đã đầy sẽ không thể chọn</li>
                    </ul>
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
        $('.customer-select').select2({
            placeholder: "Chọn khách hàng",
            allowClear: true
        });

        $('.service-select').select2({
            placeholder: "Chọn dịch vụ",
            allowClear: true
        });

        // Initialize date picker
        flatpickr("#date_appointments", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            minDate: "today",
            locale: "vn",
            disableMobile: true,
            onChange: function(selectedDates, dateStr, instance) {
                updateAvailableTimeSlots();
            }
        });

        // Handle service change
        document.getElementById('service_id').addEventListener('change', function() {
            if (document.getElementById('date_appointments').value) {
                updateAvailableTimeSlots();
            }
        });

        // Function to update available time slots
        function updateAvailableTimeSlots() {
            const date = document.getElementById('date_appointments').value;
            const serviceId = document.getElementById('service_id').value;
            const timeSlotsContainer = document.getElementById('time-slots-container');

            if (!date || !serviceId) {
                timeSlotsContainer.innerHTML = `
                    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-3 rounded">
                        <p class="text-sm">Vui lòng chọn ngày hẹn và dịch vụ</p>
                    </div>
                `;
                return;
            }

            timeSlotsContainer.innerHTML = `
                <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-3 rounded">
                    <p class="text-sm">Đang tải khung giờ...</p>
                </div>
            `;

            // Fetch available time slots
            fetch(`/api/available-time-slots?date=${date}&service_id=${serviceId}`)
                .then(response => response.json())
                .then(data => {
                    timeSlotsContainer.innerHTML = '';

                    if (data.length > 0) {
                        data.forEach(slot => {
                            const timeSlot = document.createElement('div');

                            if (slot.is_full) {
                                timeSlot.className = 'bg-red-50 border border-red-200 text-red-700 rounded-lg p-3 text-center cursor-not-allowed opacity-70';
                            } else {
                                timeSlot.className = 'bg-white border border-gray-200 hover:border-pink-300 hover:bg-pink-50 rounded-lg p-3 text-center cursor-pointer transition-colors duration-150';
                            }

                            timeSlot.innerHTML = `
                                <div class="font-medium">${slot.time}</div>
                                <div class="text-xs mt-1 ${slot.is_full ? 'text-red-600' : 'text-gray-500'}">
                                    ${slot.booked_count}/${slot.capacity} đã đặt
                                </div>
                                <input type="radio" name="time_appointments_id" value="${slot.id}"
                                    ${slot.is_full ? 'disabled' : ''} class="hidden">
                            `;

                            if (!slot.is_full) {
                                timeSlot.addEventListener('click', function() {
                                    document.querySelectorAll('[name="time_appointments_id"]').forEach(input => {
                                        input.closest('div').classList.remove('ring-2', 'ring-pink-500', 'bg-pink-50');
                                    });
                                    this.classList.add('ring-2', 'ring-pink-500', 'bg-pink-50');
                                    this.querySelector('input[type="radio"]').checked = true;
                                });
                            }

                            timeSlotsContainer.appendChild(timeSlot);
                        });
                    } else {
                        timeSlotsContainer.innerHTML = `
                            <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-3 rounded">
                                <p class="text-sm">Không có khung giờ nào khả dụng cho ngày này</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error fetching time slots:', error);
                    timeSlotsContainer.innerHTML = `
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded">
                            <p class="text-sm">Đã xảy ra lỗi khi tải khung giờ</p>
                        </div>
                    `;
                });
        }

        // Form validation
        document.getElementById('appointment-form').addEventListener('submit', function(e) {
            const customer = document.getElementById('customer_id').value;
            const service = document.getElementById('service_id').value;
            const date = document.getElementById('date_appointments').value;
            const timeSlot = document.querySelector('input[name="time_appointments_id"]:checked');

            if (!customer || !service || !date || !timeSlot) {
                e.preventDefault();
                alert('Vui lòng điền đầy đủ thông tin bắt buộc: Khách hàng, Dịch vụ, Ngày hẹn và Giờ hẹn');
            }
        });
    });
</script>
@endsection
