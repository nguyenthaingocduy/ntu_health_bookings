@extends('layouts.staff')

@section('title', 'Đặt lịch khám sức khỏe')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .time-slot-wrapper {
        display: inline-block;
        margin: 5px;
    }
    
    .time-slot-div {
        min-width: 100px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .time-radio:checked + .time-slot-div {
        background-color: #4e73df;
        color: white;
        border-color: #4e73df;
    }
    
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 0.2em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border .75s linear infinite;
    }
    
    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Đặt lịch khám sức khỏe</h1>
        <a href="{{ route('staff.health-checkups.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin lịch khám</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.health-checkups.store') }}" method="POST" id="appointmentForm">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="service_id" class="form-label">Dịch vụ khám <span class="text-danger">*</span></label>
                        <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id" required>
                            <option value="">-- Chọn dịch vụ --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <div class="mt-2" id="serviceInfo" style="display: none;">
                            <div class="card border-left-info">
                                <div class="card-body py-2">
                                    <div id="serviceDescription"></div>
                                    <div id="serviceDuration" class="mt-1"></div>
                                    <div id="servicePrice" class="mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="appointment_date" class="form-label">Ngày khám <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('appointment_date') is-invalid @enderror" id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}" placeholder="Chọn ngày" required readonly>
                        @error('appointment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Chọn giờ khám <span class="text-danger">*</span></label>
                    <div id="timeSlotContainer" class="border rounded p-3 bg-light">
                        <div class="text-center text-muted py-4">
                            Vui lòng chọn ngày khám trước
                        </div>
                    </div>
                    @error('time_slot_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="notes" class="form-label">Ghi chú</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            Tôi đồng ý với các <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">điều khoản và điều kiện</a>
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Đặt lịch</button>
            </form>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Điều khoản và điều kiện</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Quy định đặt lịch khám sức khỏe</h6>
                <ol>
                    <li>Vui lòng đến đúng giờ đã đặt lịch.</li>
                    <li>Mang theo thẻ nhân viên và CMND/CCCD.</li>
                    <li>Nhịn ăn ít nhất 8 giờ trước khi xét nghiệm máu (nếu có).</li>
                    <li>Nếu không thể đến khám, vui lòng hủy lịch trước ít nhất 24 giờ.</li>
                    <li>Trường hợp không đến khám mà không thông báo trước, sẽ bị ghi nhận vào hồ sơ.</li>
                </ol>
                
                <h6 class="mt-3">Quy trình khám sức khỏe</h6>
                <ol>
                    <li>Đăng ký tại quầy tiếp nhận.</li>
                    <li>Thực hiện các xét nghiệm cơ bản (nếu có).</li>
                    <li>Khám lâm sàng với bác sĩ.</li>
                    <li>Nhận kết quả và tư vấn.</li>
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đã hiểu</button>
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
        const datePicker = flatpickr("#appointment_date", {
            locale: "vn",
            dateFormat: "Y-m-d",
            minDate: "today",
            disable: [
                function(date) {
                    // Disable weekends (0 = Sunday, 6 = Saturday)
                    return (date.getDay() === 0 || date.getDay() === 6);
                }
            ],
            enable: [
                @foreach($availableDates as $date)
                    "{{ $date }}",
                @endforeach
            ],
            onChange: function(selectedDates, dateStr, instance) {
                if (dateStr) {
                    fetchAvailableTimeSlots(dateStr);
                }
            }
        });
        
        // Hiển thị thông tin dịch vụ
        const serviceSelect = document.getElementById('service_id');
        const serviceInfo = document.getElementById('serviceInfo');
        const serviceDescription = document.getElementById('serviceDescription');
        const serviceDuration = document.getElementById('serviceDuration');
        const servicePrice = document.getElementById('servicePrice');
        
        serviceSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (this.value) {
                // Gọi API để lấy thông tin chi tiết dịch vụ
                fetch(`/api/services/${this.value}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            serviceDescription.innerHTML = `<strong>Mô tả:</strong> ${data.service.description}`;
                            serviceDuration.innerHTML = `<strong>Thời gian:</strong> ${data.service.duration} phút`;
                            servicePrice.innerHTML = `<strong>Giá:</strong> ${data.service.price == 0 ? 'Miễn phí' : new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(data.service.price)}`;
                            serviceInfo.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching service details:', error);
                    });
            } else {
                serviceInfo.style.display = 'none';
            }
        });
        
        // Nếu đã chọn dịch vụ từ trước (ví dụ: khi validation fails), hiển thị thông tin
        if (serviceSelect.value) {
            serviceSelect.dispatchEvent(new Event('change'));
        }
    });
    
    // Hàm lấy các khung giờ có sẵn
    function fetchAvailableTimeSlots(date) {
        const timeSlotContainer = document.getElementById('timeSlotContainer');
        const serviceId = document.getElementById('service_id').value;
        
        if (!serviceId) {
            timeSlotContainer.innerHTML = `
                <div class="text-center text-danger py-4">
                    Vui lòng chọn dịch vụ trước
                </div>
            `;
            return;
        }
        
        // Hiển thị loading
        timeSlotContainer.innerHTML = `
            <div class="text-center text-muted py-4">
                <div class="loading-spinner me-2"></div> Đang kiểm tra khung giờ khả dụng...
            </div>
        `;
        
        // Gọi API để lấy khung giờ
        fetch(`/api/available-time-slots?date=${date}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Lỗi kết nối: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.available_time_slots && data.available_time_slots.length > 0) {
                    // Hiển thị các khung giờ khả dụng
                    timeSlotContainer.innerHTML = '<div class="d-flex flex-wrap">';
                    
                    data.available_time_slots.forEach(slot => {
                        timeSlotContainer.innerHTML += `
                            <div class="time-slot-wrapper">
                                <input type="radio" name="time_slot_id" id="time-slot-${slot.id}" value="${slot.id}" class="time-radio" required>
                                <label for="time-slot-${slot.id}" class="time-slot-div btn btn-outline-primary">
                                    ${slot.formatted_time}
                                </label>
                            </div>
                        `;
                    });
                    
                    timeSlotContainer.innerHTML += '</div>';
                } else {
                    timeSlotContainer.innerHTML = `
                        <div class="text-center text-danger py-4">
                            <i class="fas fa-exclamation-circle me-2"></i> Không có khung giờ nào khả dụng cho ngày này
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                timeSlotContainer.innerHTML = `
                    <div class="text-center text-danger py-4">
                        <i class="fas fa-exclamation-circle me-2"></i> Đã xảy ra lỗi: ${error.message}
                    </div>
                `;
            });
    }
</script>
@endsection
