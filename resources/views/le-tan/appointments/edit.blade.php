@extends('layouts.le-tan')

@section('title', 'Chỉnh sửa lịch hẹn')

@section('header', 'Chỉnh sửa lịch hẹn')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chỉnh sửa lịch hẹn</h1>
            <p class="text-sm text-gray-500 mt-1">Cập nhật thông tin lịch hẹn</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('le-tan.appointments.show', $appointment->id) }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
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

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <form action="{{ route('le-tan.appointments.update', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Khách hàng <span class="text-red-500">*</span></label>
                        <select id="customer_id" name="customer_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm rounded-md @error('customer_id') border-red-500 @enderror" required>
                            <option value="">-- Chọn khách hàng --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ (old('customer_id', $appointment->customer_id) == $customer->id) ? 'selected' : '' }}>
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
                        <select id="service_id" name="service_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm rounded-md @error('service_id') border-red-500 @enderror" required>
                            <option value="">-- Chọn dịch vụ --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ (old('service_id', $appointment->service_id) == $service->id) ? 'selected' : '' }}
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
                        <input type="date" id="appointment_date" name="appointment_date" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm rounded-md @error('appointment_date') border-red-500 @enderror" value="{{ old('appointment_date', $appointment->date_appointments->format('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                        @error('appointment_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="time_slot_id" class="block text-sm font-medium text-gray-700 mb-2">Giờ hẹn <span class="text-red-500">*</span></label>
                        <select id="time_slot_id" name="time_slot_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm rounded-md @error('time_slot_id') border-red-500 @enderror" required>
                            <option value="">-- Chọn giờ hẹn --</option>
                            @foreach($timeSlots as $timeSlot)
                                <option value="{{ $timeSlot->id }}" {{ (old('time_slot_id', $appointment->time_slot_id) == $timeSlot->id) ? 'selected' : '' }}>
                                    {{ $timeSlot->start_time->format('H:i') }} - {{ $timeSlot->end_time->format('H:i') }}
                                </option>
                            @endforeach
                        </select>
                        @error('time_slot_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái <span class="text-red-500">*</span></label>
                    <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm rounded-md @error('status') border-red-500 @enderror" required>
                        <option value="pending" {{ (old('status', $appointment->status) == 'pending') ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="confirmed" {{ (old('status', $appointment->status) == 'confirmed') ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="completed" {{ (old('status', $appointment->status) == 'completed') ? 'selected' : '' }}>Đã hoàn thành</option>
                        <option value="cancelled" {{ (old('status', $appointment->status) == 'cancelled') ? 'selected' : '' }}>Đã hủy</option>
                        <option value="no-show" {{ (old('status', $appointment->status) == 'no-show') ? 'selected' : '' }}>Không đến</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                    <textarea id="notes" name="notes" rows="3" class="shadow-sm focus:ring-pink-500 focus:border-pink-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md @error('notes') border-red-500 @enderror" placeholder="Nhập ghi chú nếu có">{{ old('notes', $appointment->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin dịch vụ</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Giá dịch vụ:</p>
                            <p class="text-sm text-gray-900 font-semibold" id="service-price">
                                {{ number_format($appointment->service->price ?? 0, 0, ',', '.') }} VNĐ
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Thời gian dự kiến:</p>
                            <p class="text-sm text-gray-900" id="service-duration">
                                {{ $appointment->service->duration ?? 0 }} phút
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
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

    // Khi chọn ngày, kiểm tra và lọc các khung giờ phù hợp
    document.getElementById('appointment_date').addEventListener('change', function() {
        const selectedDate = this.value;
        const dayOfWeek = new Date(selectedDate).getDay(); // 0 = Chủ nhật, 1 = Thứ 2, ...

        // TODO: Gọi API để lấy các khung giờ còn trống cho ngày đã chọn
        // Hoặc lọc các khung giờ dựa trên ngày trong tuần
    });
</script>
@endsection
@endsection
