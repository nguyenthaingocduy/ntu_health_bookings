@extends('layouts.staff_new')

@section('title', 'Đặt lịch hẹn mới - Cán bộ viên chức')
@section('page-title', 'Đặt lịch hẹn mới')

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
        padding-right: 1rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px;
    }
    .loading-spinner {
        display: inline-block;
        width: 1.5rem;
        height: 1.5rem;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #f472b6;
        animation: spin 1s ease-in-out infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .time-slot-div {
        transition: all 0.2s ease;
    }
    .time-slot-div:hover {
        border-color: #f472b6;
    }
    .time-slot-wrapper input[type="radio"]:checked + .time-slot-div {
        border-color: #f472b6;
        background-color: #fdf2f8;
    }
    .time-slot-wrapper {
        position: relative;
    }
    .time-slot-wrapper input[type="radio"]:checked + .time-slot-div::after {
        content: '';
        position: absolute;
        top: -5px;
        right: -5px;
        width: 20px;
        height: 20px;
        background-color: #f472b6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='white'%3E%3Cpath fill-rule='evenodd' d='M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z' clip-rule='evenodd' /%3E%3C/svg%3E");
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('staff.appointments.index') }}" class="text-pink-500 hover:underline flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Quay lại danh sách
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-pink-500 text-white py-4 px-6">
            <h2 class="text-xl font-semibold">Đặt lịch hẹn mới</h2>
        </div>

        <div class="p-6">
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

            <form action="{{ route('staff.appointments.store') }}" method="POST" id="appointment-form">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Khách hàng -->
                    <div>
                        <label for="customer_id" class="block text-gray-700 font-medium mb-2">Khách hàng <span class="text-red-500">*</span></label>
                        <select name="customer_id" id="customer_id" class="customer-select w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 @error('customer_id') border-red-500 @enderror" required>
                            <option value="">-- Chọn khách hàng --</option>
                        </select>
                        @error('customer_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dịch vụ -->
                    <div>
                        <label for="service_id" class="block text-gray-700 font-medium mb-2">Dịch vụ <span class="text-red-500">*</span></label>
                        <select name="service_id" id="service_id" class="service-select w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 @error('service_id') border-red-500 @enderror" required>
                            <option value="">-- Chọn dịch vụ --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id || (isset($serviceId) && $serviceId == $service->id) ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ngày hẹn -->
                    <div>
                        <label for="date_appointments" class="block text-gray-700 font-medium mb-2">Ngày hẹn <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="date_appointments" id="date_appointments" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 @error('date_appointments') border-red-500 @enderror" placeholder="Chọn ngày" value="{{ old('date_appointments') }}" required>
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

                    <!-- Trạng thái -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Trạng thái <span class="text-red-500">*</span></label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="pending" class="form-radio h-4 w-4 text-pink-600 border-gray-300 focus:ring-pink-500" {{ old('status', 'pending') == 'pending' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Chờ xác nhận</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="confirmed" class="form-radio h-4 w-4 text-pink-600 border-gray-300 focus:ring-pink-500" {{ old('status') == 'confirmed' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Đã xác nhận</span>
                            </label>
                        </div>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Khung giờ -->
                <div class="mt-6">
                    <label class="block text-gray-700 font-medium mb-2">Thời gian <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2" id="time-slots-container">
                        <div class="col-span-full text-gray-500 text-sm p-3 border rounded-lg bg-gray-50">
                            <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Vui lòng chọn dịch vụ và ngày để xem khung giờ.
                        </div>
                    </div>
                    @error('time_appointments_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ghi chú -->
                <div class="mt-6">
                    <label for="notes" class="block text-gray-700 font-medium mb-2">Ghi chú</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tùy chọn đặt lịch nâng cao -->
                <div class="mt-6 border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tùy chọn đặt lịch nâng cao</h3>
                    
                    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm">Bạn có thể đặt lịch cho nhiều ngày cùng lúc bằng cách chọn các tùy chọn bên dưới.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Đặt lịch theo tháng -->
                        <div class="border rounded-lg p-3 bg-white shadow-sm">
                            <label class="inline-flex items-center mb-2">
                                <input type="checkbox" name="booking_types[]" value="month" class="form-checkbox h-4 w-4 text-pink-600 border-gray-300 focus:ring-pink-500">
                                <span class="ml-2 font-medium text-gray-700">Đặt lịch theo tháng</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="month_date" id="month_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50" placeholder="Chọn tháng" value="{{ old('month_date') }}" disabled>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Đặt lịch theo năm -->
                        <div class="border rounded-lg p-3 bg-white shadow-sm">
                            <label class="inline-flex items-center mb-2">
                                <input type="checkbox" name="booking_types[]" value="year" class="form-checkbox h-4 w-4 text-pink-600 border-gray-300 focus:ring-pink-500">
                                <span class="ml-2 font-medium text-gray-700">Đặt lịch theo năm</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="year_date" id="year_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50" placeholder="Chọn năm" value="{{ old('year_date') }}" disabled>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <p class="mt-2 text-xs text-gray-500">Hệ thống sẽ tạo lịch hẹn cho tất cả các ngày được chọn (trừ cuối tuần).</p>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded-lg hover:bg-pink-600 transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Đặt lịch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for customer selection
        $('.customer-select').select2({
            placeholder: "Chọn khách hàng",
            allowClear: true,
            ajax: {
                url: '/api/customers/search',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
            templateResult: formatCustomer,
            templateSelection: formatCustomerSelection
        });

        function formatCustomer(customer) {
            if (customer.loading) {
                return customer.text;
            }
            
            if (!customer.id) {
                return customer.text;
            }
            
            return $(`
                <div class="flex items-center">
                    <div class="ml-2">
                        <div class="font-medium">${customer.name}</div>
                        <div class="text-xs text-gray-500">${customer.email || ''} ${customer.phone ? ' • ' + customer.phone : ''}</div>
                    </div>
                </div>
            `);
        }

        function formatCustomerSelection(customer) {
            return customer.name || customer.text;
        }

        // Initialize Select2 for service selection
        $('.service-select').select2({
            placeholder: "Chọn dịch vụ",
            allowClear: true
        });

        // Initialize date picker
        const datePicker = flatpickr("#date_appointments", {
            dateFormat: "Y-m-d",
            minDate: "today",
            locale: "vn",
            disableMobile: "true",
            onChange: function(selectedDates) {
                if (selectedDates.length > 0) {
                    updateAvailableTimeSlots();
                }
            }
        });

        // Initialize month date picker
        const monthDatePicker = flatpickr("#month_date", {
            dateFormat: "m/Y",
            minDate: "today",
            locale: "vn",
            disableMobile: "true",
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "m/Y",
                    altFormat: "F Y"
                })
            ]
        });

        // Initialize year date picker
        const yearDatePicker = flatpickr("#year_date", {
            dateFormat: "Y",
            minDate: "today",
            locale: "vn",
            disableMobile: "true",
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "Y",
                    altFormat: "Y"
                })
            ],
            onChange: function(selectedDates, dateStr, instance) {
                // Just show the year
                if (selectedDates.length > 0) {
                    instance.input.value = selectedDates[0].getFullYear();
                }
            }
        });

        // Handle booking type checkboxes
        document.querySelectorAll('input[name="booking_types[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const type = this.value;
                const isChecked = this.checked;
                
                // Enable/disable the corresponding date input
                if (type === 'month') {
                    document.getElementById('month_date').disabled = !isChecked;
                    if (isChecked && !document.getElementById('month_date').value) {
                        monthDatePicker.setDate(new Date());
                    }
                }
                else if (type === 'year') {
                    document.getElementById('year_date').disabled = !isChecked;
                    if (isChecked && !document.getElementById('year_date').value) {
                        yearDatePicker.setDate(new Date());
                    }
                }
            });
        });

        // Service and time select elements
        const serviceSelect = document.getElementById('service_id');
        const timeSlotContainer = document.getElementById('time-slots-container');

        // Function to update available time slots
        function updateAvailableTimeSlots() {
            const date = document.getElementById('date_appointments').value;
            const serviceId = serviceSelect.value;

            if (!date || !serviceId) return;

            // Show loading
            timeSlotContainer.innerHTML = `
                <div class="col-span-full flex items-center justify-center py-4">
                    <div class="loading-spinner mr-3"></div> 
                    <span class="text-gray-500">Đang kiểm tra khung giờ khả dụng...</span>
                </div>
            `;

            // Call API to get available time slots
            fetch(`/api/check-available-slots?date=${date}&service_id=${serviceId}`)
                .then(response => response.json())
                .then(data => {
                    // Clear container
                    timeSlotContainer.innerHTML = '';

                    if (data.success && data.available_slots.length > 0) {
                        // Add available time slots
                        data.available_slots.forEach(slot => {
                            const availableText = slot.available_slots > 0
                                ? `<span class="text-xs text-green-600 block mt-1">${slot.available_slots}/${slot.capacity} chỗ trống</span>`
                                : '<span class="text-xs text-red-600 block mt-1">Đã đầy</span>';

                            const timeSlotHTML = `
                                <div class="time-slot-wrapper">
                                    <input type="radio" name="time_appointments_id" id="time-input-${slot.id}" value="${slot.id}" class="hidden time-radio" required>
                                    <label for="time-input-${slot.id}" class="text-center px-4 py-2 border rounded-lg cursor-pointer hover:border-pink-500 transition time-slot-div block">
                                        ${slot.time}
                                        ${availableText}
                                    </label>
                                </div>
                            `;

                            timeSlotContainer.insertAdjacentHTML('beforeend', timeSlotHTML);
                        });
                    } else {
                        // No available slots
                        timeSlotContainer.innerHTML = `
                            <div class="col-span-full text-center py-4 border rounded-lg bg-gray-50">
                                <svg class="inline-block w-5 h-5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-gray-500">Không có khung giờ trống cho ngày này</span>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error fetching time slots:', error);
                    timeSlotContainer.innerHTML = `
                        <div class="col-span-full text-center py-4 border rounded-lg bg-red-50">
                            <svg class="inline-block w-5 h-5 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-red-500">Lỗi khi tải khung giờ</span>
                        </div>
                    `;
                });
        }

        // Add event listeners
        serviceSelect.addEventListener('change', updateAvailableTimeSlots);
        
        // Event delegation for time slot selection
        timeSlotContainer.addEventListener('change', function(e) {
            if (e.target.classList.contains('time-radio')) {
                // Remove all selected classes
                document.querySelectorAll('.time-slot-div').forEach(div => {
                    div.classList.remove('bg-pink-50', 'border-pink-500');
                });
                
                // Add selected class to the clicked time slot
                const label = document.querySelector(`label[for="${e.target.id}"]`);
                if (label) {
                    label.classList.add('bg-pink-50', 'border-pink-500');
                }
            }
        });

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

        // Initial update if values are pre-selected
        if (serviceSelect.value && document.getElementById('date_appointments').value) {
            updateAvailableTimeSlots();
        }
    });
</script>
@endsection
