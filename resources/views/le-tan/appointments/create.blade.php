@extends('layouts.le-tan')

@section('title', 'Tạo lịch hẹn mới')

@section('header', 'Tạo lịch hẹn mới')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tạo lịch hẹn mới</h1>
            <p class="text-sm text-gray-500 mt-1">Đặt lịch hẹn cho khách hàng</p>
        </div>
        <a href="{{ route('le-tan.appointments.index') }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
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

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <form action="{{ route('le-tan.appointments.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Khách hàng <span class="text-red-500">*</span></label>
                        <select id="customer_id" name="customer_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('customer_id') border-red-500 @enderror" required>
                            <option value="">-- Chọn khách hàng --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->full_name }} - {{ $customer->email }} - {{ $customer->phone }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">Dịch vụ <span class="text-red-500">*</span></label>
                        <select id="service_id" name="service_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('service_id') border-red-500 @enderror" required>
                            <option value="">-- Chọn dịch vụ --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}
                                    data-price="{{ $service->price }}"
                                    data-duration="{{ $service->duration }}">
                                    {{ $service->name }} - {{ number_format($service->price, 0, ',', '.') }} VNĐ
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">Ngày hẹn <span class="text-red-500">*</span></label>
                        <input type="date" id="appointment_date" name="appointment_date" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('appointment_date') border-red-500 @enderror" value="{{ old('appointment_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                        @error('appointment_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="time_appointments_id" class="block text-sm font-medium text-gray-700 mb-2">Giờ hẹn <span class="text-red-500">*</span></label>
                        <select id="time_appointments_id" name="time_appointments_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('time_appointments_id') border-red-500 @enderror" required>
                            <option value="">-- Chọn giờ hẹn --</option>
                            @foreach($timeSlots as $timeSlot)
                                <option value="{{ $timeSlot->id }}" {{ old('time_appointments_id') == $timeSlot->id ? 'selected' : '' }}>
                                    @if($timeSlot->started_time)
                                        {{ \Carbon\Carbon::parse($timeSlot->started_time)->format('H:i') }}
                                        @if($timeSlot->ended_time)
                                            - {{ \Carbon\Carbon::parse($timeSlot->ended_time)->format('H:i') }}
                                        @endif
                                        ({{ $timeSlot->capacity }} chỗ)
                                    @else
                                        {{ $timeSlot->started_time }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('time_appointments_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">Nhân viên kỹ thuật</label>
                        <select id="employee_id" name="employee_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('employee_id') border-red-500 @enderror">
                            <option value="">-- Chọn nhân viên kỹ thuật --</option>
                            @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}" {{ old('employee_id') == $technician->id ? 'selected' : '' }}>
                                    {{ $technician->full_name }} - {{ $technician->phone }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Có thể để trống và phân công sau</p>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror" placeholder="Nhập ghi chú nếu có">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin dịch vụ</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Giá dịch vụ:</p>
                            <p class="text-sm text-gray-900 font-semibold" id="service-price">Chưa chọn dịch vụ</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Thời gian dự kiến:</p>
                            <p class="text-sm text-gray-900" id="service-duration">Chưa chọn dịch vụ</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Tạo lịch hẹn
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Khi chọn dịch vụ, hiển thị thông tin
    document.getElementById('service_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (this.value) {
            // Lấy thông tin từ data attributes
            const price = selectedOption.getAttribute('data-price');
            const duration = selectedOption.getAttribute('data-duration');

            // Hiển thị thông tin
            document.getElementById('service-price').textContent = new Intl.NumberFormat('vi-VN').format(price) + ' VNĐ';
            document.getElementById('service-duration').textContent = duration + ' phút';
        } else {
            // Reset thông tin nếu không chọn dịch vụ
            document.getElementById('service-price').textContent = 'Chưa chọn dịch vụ';
            document.getElementById('service-duration').textContent = 'Chưa chọn dịch vụ';
        }
    });

    // Hàm kiểm tra tính khả dụng của nhân viên kỹ thuật
    function checkTechnicianAvailability() {
        const selectedDate = document.getElementById('appointment_date').value;
        const selectedTimeSlot = document.getElementById('time_appointments_id').value;
        const selectedTechnician = document.getElementById('employee_id').value;

        if (selectedDate && selectedTimeSlot && selectedTechnician) {
            // Gọi API để kiểm tra tính khả dụng của nhân viên
            fetch(`/api/check-technician-availability?date=${selectedDate}&time_appointments_id=${selectedTimeSlot}&technician_id=${selectedTechnician}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.available) {
                        alert('Nhân viên kỹ thuật đã có lịch hẹn khác trong cùng khung giờ này. Vui lòng chọn nhân viên khác.');
                        document.getElementById('employee_id').value = '';
                    }
                })
                .catch(error => {
                    console.error('Error checking technician availability:', error);
                });
        }
    }

    // Khi chọn ngày, kiểm tra tính khả dụng của nhân viên kỹ thuật
    document.getElementById('appointment_date').addEventListener('change', function() {
        // Kiểm tra lại tính khả dụng của nhân viên kỹ thuật nếu đã chọn
        if (document.getElementById('employee_id').value) {
            checkTechnicianAvailability();
        }
    });

    // Khi chọn khung giờ, kiểm tra tính khả dụng của nhân viên kỹ thuật
    document.getElementById('time_appointments_id').addEventListener('change', function() {
        if (document.getElementById('employee_id').value) {
            checkTechnicianAvailability();
        }
    });

    // Khi chọn nhân viên kỹ thuật, kiểm tra tính khả dụng
    document.getElementById('employee_id').addEventListener('change', function() {
        if (this.value) {
            checkTechnicianAvailability();
        }
    });
</script>
@endsection
@endsection
