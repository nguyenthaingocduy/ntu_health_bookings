@extends('layouts.staff')

@section('title', 'Đặt lịch khám sức khỏe')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Đặt lịch khám sức khỏe</h1>
    <a href="{{ route('staff.appointments.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thông tin đặt lịch</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('staff.appointments.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="service_id" class="form-label">Dịch vụ khám <span class="text-danger">*</span></label>
                        <select name="service_id" id="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                            <option value="">-- Chọn dịch vụ --</option>
                            @foreach($services as $serviceItem)
                                <option value="{{ $serviceItem->id }}" {{ (old('service_id') == $serviceItem->id || (isset($service) && $service->id == $serviceItem->id)) ? 'selected' : '' }}>
                                    {{ $serviceItem->name }} - {{ number_format($serviceItem->price, 0, ',', '.') }} VNĐ
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="date_appointments" class="form-label">Ngày hẹn <span class="text-danger">*</span></label>
                        <input type="text" name="date_appointments" id="date_appointments" class="form-control datepicker @error('date_appointments') is-invalid @enderror" placeholder="Chọn ngày" value="{{ old('date_appointments') }}" required>
                        @error('date_appointments')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Chọn ngày bạn muốn đặt lịch khám.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="time_appointments_id" class="form-label">Giờ hẹn <span class="text-danger">*</span></label>
                        <select name="time_appointments_id" id="time_appointments_id" class="form-select @error('time_appointments_id') is-invalid @enderror" required>
                            <option value="">-- Chọn giờ hẹn --</option>
                            @foreach($times as $time)
                                <option value="{{ $time->id }}" {{ old('time_appointments_id') == $time->id ? 'selected' : '' }}>
                                    {{ $time->started_time }}
                                </option>
                            @endforeach
                        </select>
                        @error('time_appointments_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Các khung giờ đã đầy sẽ không hiển thị.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Ghi chú</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Nhập ghi chú hoặc yêu cầu đặc biệt (nếu có)">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-calendar-check me-1"></i> Xác nhận đặt lịch
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Hướng dẫn</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6><i class="fas fa-info-circle text-primary me-2"></i>Quy trình đặt lịch</h6>
                    <ol class="ps-3">
                        <li>Chọn dịch vụ khám sức khỏe</li>
                        <li>Chọn ngày hẹn</li>
                        <li>Chọn giờ hẹn</li>
                        <li>Nhập ghi chú (nếu có)</li>
                        <li>Xác nhận đặt lịch</li>
                    </ol>
                </div>
                
                <div class="mb-3">
                    <h6><i class="fas fa-exclamation-triangle text-warning me-2"></i>Lưu ý</h6>
                    <ul class="ps-3">
                        <li>Vui lòng đến trước giờ hẹn 15 phút</li>
                        <li>Mang theo giấy tờ tùy thân</li>
                        <li>Có thể hủy lịch hẹn trước 24 giờ</li>
                        <li>Nếu cần hỗ trợ, vui lòng liên hệ số điện thoại: <strong>(0258) 2471303</strong></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thông tin liên hệ</h5>
            </div>
            <div class="card-body">
                <p><i class="fas fa-hospital me-2 text-primary"></i><strong>Trạm Y tế - Trường Đại học Nha Trang</strong></p>
                <p><i class="fas fa-map-marker-alt me-2 text-danger"></i>02 Nguyễn Đình Chiểu, Nha Trang, Khánh Hòa</p>
                <p><i class="fas fa-phone me-2 text-success"></i>(0258) 2471303</p>
                <p><i class="fas fa-envelope me-2 text-warning"></i>tramyte@ntu.edu.vn</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo date picker
        const datePicker = flatpickr("#date_appointments", {
            dateFormat: "Y-m-d",
            minDate: "today",
            locale: "vn",
            disableMobile: "true"
        });
        
        // Xử lý khi thay đổi ngày và dịch vụ
        const dateInput = document.getElementById('date_appointments');
        const serviceSelect = document.getElementById('service_id');
        const timeSelect = document.getElementById('time_appointments_id');
        
        function updateAvailableTimeSlots() {
            const date = dateInput.value;
            const serviceId = serviceSelect.value;
            
            if (!date || !serviceId) return;
            
            // Disable time select while loading
            timeSelect.disabled = true;
            
            // Fetch available time slots
            fetch(`/api/check-available-slots?date=${date}&service_id=${serviceId}`)
                .then(response => response.json())
                .then(data => {
                    // Clear current options
                    timeSelect.innerHTML = '<option value="">-- Chọn giờ hẹn --</option>';
                    
                    if (data.success && data.available_slots.length > 0) {
                        // Add available time slots
                        data.available_slots.forEach(slot => {
                            const option = document.createElement('option');
                            option.value = slot.id;
                            option.textContent = slot.started_time;
                            timeSelect.appendChild(option);
                        });
                    } else {
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
        
        // Initial update if values are pre-selected
        if (dateInput.value && serviceSelect.value) {
            updateAvailableTimeSlots();
        }
    });
</script>
@endsection
