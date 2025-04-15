@extends('layouts.staff')

@section('title', 'Chi tiết lịch hẹn khám sức khỏe')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Chi tiết lịch hẹn</h1>
    <a href="{{ route('staff.appointments.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thông tin lịch hẹn</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Mã lịch hẹn:</div>
                    <div class="col-md-8">{{ substr($appointment->id, 0, 8) }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Trạng thái:</div>
                    <div class="col-md-8">
                        @if($appointment->status == 'pending')
                            <span class="badge bg-warning">Chờ xác nhận</span>
                        @elseif($appointment->status == 'confirmed')
                            <span class="badge bg-info">Đã xác nhận</span>
                        @elseif($appointment->status == 'completed')
                            <span class="badge bg-success">Hoàn thành</span>
                        @elseif($appointment->status == 'cancelled')
                            <span class="badge bg-danger">Đã hủy</span>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Dịch vụ:</div>
                    <div class="col-md-8">{{ $appointment->service->name }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Ngày đăng ký:</div>
                    <div class="col-md-8">{{ \Carbon\Carbon::parse($appointment->date_register)->format('d/m/Y H:i') }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Ngày hẹn:</div>
                    <div class="col-md-8">{{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y') }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Giờ hẹn:</div>
                    <div class="col-md-8">{{ $appointment->timeAppointment->started_time }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Nhân viên phụ trách:</div>
                    <div class="col-md-8">
                        @if($appointment->employee)
                            {{ $appointment->employee->first_name }} {{ $appointment->employee->last_name }}
                        @else
                            <span class="text-muted">Chưa phân công</span>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Ghi chú:</div>
                    <div class="col-md-8">
                        @if($appointment->notes)
                            {{ $appointment->notes }}
                        @else
                            <span class="text-muted">Không có ghi chú</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white">
                @if(in_array($appointment->status, ['pending', 'confirmed']))
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        <i class="fas fa-times-circle me-1"></i> Hủy lịch hẹn
                    </button>
                @endif
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thông tin dịch vụ</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Tên dịch vụ:</div>
                    <div class="col-md-8">{{ $appointment->service->name }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Giá dịch vụ:</div>
                    <div class="col-md-8">{{ number_format($appointment->service->price, 0, ',', '.') }} VNĐ</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Mô tả:</div>
                    <div class="col-md-8">
                        @if($appointment->service->description)
                            {{ $appointment->service->description }}
                        @else
                            <span class="text-muted">Không có mô tả</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thông tin cá nhân</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-5 fw-bold">Họ tên:</div>
                    <div class="col-md-7">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-5 fw-bold">Email:</div>
                    <div class="col-md-7">{{ Auth::user()->email }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-5 fw-bold">Số điện thoại:</div>
                    <div class="col-md-7">{{ Auth::user()->phone }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-5 fw-bold">Địa chỉ:</div>
                    <div class="col-md-7">{{ Auth::user()->address }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-5 fw-bold">Giới tính:</div>
                    <div class="col-md-7">
                        @if(Auth::user()->gender == 'male')
                            Nam
                        @elseif(Auth::user()->gender == 'female')
                            Nữ
                        @else
                            Khác
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Hướng dẫn</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6><i class="fas fa-info-circle text-primary me-2"></i>Quy trình khám</h6>
                    <ol class="ps-3">
                        <li>Đến trước giờ hẹn 15 phút</li>
                        <li>Xuất trình giấy tờ tùy thân</li>
                        <li>Làm thủ tục tại quầy tiếp nhận</li>
                        <li>Chờ đến lượt khám theo hướng dẫn</li>
                        <li>Nhận kết quả và thanh toán</li>
                    </ol>
                </div>
                
                <div class="mb-3">
                    <h6><i class="fas fa-exclamation-triangle text-warning me-2"></i>Lưu ý</h6>
                    <ul class="ps-3">
                        <li>Mang theo giấy tờ tùy thân</li>
                        <li>Có thể hủy lịch hẹn trước 24 giờ</li>
                        <li>Nếu cần hỗ trợ, vui lòng liên hệ số điện thoại: <strong>(0258) 2471303</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hủy lịch hẹn -->
@if(in_array($appointment->status, ['pending', 'confirmed']))
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận hủy lịch hẹn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn hủy lịch hẹn này không?</p>
                <p><strong>Dịch vụ:</strong> {{ $appointment->service->name }}</p>
                <p><strong>Ngày hẹn:</strong> {{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y') }}</p>
                <p><strong>Giờ hẹn:</strong> {{ $appointment->timeAppointment->started_time }}</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> Lưu ý: Hành động này không thể hoàn tác.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <form action="{{ route('staff.appointments.cancel', $appointment->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
