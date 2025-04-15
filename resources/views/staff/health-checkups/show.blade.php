@extends('layouts.staff')

@section('title', 'Chi tiết lịch khám sức khỏe')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết lịch khám sức khỏe</h1>
        <a href="{{ route('staff.health-checkups.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin lịch khám</h6>
                    <div>
                        @if(in_array($appointment->status, ['pending', 'confirmed']))
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="fas fa-times me-1"></i> Hủy lịch
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Mã lịch hẹn:</div>
                        <div class="col-md-8">{{ substr($appointment->id, 0, 8) }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Dịch vụ:</div>
                        <div class="col-md-8">{{ $appointment->service->name }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Ngày khám:</div>
                        <div class="col-md-8">{{ $appointment->appointment_date->format('d/m/Y') }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Giờ khám:</div>
                        <div class="col-md-8">
                            @if($appointment->timeSlot)
                                {{ $appointment->timeSlot->formatted_time }}
                            @else
                                {{ $appointment->appointment_date->format('H:i') }}
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Bác sĩ phụ trách:</div>
                        <div class="col-md-8">
                            @if($appointment->doctor)
                                {{ $appointment->doctor->full_name }}
                            @else
                                <span class="text-muted">Chưa phân công</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Trạng thái:</div>
                        <div class="col-md-8">
                            @if($appointment->status == 'pending')
                                <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                            @elseif($appointment->status == 'confirmed')
                                <span class="badge bg-primary">Đã xác nhận</span>
                            @elseif($appointment->status == 'completed')
                                <span class="badge bg-success">Đã hoàn thành</span>
                            @elseif($appointment->status == 'cancelled')
                                <span class="badge bg-danger">Đã hủy</span>
                                @if($appointment->cancellation_reason)
                                    <div class="mt-1 text-danger">
                                        <small><strong>Lý do:</strong> {{ $appointment->cancellation_reason }}</small>
                                    </div>
                                @endif
                            @elseif($appointment->status == 'no-show')
                                <span class="badge bg-secondary">Không đến</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Ngày đặt lịch:</div>
                        <div class="col-md-8">{{ $appointment->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    
                    @if($appointment->notes)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Ghi chú:</div>
                            <div class="col-md-8">{{ $appointment->notes }}</div>
                        </div>
                    @endif
                    
                    @if($appointment->check_in_time)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Thời gian check-in:</div>
                            <div class="col-md-8">{{ $appointment->check_in_time->format('d/m/Y H:i') }}</div>
                        </div>
                    @endif
                    
                    @if($appointment->check_out_time)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Thời gian hoàn thành:</div>
                            <div class="col-md-8">{{ $appointment->check_out_time->format('d/m/Y H:i') }}</div>
                        </div>
                    @endif
                </div>
            </div>
            
            @if($appointment->healthRecord)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Kết quả khám sức khỏe</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Ngày khám:</div>
                            <div class="col-md-8">{{ $appointment->healthRecord->check_date->format('d/m/Y') }}</div>
                        </div>
                        
                        @if($appointment->healthRecord->height)
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Chiều cao:</div>
                                <div class="col-md-8">{{ $appointment->healthRecord->height }} cm</div>
                            </div>
                        @endif
                        
                        @if($appointment->healthRecord->weight)
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Cân nặng:</div>
                                <div class="col-md-8">{{ $appointment->healthRecord->weight }} kg</div>
                            </div>
                        @endif
                        
                        @if($appointment->healthRecord->blood_pressure)
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Huyết áp:</div>
                                <div class="col-md-8">{{ $appointment->healthRecord->blood_pressure }} mmHg</div>
                            </div>
                        @endif
                        
                        @if($appointment->healthRecord->heart_rate)
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Nhịp tim:</div>
                                <div class="col-md-8">{{ $appointment->healthRecord->heart_rate }} bpm</div>
                            </div>
                        @endif
                        
                        @if($appointment->healthRecord->diagnosis)
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Chẩn đoán:</div>
                                <div class="col-md-8">{{ $appointment->healthRecord->diagnosis }}</div>
                            </div>
                        @endif
                        
                        @if($appointment->healthRecord->recommendations)
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Khuyến nghị:</div>
                                <div class="col-md-8">{{ $appointment->healthRecord->recommendations }}</div>
                            </div>
                        @endif
                        
                        @if($appointment->healthRecord->next_check_date)
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Ngày tái khám:</div>
                                <div class="col-md-8">{{ $appointment->healthRecord->next_check_date->format('d/m/Y') }}</div>
                            </div>
                        @endif
                        
                        <div class="mt-3">
                            <a href="{{ route('staff.health-checkups.records.show', $appointment->healthRecord->id) }}" class="btn btn-info">
                                <i class="fas fa-file-medical me-1"></i> Xem chi tiết hồ sơ
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin dịch vụ</h6>
                </div>
                <div class="card-body">
                    <h5>{{ $appointment->service->name }}</h5>
                    <p>{{ $appointment->service->description }}</p>
                    
                    <div class="mb-2">
                        <strong>Thời gian:</strong> {{ $appointment->service->duration }} phút
                    </div>
                    
                    <div class="mb-2">
                        <strong>Giá:</strong> 
                        @if($appointment->service->price == 0)
                            <span class="text-success">Miễn phí</span>
                        @else
                            {{ number_format($appointment->service->price, 0, ',', '.') }} VNĐ
                        @endif
                    </div>
                    
                    @if($appointment->service->required_tests)
                        <div class="mb-2">
                            <strong>Các xét nghiệm:</strong>
                            <ul class="mb-0 ps-3">
                                @foreach(json_decode($appointment->service->required_tests) as $test)
                                    <li>{{ $test }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if($appointment->service->preparation_instructions)
                        <div class="mt-3">
                            <strong>Hướng dẫn chuẩn bị:</strong>
                            <p class="mb-0 mt-1">{{ $appointment->service->preparation_instructions }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            @if($appointment->doctor)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin bác sĩ</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img class="img-profile rounded-circle" src="{{ $appointment->doctor->profile_image ?? asset('img/undraw_profile.svg') }}" width="100">
                            <h5 class="mt-2">{{ $appointment->doctor->full_name }}</h5>
                            <p class="text-muted">{{ $appointment->doctor->position ?? 'Bác sĩ' }}</p>
                        </div>
                        
                        @if($appointment->doctor->department)
                            <div class="mb-2">
                                <strong>Khoa/Phòng:</strong> {{ $appointment->doctor->department }}
                            </div>
                        @endif
                        
                        @if($appointment->doctor->email)
                            <div class="mb-2">
                                <strong>Email:</strong> {{ $appointment->doctor->email }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Hủy lịch khám</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('staff.health-checkups.cancel', $appointment->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn hủy lịch khám này không?</p>
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Lý do hủy</label>
                        <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
