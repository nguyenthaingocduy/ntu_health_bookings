@extends('layouts.admin')

@section('title', 'Nhập kết quả khám sức khỏe')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nhập kết quả khám sức khỏe</h1>
        <a href="{{ route('admin.health-checkups.show', $appointment->id) }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin cán bộ</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Họ và tên:</th>
                            <td>{{ $appointment->user->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Mã cán bộ:</th>
                            <td>{{ $appointment->user->staff_id }}</td>
                        </tr>
                        <tr>
                            <th>Đơn vị:</th>
                            <td>{{ $appointment->user->department }}</td>
                        </tr>
                        <tr>
                            <th>Chức vụ:</th>
                            <td>{{ $appointment->user->position }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin lịch khám</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Dịch vụ khám:</th>
                            <td>{{ $appointment->service->name }}</td>
                        </tr>
                        <tr>
                            <th>Ngày khám:</th>
                            <td>{{ $appointment->appointment_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Giờ khám:</th>
                            <td>{{ $appointment->timeSlot ? $appointment->timeSlot->start_time->format('H:i') . ' - ' . $appointment->timeSlot->end_time->format('H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Bác sĩ phụ trách:</th>
                            <td>{{ $appointment->doctor ? $appointment->doctor->full_name : 'Chưa phân công' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Kết quả khám sức khỏe</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.health-checkups.record.save', $appointment->id) }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="font-weight-bold mb-3">Chỉ số sức khỏe</h5>
                        
                        <div class="form-group row">
                            <label for="height" class="col-sm-4 col-form-label">Chiều cao (cm)</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" min="0" max="300" class="form-control @error('height') is-invalid @enderror" id="height" name="height" value="{{ old('height', $healthRecord->height ?? '') }}">
                                @error('height')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="weight" class="col-sm-4 col-form-label">Cân nặng (kg)</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" min="0" max="500" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $healthRecord->weight ?? '') }}">
                                @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="blood_pressure" class="col-sm-4 col-form-label">Huyết áp</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('blood_pressure') is-invalid @enderror" id="blood_pressure" name="blood_pressure" placeholder="Ví dụ: 120/80" value="{{ old('blood_pressure', $healthRecord->blood_pressure ?? '') }}">
                                @error('blood_pressure')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="heart_rate" class="col-sm-4 col-form-label">Nhịp tim (bpm)</label>
                            <div class="col-sm-8">
                                <input type="number" min="0" max="300" class="form-control @error('heart_rate') is-invalid @enderror" id="heart_rate" name="heart_rate" value="{{ old('heart_rate', $healthRecord->heart_rate ?? '') }}">
                                @error('heart_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="blood_type" class="col-sm-4 col-form-label">Nhóm máu</label>
                            <div class="col-sm-8">
                                <select class="form-control @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type">
                                    <option value="">-- Chọn nhóm máu --</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                        <option value="{{ $type }}" {{ old('blood_type', $healthRecord->blood_type ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('blood_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="font-weight-bold mb-3">Thông tin bổ sung</h5>
                        
                        <div class="form-group row">
                            <label for="allergies" class="col-sm-4 col-form-label">Dị ứng</label>
                            <div class="col-sm-8">
                                <textarea class="form-control @error('allergies') is-invalid @enderror" id="allergies" name="allergies" rows="2">{{ old('allergies', $healthRecord->allergies ?? '') }}</textarea>
                                @error('allergies')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="medical_history" class="col-sm-4 col-form-label">Lịch sử bệnh</label>
                            <div class="col-sm-8">
                                <textarea class="form-control @error('medical_history') is-invalid @enderror" id="medical_history" name="medical_history" rows="2">{{ old('medical_history', $healthRecord->medical_history ?? '') }}</textarea>
                                @error('medical_history')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="next_check_date" class="col-sm-4 col-form-label">Ngày tái khám</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control @error('next_check_date') is-invalid @enderror" id="next_check_date" name="next_check_date" value="{{ old('next_check_date', $healthRecord && $healthRecord->next_check_date ? $healthRecord->next_check_date->format('Y-m-d') : '') }}">
                                @error('next_check_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5 class="font-weight-bold mb-3">Kết quả khám</h5>
                        
                        <div class="form-group">
                            <label for="diagnosis">Chẩn đoán</label>
                            <textarea class="form-control @error('diagnosis') is-invalid @enderror" id="diagnosis" name="diagnosis" rows="3">{{ old('diagnosis', $healthRecord->diagnosis ?? '') }}</textarea>
                            @error('diagnosis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="recommendations">Khuyến nghị</label>
                            <textarea class="form-control @error('recommendations') is-invalid @enderror" id="recommendations" name="recommendations" rows="3">{{ old('recommendations', $healthRecord->recommendations ?? '') }}</textarea>
                            @error('recommendations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="doctor_notes">Ghi chú của bác sĩ</label>
                            <textarea class="form-control @error('doctor_notes') is-invalid @enderror" id="doctor_notes" name="doctor_notes" rows="3">{{ old('doctor_notes', $healthRecord->doctor_notes ?? '') }}</textarea>
                            @error('doctor_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Lưu kết quả khám
                    </button>
                    <a href="{{ route('admin.health-checkups.show', $appointment->id) }}" class="btn btn-secondary btn-lg">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Calculate BMI when height or weight changes
        $('#height, #weight').change(function() {
            const height = parseFloat($('#height').val()) / 100; // convert to meters
            const weight = parseFloat($('#weight').val());
            
            if (height > 0 && weight > 0) {
                const bmi = weight / (height * height);
                
                // You can display BMI somewhere on the form if needed
                console.log('BMI:', bmi.toFixed(2));
            }
        });
    });
</script>
@endsection
