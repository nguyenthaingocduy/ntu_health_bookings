@extends('layouts.admin')

@section('title', 'Chỉnh sửa lịch khám sức khỏe')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa lịch khám sức khỏe</h1>
        <a href="{{ route('admin.health-checkups.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin lịch khám</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.health-checkups.update', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Cán bộ</label>
                        <input type="text" class="form-control" value="{{ $appointment->user->full_name }} - {{ $appointment->user->staff_id }}" readonly>
                        <small class="text-muted">{{ $appointment->user->department }}</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="service_id">Dịch vụ khám <span class="text-danger">*</span></label>
                        <select name="service_id" id="service_id" class="form-control @error('service_id') is-invalid @enderror" required>
                            <option value="">-- Chọn dịch vụ --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id', $appointment->service_id) == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="appointment_date">Ngày khám <span class="text-danger">*</span></label>
                        <input type="date" name="appointment_date" id="appointment_date" class="form-control @error('appointment_date') is-invalid @enderror" value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" required>
                        @error('appointment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="time_slot_id">Khung giờ <span class="text-danger">*</span></label>
                        <select name="time_slot_id" id="time_slot_id" class="form-control @error('time_slot_id') is-invalid @enderror" required>
                            <option value="">-- Chọn khung giờ --</option>
                            @foreach($timeSlots as $timeSlot)
                                <option value="{{ $timeSlot->id }}" {{ old('time_slot_id', $appointment->time_slot_id) == $timeSlot->id ? 'selected' : '' }}>
                                    {{ $timeSlot->start_time->format('H:i') }} - {{ $timeSlot->end_time->format('H:i') }}
                                    @if($timeSlot->day_of_week !== null)
                                        ({{ ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'][$timeSlot->day_of_week] }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('time_slot_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="doctor_id">Bác sĩ phụ trách <span class="text-danger">*</span></label>
                        <select name="doctor_id" id="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror" required>
                            <option value="">-- Chọn bác sĩ --</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="status">Trạng thái <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                            <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            <option value="no-show" {{ old('status', $appointment->status) == 'no-show' ? 'selected' : '' }}>Không đến</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="notes">Ghi chú</label>
                    <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $appointment->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                    <a href="{{ route('admin.health-checkups.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize select2 for better dropdown experience
        if ($.fn.select2) {
            $('#service_id, #time_slot_id, #doctor_id, #status').select2({
                placeholder: "-- Chọn --",
                allowClear: true
            });
        }
    });
</script>
@endsection
