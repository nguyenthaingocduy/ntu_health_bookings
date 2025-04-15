@extends('layouts.staff')

@section('title', 'Thông tin cá nhân')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thông tin cá nhân</h1>
    <div>
        <a href="{{ route('staff.profile.edit') }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i> Chỉnh sửa
        </a>
        <a href="{{ route('staff.profile.change-password') }}" class="btn btn-secondary ms-2">
            <i class="fas fa-key me-1"></i> Đổi mật khẩu
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thông tin cá nhân</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Họ:</div>
                    <div class="col-md-8">{{ $staff->first_name }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Tên:</div>
                    <div class="col-md-8">{{ $staff->last_name }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Email:</div>
                    <div class="col-md-8">{{ $staff->email }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Số điện thoại:</div>
                    <div class="col-md-8">{{ $staff->phone }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Địa chỉ:</div>
                    <div class="col-md-8">{{ $staff->address }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Giới tính:</div>
                    <div class="col-md-8">
                        @if($staff->gender == 'male')
                            Nam
                        @elseif($staff->gender == 'female')
                            Nữ
                        @else
                            Khác
                        @endif
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Ngày sinh:</div>
                    <div class="col-md-8">
                        @if($staff->birthday)
                            {{ \Carbon\Carbon::parse($staff->birthday)->format('d/m/Y') }}
                        @else
                            <span class="text-muted">Chưa cập nhật</span>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Vai trò:</div>
                    <div class="col-md-8">
                        @if($staff->role)
                            {{ $staff->role->name }}
                        @else
                            <span class="text-muted">Chưa phân quyền</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thống kê</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-0">Tổng số lịch hẹn</h6>
                        <small class="text-muted">Tất cả lịch hẹn đã đặt</small>
                    </div>
                    <span class="badge bg-primary rounded-pill">{{ $staff->appointments->count() }}</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-0">Đang chờ xác nhận</h6>
                        <small class="text-muted">Lịch hẹn chưa được xác nhận</small>
                    </div>
                    <span class="badge bg-warning rounded-pill">{{ $staff->appointments->where('status', 'pending')->count() }}</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-0">Đã xác nhận</h6>
                        <small class="text-muted">Lịch hẹn đã được xác nhận</small>
                    </div>
                    <span class="badge bg-info rounded-pill">{{ $staff->appointments->where('status', 'confirmed')->count() }}</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-0">Đã hoàn thành</h6>
                        <small class="text-muted">Lịch hẹn đã hoàn thành</small>
                    </div>
                    <span class="badge bg-success rounded-pill">{{ $staff->appointments->where('status', 'completed')->count() }}</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Đã hủy</h6>
                        <small class="text-muted">Lịch hẹn đã bị hủy</small>
                    </div>
                    <span class="badge bg-danger rounded-pill">{{ $staff->appointments->where('status', 'cancelled')->count() }}</span>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thao tác nhanh</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('staff.appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus me-1"></i> Đặt lịch khám mới
                    </a>
                    <a href="{{ route('staff.appointments.index') }}" class="btn btn-info">
                        <i class="fas fa-calendar-alt me-1"></i> Xem lịch hẹn
                    </a>
                    <a href="{{ route('staff.profile.edit') }}" class="btn btn-secondary">
                        <i class="fas fa-user-edit me-1"></i> Cập nhật thông tin
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
