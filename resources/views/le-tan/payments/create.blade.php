@extends('layouts.le-tan')

@section('title', 'Tạo thanh toán mới')

@section('header', 'Tạo thanh toán mới')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tạo thanh toán mới</h1>
            <p class="text-sm text-gray-500 mt-1">Tạo thanh toán cho lịch hẹn đã hoàn thành</p>
        </div>
        <a href="{{ route('le-tan.payments.index') }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
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
            <form action="{{ route('le-tan.payments.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="appointment_id" class="block text-sm font-medium text-gray-700 mb-2">Chọn lịch hẹn <span class="text-red-500">*</span></label>
                    <select id="appointment_id" name="appointment_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-gray-500 focus:ring-gray-500 sm:text-sm rounded-md @error('appointment_id') border-red-500 @enderror" required>
                        <option value="">-- Chọn lịch hẹn --</option>
                        @foreach($appointments as $appointment)
                            <option value="{{ $appointment->id }}" {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                {{ $appointment->appointment_code ?? 'APT-'.substr($appointment->id, 0, 8) }} -
                                {{ $appointment->customer->full_name ?? 'Khách hàng' }} -
                                {{ $appointment->service->name ?? 'Dịch vụ' }} -
                                {{ $appointment->appointment_date ? $appointment->appointment_date->format('d/m/Y H:i') : ($appointment->date_appointments ? date('d/m/Y H:i', strtotime($appointment->date_appointments)) : 'Chưa có ngày hẹn') }}
                                @if($appointment->status == 'confirmed')
                                    (Đã xác nhận)
                                @elseif($appointment->status == 'completed')
                                    (Đã hoàn thành)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('appointment_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Số tiền <span class="text-red-500">*</span></label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" name="amount" id="amount" class=" w-full px-4 py-2 border border-gray-300 block pl-3 pr-12 rounded-lg focus:ring-gray-500 focus:ring-gray-500 @error('amount') border-red-500 @enderror" placeholder="0" value="{{ old('amount') }}" required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">
                                VNĐ
                            </span>
                        </div>
                    </div>
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <div id="price-info" class="hidden"></div>
                </div>

                <div class="mb-6">
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Phương thức thanh toán <span class="text-red-500">*</span></label>
                    <select id="payment_method" name="payment_method" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-gray-500 focus:ring-gray-500 sm:text-sm rounded-md @error('payment_method') border-red-500 @enderror" required>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tiền mặt</option>
                        <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Thẻ tín dụng</option>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản</option>
                    </select>
                    @error('payment_method')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái thanh toán <span class="text-red-500">*</span></label>
                    <select id="payment_status" name="payment_status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-gray-500 focus:ring-gray-500 sm:text-sm rounded-md @error('payment_status') border-red-500 @enderror" required>
                        <option value="completed" {{ old('payment_status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="failed" {{ old('payment_status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                    </select>
                    @error('payment_status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-gray-500 focus:ring-gray-500 @error('notes') border-red-500 @enderror" placeholder="Nhập ghi chú nếu có">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Tạo thanh toán
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Khi chọn lịch hẹn, tự động điền số tiền
    document.getElementById('appointment_id').addEventListener('change', function() {
        const appointmentId = this.value;
        if (!appointmentId) return;

        // Tìm thông tin lịch hẹn đã chọn
        const appointments = @json($appointments);
        const selectedAppointment = appointments.find(appointment => appointment.id === appointmentId);

        if (selectedAppointment) {
            // Sử dụng giá đã giảm (final_price) nếu có, nếu không thì sử dụng giá gốc
            if (selectedAppointment.final_price && selectedAppointment.final_price > 0) {
                document.getElementById('amount').value = selectedAppointment.final_price;
            } else if (selectedAppointment.service) {
                document.getElementById('amount').value = selectedAppointment.service.price;
            }

            // Hiển thị thông tin giảm giá nếu có
            const priceInfoElement = document.getElementById('price-info');
            if (priceInfoElement) {
                if (selectedAppointment.final_price && selectedAppointment.service &&
                    selectedAppointment.final_price < selectedAppointment.service.price) {
                    const discountPercent = Math.round((selectedAppointment.service.price - selectedAppointment.final_price) / selectedAppointment.service.price * 100);

                    priceInfoElement.innerHTML = `
                        <div class="mt-2 text-sm">
                            <span class="line-through text-gray-500">${new Intl.NumberFormat('vi-VN').format(selectedAppointment.service.price)} VNĐ</span>
                            <span class="text-red-600 ml-2">${new Intl.NumberFormat('vi-VN').format(selectedAppointment.final_price)} VNĐ</span>
                            <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Giảm ${discountPercent}%</span>
                        </div>
                    `;
                    priceInfoElement.classList.remove('hidden');
                } else {
                    priceInfoElement.innerHTML = '';
                    priceInfoElement.classList.add('hidden');
                }
            }
        }
    });
</script>
@endsection
@endsection
