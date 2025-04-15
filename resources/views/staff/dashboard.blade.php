@extends('layouts.staff')

@section('title', 'Trang chủ - Cán bộ viên chức')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Trang chủ cán bộ viên chức</h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Lịch hẹn sắp tới</h6>
                        <h2 class="mb-0">{{ $upcomingAppointmentsCount }}</h2>
                    </div>
                    <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('staff.appointments.index') }}" class="text-white">Xem chi tiết <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Lịch hẹn đã hoàn thành</h6>
                        <h2 class="mb-0">{{ $completedAppointmentsCount }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('staff.appointments.index') }}" class="text-white">Xem chi tiết <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Đặt lịch khám mới</h6>
                        <p class="mb-0">Đặt lịch khám sức khỏe</p>
                    </div>
                    <i class="fas fa-calendar-plus fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('staff.appointments.create') }}" class="text-white">Đặt lịch ngay <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Thông tin cá nhân</h6>
                        <p class="mb-0">Cập nhật thông tin</p>
                    </div>
                    <i class="fas fa-user-edit fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('staff.profile.index') }}" class="text-dark">Xem chi tiết <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lịch hẹn gần đây</h5>
                    <a href="{{ route('staff.appointments.index') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
            </div>
            <div class="card-body">
                @if($appointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Dịch vụ</th>
                                    <th>Ngày hẹn</th>
                                    <th>Giờ hẹn</th>
                                    <th>Trạng thái</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->service->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y') }}</td>
                                        <td>{{ $appointment->timeAppointment->started_time }}</td>
                                        <td>
                                            @if($appointment->status == 'pending')
                                                <span class="badge bg-warning">Chờ xác nhận</span>
                                            @elseif($appointment->status == 'confirmed')
                                                <span class="badge bg-info">Đã xác nhận</span>
                                            @elseif($appointment->status == 'completed')
                                                <span class="badge bg-success">Hoàn thành</span>
                                            @elseif($appointment->status == 'cancelled')
                                                <span class="badge bg-danger">Đã hủy</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('staff.appointments.show', $appointment->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="mb-0">Bạn chưa có lịch hẹn nào.</p>
                        <a href="{{ route('staff.appointments.create') }}" class="btn btn-primary mt-3">Đặt lịch ngay</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Dịch vụ khám sức khỏe</h5>
            </div>
            <div class="card-body">
                @if($services->count() > 0)
                    <div class="list-group">
                        @foreach($services as $service)
                            <a href="{{ route('staff.appointments.create', $service->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $service->name }}</h6>
                                    <small>{{ number_format($service->price, 0, ',', '.') }} VNĐ</small>
                                </div>
                                <p class="mb-1 text-muted small">{{ Str::limit($service->description, 100) }}</p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-heartbeat fa-3x text-muted mb-3"></i>
                        <p class="mb-0">Không có dịch vụ khám sức khỏe nào.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
