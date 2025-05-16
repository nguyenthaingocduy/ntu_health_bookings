@extends('layouts.le-tan')

@section('title', 'Chỉnh sửa nhắc lịch hẹn')

@section('header', 'Chỉnh sửa nhắc lịch hẹn')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chỉnh sửa nhắc lịch hẹn</h1>
            <p class="text-sm text-gray-500 mt-1">Cập nhật thông tin nhắc nhở lịch hẹn</p>
        </div>
        <div>
            <a href="{{ route('le-tan.reminders.show', $reminder->id) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <form action="{{ route('le-tan.reminders.update', $reminder->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="appointment_id" class="block text-sm font-medium text-gray-700 mb-1">Lịch hẹn <span class="text-red-500">*</span></label>
                    <select id="appointment_id" name="appointment_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Chọn lịch hẹn</option>
                        @foreach($appointments as $appointment)
                        <option value="{{ $appointment->id }}" {{ (old('appointment_id', $reminder->appointment_id) == $appointment->id) ? 'selected' : '' }}>
                            {{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }} -
                            {{ $appointment->service->name }} -
                            {{ $appointment->date_appointments->format('d/m/Y') }}
                            {{ $appointment->timeSlot ? $appointment->timeSlot->start_time : ($appointment->timeAppointment ? $appointment->timeAppointment->started_time : '') }}
                        </option>
                        @endforeach
                    </select>
                    @error('appointment_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="reminder_type" class="block text-sm font-medium text-gray-700 mb-1">Loại nhắc nhở <span class="text-red-500">*</span></label>
                    <select id="reminder_type" name="reminder_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        <option value="email" {{ (old('reminder_type', $reminder->reminder_type) == 'email') ? 'selected' : '' }}>Email</option>
                        <option value="sms" {{ (old('reminder_type', $reminder->reminder_type) == 'sms') ? 'selected' : '' }}>SMS</option>
                        <option value="both" {{ (old('reminder_type', $reminder->reminder_type) == 'both') ? 'selected' : '' }}>Email & SMS</option>
                    </select>
                    @error('reminder_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="reminder_date" class="block text-sm font-medium text-gray-700 mb-1">Ngày giờ nhắc <span class="text-red-500">*</span></label>
                    <input type="datetime-local" id="reminder_date" name="reminder_date" value="{{ old('reminder_date', $reminder->reminder_date->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    @error('reminder_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Nội dung nhắc nhở <span class="text-red-500">*</span></label>
                    <textarea id="message" name="message" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>{{ old('message', $reminder->message) }}</textarea>
                    @error('message')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    {{-- <p class="text-sm text-gray-500 mt-2">
                        <span class="font-medium">Gợi ý:</span> Bạn có thể sử dụng các biến sau trong nội dung:
                        <br>
                        [tên khách hàng] - Tên khách hàng
                        <br>
                        [tên dịch vụ] - Tên dịch vụ
                        <br>
                        [ngày] - Ngày hẹn
                        <br>
                        [giờ] - Giờ hẹn
                    </p> --}}
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded-lg hover:bg-pink-600 transition-colors duration-150 shadow-md">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Cập nhật nhắc nhở
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const appointmentSelect = document.getElementById('appointment_id');
        const messageTextarea = document.getElementById('message');

        appointmentSelect.addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                const appointmentText = selectedOption.text;

                // Parse appointment text to get customer name, service name, date and time
                const parts = appointmentText.split(' - ');
                if (parts.length >= 3) {
                    const customerName = parts[0].trim();
                    const serviceName = parts[1].trim();
                    const dateTimeParts = parts[2].trim().split(' ');
                    const date = dateTimeParts[0];
                    const time = dateTimeParts.length > 1 ? dateTimeParts[1] : '';

                    // Replace placeholders in message
                    let message = messageTextarea.value;
                    message = message.replace(/\[tên khách hàng\]/g, customerName);
                    message = message.replace(/\[tên dịch vụ\]/g, serviceName);
                    message = message.replace(/\[ngày\]/g, date);
                    message = message.replace(/\[giờ\]/g, time);

                    messageTextarea.value = message;
                }
            }
        });
    });
</script>
@endsection
@endsection
