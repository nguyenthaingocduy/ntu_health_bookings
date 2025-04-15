@extends('layouts.staff')

@section('title', 'Danh sách lịch hẹn khám sức khỏe')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Danh sách lịch hẹn khám sức khỏe</h1>
    <a href="{{ route('staff.appointments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-1"></i> Đặt lịch mới
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($appointments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã lịch hẹn</th>
                            <th>Dịch vụ</th>
                            <th>Ngày đăng ký</th>
                            <th>Ngày hẹn</th>
                            <th>Giờ hẹn</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ substr($appointment->id, 0, 8) }}</td>
                                <td>{{ $appointment->service->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->date_register)->format('d/m/Y') }}</td>
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
                                    <a href="{{ route('staff.appointments.show', $appointment->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if(in_array($appointment->status, ['pending', 'confirmed']))
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $appointment->id }}" title="Hủy lịch hẹn">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        
                                        <!-- Modal Hủy lịch hẹn -->
                                        <div class="modal fade" id="cancelModal{{ $appointment->id }}" tabindex="-1" aria-hidden="true">
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $appointments->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h5>Không có lịch hẹn nào</h5>
                <p class="text-muted">Bạn chưa đặt lịch hẹn khám sức khỏe nào.</p>
                <a href="{{ route('staff.appointments.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus-circle me-1"></i> Đặt lịch ngay
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
