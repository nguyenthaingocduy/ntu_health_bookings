@extends('layouts.staff_new')

@section('title', 'Tạo lịch hẹn mới - Cán bộ viên chức')
@section('page-title', 'Tạo lịch hẹn mới')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
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
   .w-3 {
    width: 70rem !important;
   }
   .h-3 {
    height: 70rem !important;
   }

</style>
@endsection

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Tạo lịch hẹn mới</h3>
            <p class="text-base text-gray-500">Đặt lịch hẹn cho khách hàng</p>
        </div>
        <a href="{{ route('staff.appointments.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-150 flex items-center shadow-sm border border-gray-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Quay lại
        </a>
    </div>

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid gap-6 mb-8 md:grid-cols-3">
        <!-- Appointment Form -->
        <div class="col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-pink-50 to-pink-100 border-b border-gray-200 px-5 py-4">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2.5 text-pink-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        Thông tin lịch hẹn
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('staff.appointments.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">Khách hàng <span class="text-red-500">*</span></label>
                            <select name="customer_id" id="customer_id" class="customer-select w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 @error('customer_id') border-red-500 @enderror" required>
                                <option value="">-- Chọn khách hàng --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Dịch vụ <span class="text-red-500">*</span></label>
                            <select name="service_id" id="service_id" class="service-select w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 @error('service_id') border-red-500 @enderror" required>
                                <option value="">-- Chọn dịch vụ --</option>
                                @foreach($services as $serviceItem)
                                    <option value="{{ $serviceItem->id }}" {{ (old('service_id') == $serviceItem->id || (isset($service) && $service->id == $serviceItem->id)) ? 'selected' : '' }}>
                                        {{ $serviceItem->name }} - {{ number_format($serviceItem->price, 0, ',', '.') }} VNĐ
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian đặt lịch <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Specific Day Selection -->
                                <div class="border rounded-lg p-3 bg-white shadow-sm">
                                    <label class="inline-flex items-center mb-2">
                                        <input type="checkbox" name="booking_types[]" value="day" class="form-checkbox h-4 w-4 text-pink-600 border-gray-300 focus:ring-pink-500" checked>
                                        <span class="ml-2 font-medium text-gray-700">Ngày cụ thể</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="day_date" id="day_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50" placeholder="Chọn ngày" value="{{ old('day_date') }}">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Month Selection -->
                                <div class="border rounded-lg p-3 bg-white shadow-sm">
                                    <label class="inline-flex items-center mb-2">
                                        <input type="checkbox" name="booking_types[]" value="month" class="form-checkbox h-4 w-4 text-pink-600 border-gray-300 focus:ring-pink-500">
                                        <span class="ml-2 font-medium text-gray-700">Tháng</span>
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

                                <!-- Year Selection -->
                                <div class="border rounded-lg p-3 bg-white shadow-sm">
                                    <label class="inline-flex items-center mb-2">
                                        <input type="checkbox" name="booking_types[]" value="year" class="form-checkbox h-4 w-4 text-pink-600 border-gray-300 focus:ring-pink-500">
                                        <span class="ml-2 font-medium text-gray-700">Năm</span>
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
                            @error('booking_types')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">Bạn có thể chọn đồng thời nhiều loại thời gian để đặt lịch. Hệ thống sẽ tạo lịch hẹn cho tất cả các ngày được chọn (trừ cuối tuần).</p>
                        </div>

                        <div class="mb-6">
                            <label for="time_appointments_id" class="block text-sm font-medium text-gray-700 mb-1">Giờ hẹn <span class="text-red-500">*</span></label>
                            <select name="time_appointments_id" id="time_appointments_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 @error('time_appointments_id') border-red-500 @enderror" required>
                                <option value="">-- Chọn giờ hẹn --</option>
                                @foreach($times as $time)
                                    <option value="{{ $time->id }}" {{ old('time_appointments_id') == $time->id ? 'selected' : '' }}>
                                        {{ $time->started_time }}
                                    </option>
                                @endforeach
                            </select>
                            @error('time_appointments_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Các khung giờ đã đầy sẽ không hiển thị.</p>
                        </div>

                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái <span class="text-red-500">*</span></label>
                            <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 @error('status') border-red-500 @enderror" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                            <textarea name="notes" id="notes" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 @error('notes') border-red-500 @enderror" placeholder="Nhập ghi chú hoặc yêu cầu đặc biệt (nếu có)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="submit" class="px-8 py-4 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-medium text-lg rounded-xl hover:shadow-lg transition-all shadow-md transform hover:-translate-y-1">
                                <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Xác nhận đặt lịch
                            </button>
                        </div>
                        <div class="text-center mt-3 text-gray-500 text-sm">
                            <p>Nhấn nút xác nhận để hoàn tất việc đặt lịch hẹn</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Information Sidebar -->
        <div class="col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 shadow-md overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-b border-gray-200 px-5 py-4">
                    <h4 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2.5 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Hướng dẫn
                    </h4>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <h5 class="flex items-center text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-3 h-3 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            Quy trình đặt lịch
                        </h5>
                        <ol class="list-decimal list-inside text-sm text-gray-600 ml-2">
                            <li class="mb-1">Chọn khách hàng</li>
                            <li class="mb-1">Chọn dịch vụ khám sức khỏe</li>
                            <li class="mb-1">Chọn ngày hẹn</li>
                            <li class="mb-1">Chọn giờ hẹn</li>
                            <li class="mb-1">Chọn trạng thái</li>
                            <li class="mb-1">Nhập ghi chú (nếu có)</li>
                            <li>Xác nhận đặt lịch</li>
                        </ol>
                    </div>

                    <div>
                        <h5 class="flex items-center text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-3 h-3 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Lưu ý
                        </h5>
                        <ul class="list-disc list-inside text-sm text-gray-600 ml-2">
                            <li class="mb-1">Chỉ có thể đặt lịch cho khách hàng đã đăng ký tài khoản</li>
                            <li class="mb-1">Nếu chọn trạng thái "Đã xác nhận", khách hàng sẽ nhận được thông báo ngay lập tức</li>
                            <li class="mb-1">Kiểm tra kỹ thông tin trước khi xác nhận đặt lịch</li>
                            <li>Có thể chỉnh sửa hoặc hủy lịch hẹn sau khi đã tạo</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-green-100 border-b border-gray-200 px-5 py-4">
                    <h4 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2.5 text-green-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                        </svg>
                        Thông tin liên hệ
                    </h4>
                </div>
                <div class="p-6">
                    <div class="space-y-3 text-sm text-gray-600">
                        <p class="flex items-center">
                            <svg class="w-3 h-3 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                            </svg>
                            <strong>Trạm Y tế - Trường Đại học Nha Trang</strong>
                        </p>
                        <p class="flex items-center">
                            <svg class="w-3 h-3 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            02 Nguyễn Đình Chiểu, Nha Trang, Khánh Hòa
                        </p>
                        <p class="flex items-center">
                            <svg class="w-3 h-3 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            (0258) 2471303
                        </p>
                        <p class="flex items-center">
                            <svg class="w-3 h-3 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                            ntuhealthbooking@gmail.com
                        </p>
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
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

        // Initialize day date picker
        const dayDatePicker = flatpickr("#day_date", {
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
            ],
            onChange: function(selectedDates) {
                if (selectedDates.length > 0) {
                    updateAvailableTimeSlots();
                }
            }
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
                    updateAvailableTimeSlots();
                }
            }
        });

        // Handle booking type checkboxes
        document.querySelectorAll('input[name="booking_types[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const type = this.value;
                const isChecked = this.checked;

                // Enable/disable the corresponding date input
                if (type === 'day') {
                    document.getElementById('day_date').disabled = !isChecked;
                    if (isChecked && !document.getElementById('day_date').value) {
                        dayDatePicker.setDate(new Date());
                    }
                }
                else if (type === 'month') {
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

                // Ensure at least one option is selected
                const checkedOptions = document.querySelectorAll('input[name="booking_types[]"]:checked');
                if (checkedOptions.length === 0) {
                    // If no options are selected, re-check this one
                    this.checked = true;
                    document.getElementById(`${type}_date`).disabled = false;
                }

                // Update time slots
                updateAvailableTimeSlots();
            });
        });

        // Service and time select elements
        const serviceSelect = document.getElementById('service_id');
        const timeSelect = document.getElementById('time_appointments_id');

        function updateAvailableTimeSlots() {
            const serviceId = serviceSelect.value;
            if (!serviceId) return;

            // Get selected booking types
            const selectedTypes = [];
            document.querySelectorAll('input[name="booking_types[]"]:checked').forEach(checkbox => {
                const type = checkbox.value;
                const dateInput = document.getElementById(`${type}_date`);
                if (dateInput && dateInput.value) {
                    selectedTypes.push({
                        type: type,
                        date: dateInput.value
                    });
                }
            });

            if (selectedTypes.length === 0) return;

            // Disable time select while loading
            timeSelect.disabled = true;

            // Remove any existing booking notes
            const timeSelectContainer = timeSelect.parentElement;
            const noteElements = timeSelectContainer.querySelectorAll('.booking-note');
            noteElements.forEach(el => el.remove());

            // Always show all available time slots when multiple booking types are selected
            timeSelect.innerHTML = '<option value="">-- Chọn giờ hẹn --</option>';

            // Fetch all time slots
            fetch(`/api/time-slots`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        // Add all time slots
                        data.forEach(slot => {
                            const option = document.createElement('option');
                            option.value = slot.id;
                            option.textContent = slot.started_time;
                            timeSelect.appendChild(option);
                        });

                        // Add notes for each selected booking type
                        selectedTypes.forEach(selected => {
                            let noteElement = document.createElement('p');
                            noteElement.className = 'booking-note mt-2 text-sm text-pink-600 font-medium';

                            if (selected.type === 'day') {
                                noteElement.innerHTML = `Giờ hẹn sẽ được đặt cho ngày ${selected.date}.`;
                            } else if (selected.type === 'month') {
                                noteElement.innerHTML = `Giờ hẹn sẽ được đặt cho tất cả các ngày trong tháng ${selected.date}.`;
                            } else if (selected.type === 'year') {
                                noteElement.innerHTML = `Giờ hẹn sẽ được đặt cho tất cả các ngày trong năm ${selected.date}.`;
                            }

                            timeSelectContainer.appendChild(noteElement);
                        });

                        // Add a summary note if multiple types are selected
                        if (selectedTypes.length > 1) {
                            let summaryNote = document.createElement('p');
                            summaryNote.className = 'booking-note mt-3 text-sm text-blue-600 font-bold';
                            summaryNote.innerHTML = `Hệ thống sẽ tạo lịch hẹn cho tất cả các ngày được chọn (trừ cuối tuần).`;
                            timeSelectContainer.appendChild(summaryNote);
                        }
                    } else {
                        // No time slots available
                        const option = document.createElement('option');
                        option.disabled = true;
                        option.textContent = 'Không có khung giờ nào';
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

        // Add event listener for service change
        serviceSelect.addEventListener('change', updateAvailableTimeSlots);

        // Initial update with a slight delay to ensure all date pickers are initialized
        setTimeout(updateAvailableTimeSlots, 500);
    });
</script>
@endsection
