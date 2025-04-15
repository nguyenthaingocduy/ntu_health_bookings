@extends('layouts.staff')

@section('title', 'Chi tiết hồ sơ sức khỏe')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết hồ sơ sức khỏe</h1>
        <a href="{{ route('staff.health-checkups.records') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại danh sách
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin sức khỏe</h6>
                    <div>
                        <a href="#" class="btn btn-sm btn-primary" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> In kết quả
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Ngày khám:</div>
                        <div class="col-md-8">{{ $healthRecord->check_date->format('d/m/Y') }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Dịch vụ khám:</div>
                        <div class="col-md-8">
                            @if($healthRecord->appointment && $healthRecord->appointment->service)
                                {{ $healthRecord->appointment->service->name }}
                            @else
                                <span class="text-muted">Không có thông tin</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Bác sĩ khám:</div>
                        <div class="col-md-8">
                            @if($healthRecord->appointment && $healthRecord->appointment->doctor)
                                {{ $healthRecord->appointment->doctor->full_name }}
                            @else
                                <span class="text-muted">Không có thông tin</span>
                            @endif
                        </div>
                    </div>
                    
                    <h6 class="mt-4 mb-3 font-weight-bold text-primary">Chỉ số cơ bản</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Chiều cao</h6>
                                    <p class="card-text display-6">{{ $healthRecord->height ?? 'N/A' }} <small class="text-muted">cm</small></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Cân nặng</h6>
                                    <p class="card-text display-6">{{ $healthRecord->weight ?? 'N/A' }} <small class="text-muted">kg</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Huyết áp</h6>
                                    <p class="card-text display-6">{{ $healthRecord->blood_pressure ?? 'N/A' }} <small class="text-muted">mmHg</small></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Nhịp tim</h6>
                                    <p class="card-text display-6">{{ $healthRecord->heart_rate ?? 'N/A' }} <small class="text-muted">bpm</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($healthRecord->height && $healthRecord->weight)
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Chỉ số BMI</h6>
                                @php
                                    $bmi = $healthRecord->weight / (($healthRecord->height / 100) * ($healthRecord->height / 100));
                                    $bmiCategory = '';
                                    $bmiColor = '';
                                    
                                    if ($bmi < 18.5) {
                                        $bmiCategory = 'Thiếu cân';
                                        $bmiColor = 'text-warning';
                                    } elseif ($bmi >= 18.5 && $bmi < 25) {
                                        $bmiCategory = 'Bình thường';
                                        $bmiColor = 'text-success';
                                    } elseif ($bmi >= 25 && $bmi < 30) {
                                        $bmiCategory = 'Thừa cân';
                                        $bmiColor = 'text-warning';
                                    } else {
                                        $bmiCategory = 'Béo phì';
                                        $bmiColor = 'text-danger';
                                    }
                                @endphp
                                <p class="card-text display-6">{{ number_format($bmi, 1) }} <small class="text-muted">({{ $bmiCategory }})</small></p>
                                
                                <div class="progress mt-2">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Thiếu cân</div>
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Bình thường</div>
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Thừa cân</div>
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Béo phì</div>
                                </div>
                                
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <strong>Phân loại BMI:</strong><br>
                                        Dưới 18.5: Thiếu cân<br>
                                        18.5 - 24.9: Bình thường<br>
                                        25.0 - 29.9: Thừa cân<br>
                                        Trên 30.0: Béo phì
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($healthRecord->blood_type)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Nhóm máu:</div>
                            <div class="col-md-8">{{ $healthRecord->blood_type }}</div>
                        </div>
                    @endif
                    
                    @if($healthRecord->allergies)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Dị ứng:</div>
                            <div class="col-md-8">{{ $healthRecord->allergies }}</div>
                        </div>
                    @endif
                    
                    @if($healthRecord->medical_history)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Tiền sử bệnh:</div>
                            <div class="col-md-8">{{ $healthRecord->medical_history }}</div>
                        </div>
                    @endif
                    
                    <h6 class="mt-4 mb-3 font-weight-bold text-primary">Kết quả khám</h6>
                    
                    @if($healthRecord->diagnosis)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Chẩn đoán:</div>
                            <div class="col-md-8">{{ $healthRecord->diagnosis }}</div>
                        </div>
                    @endif
                    
                    @if($healthRecord->recommendations)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Khuyến nghị:</div>
                            <div class="col-md-8">{{ $healthRecord->recommendations }}</div>
                        </div>
                    @endif
                    
                    @if($healthRecord->next_check_date)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Ngày tái khám:</div>
                            <div class="col-md-8">{{ $healthRecord->next_check_date->format('d/m/Y') }}</div>
                        </div>
                    @endif
                    
                    @if($healthRecord->doctor_notes)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Ghi chú của bác sĩ:</div>
                            <div class="col-md-8">{{ $healthRecord->doctor_notes }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin cá nhân</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img class="img-profile rounded-circle" src="{{ auth()->user()->profile_image ?? asset('img/undraw_profile.svg') }}" width="100">
                        <h5 class="mt-2">{{ auth()->user()->full_name }}</h5>
                        <p class="text-muted">{{ auth()->user()->staff_id ?? 'Mã nhân viên: N/A' }}</p>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Email:</div>
                        <div class="col-md-8">{{ auth()->user()->email }}</div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Điện thoại:</div>
                        <div class="col-md-8">{{ auth()->user()->phone ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Phòng/Ban:</div>
                        <div class="col-md-8">{{ auth()->user()->department ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Chức vụ:</div>
                        <div class="col-md-8">{{ auth()->user()->position ?? 'N/A' }}</div>
                    </div>
                    
                    @if(auth()->user()->last_health_check)
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-1"></i> Lần khám sức khỏe gần nhất: {{ auth()->user()->last_health_check->format('d/m/Y') }}
                        </div>
                    @endif
                </div>
            </div>
            
            @if($healthRecord->appointment)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin lịch khám</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Mã lịch hẹn:</div>
                            <div class="col-md-8">{{ substr($healthRecord->appointment->id, 0, 8) }}</div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Ngày khám:</div>
                            <div class="col-md-8">{{ $healthRecord->appointment->appointment_date->format('d/m/Y') }}</div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Giờ khám:</div>
                            <div class="col-md-8">
                                @if($healthRecord->appointment->timeSlot)
                                    {{ $healthRecord->appointment->timeSlot->formatted_time }}
                                @else
                                    {{ $healthRecord->appointment->appointment_date->format('H:i') }}
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('staff.health-checkups.show', $healthRecord->appointment->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-calendar-check me-1"></i> Xem chi tiết lịch hẹn
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {
        .sidebar, .topbar, .btn, footer, .scroll-to-top {
            display: none !important;
        }
        
        .container-fluid {
            width: 100% !important;
            padding: 0 !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .card-header {
            background-color: #f8f9fc !important;
            color: #000 !important;
        }
        
        body {
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }
    }
</style>
@endsection
