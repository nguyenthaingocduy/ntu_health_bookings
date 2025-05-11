@extends('layouts.le-tan')

@section('title', 'Tạo hóa đơn mới')

@section('header', 'Tạo hóa đơn mới')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tạo hóa đơn mới</h1>
            <p class="text-sm text-gray-500 mt-1">Tạo hóa đơn cho lịch hẹn đã hoàn thành và thanh toán</p>
        </div>
        <a href="{{ route('le-tan.invoices.index') }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
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

    @if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <form action="{{ route('le-tan.invoices.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="appointment_id" class="block text-sm font-medium text-gray-700 mb-2">Chọn lịch hẹn <span class="text-red-500">*</span></label>
                    <select id="appointment_id" name="appointment_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('appointment_id') border-red-500 @enderror" required>
                        <option value="">-- Chọn lịch hẹn --</option>
                        @foreach($appointments as $appointment)
                            <option value="{{ $appointment->id }}" {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}
                                data-customer="{{ $appointment->customer->full_name }}"
                                data-service="{{ $appointment->service->name }}"
                                data-price="{{ $appointment->service->price }}"
                                data-date="{{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y H:i') }}"
                                data-payment="{{ $appointment->payment->amount }}">
                                {{ $appointment->id }} - {{ $appointment->customer->full_name }} - {{ $appointment->service->name }} - {{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y H:i') }}
                            </option>
                        @endforeach
                    </select>
                    @error('appointment_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="invoice_date" class="block text-sm font-medium text-gray-700 mb-2">Ngày hóa đơn <span class="text-red-500">*</span></label>
                    <input type="date" name="invoice_date" id="invoice_date" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('invoice_date') border-red-500 @enderror" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                    @error('invoice_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror" placeholder="Nhập ghi chú nếu có">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin hóa đơn</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Khách hàng:</p>
                            <p class="text-sm text-gray-900 font-semibold" id="customer-info">Chưa chọn lịch hẹn</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Dịch vụ:</p>
                            <p class="text-sm text-gray-900" id="service-info">Chưa chọn lịch hẹn</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Ngày hẹn:</p>
                            <p class="text-sm text-gray-900" id="date-info">Chưa chọn lịch hẹn</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Số tiền thanh toán:</p>
                            <p class="text-sm text-gray-900 font-semibold" id="payment-info">Chưa chọn lịch hẹn</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Tạo hóa đơn
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Khi chọn lịch hẹn, hiển thị thông tin
    document.getElementById('appointment_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (this.value) {
            // Lấy thông tin từ data attributes
            const customer = selectedOption.getAttribute('data-customer');
            const service = selectedOption.getAttribute('data-service');
            const date = selectedOption.getAttribute('data-date');
            const payment = selectedOption.getAttribute('data-payment');

            // Hiển thị thông tin
            document.getElementById('customer-info').textContent = customer;
            document.getElementById('service-info').textContent = service;
            document.getElementById('date-info').textContent = date;
            document.getElementById('payment-info').textContent = new Intl.NumberFormat('vi-VN').format(payment) + ' VNĐ';
        } else {
            // Reset thông tin nếu không chọn lịch hẹn
            document.getElementById('customer-info').textContent = 'Chưa chọn lịch hẹn';
            document.getElementById('service-info').textContent = 'Chưa chọn lịch hẹn';
            document.getElementById('date-info').textContent = 'Chưa chọn lịch hẹn';
            document.getElementById('payment-info').textContent = 'Chưa chọn lịch hẹn';
        }
    });
</script>
@endsection
@endsection
