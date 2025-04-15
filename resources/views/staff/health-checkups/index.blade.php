@extends('layouts.staff')

@section('title', 'Lịch khám sức khỏe')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lịch khám sức khỏe</h1>
        <a href="{{ route('staff.health-checkups.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Đặt lịch khám mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách lịch khám sức khỏe</h6>
            <a href="{{ route('staff.health-checkups.records') }}" class="btn btn-sm btn-info">
                <i class="fas fa-clipboard-list me-1"></i> Xem hồ sơ sức khỏe
            </a>
        </div>
        <div class="card-body">
            @if($appointments->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <h5>Không có lịch khám nào</h5>
                    <p class="text-muted">Bạn chưa đặt lịch khám sức khỏe nào.</p>
                    <a href="{{ route('staff.health-checkups.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus-circle me-1"></i> Đặt lịch ngay
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="appointmentsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Mã lịch hẹn</th>
                                <th>Dịch vụ</th>
                                <th>Ngày khám</th>
                                <th>Giờ khám</th>
                                <th>Bác sĩ</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td>{{ substr($appointment->id, 0, 8) }}</td>
                                    <td>{{ $appointment->service->name }}</td>
                                    <td>{{ $appointment->appointment_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if($appointment->timeSlot)
                                            {{ $appointment->timeSlot->formatted_time }}
                                        @else
                                            {{ $appointment->appointment_date->format('H:i') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($appointment->doctor)
                                            {{ $appointment->doctor->full_name }}
                                        @else
                                            <span class="text-muted">Chưa phân công</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($appointment->status == 'pending')
                                            <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                        @elseif($appointment->status == 'confirmed')
                                            <span class="badge bg-primary">Đã xác nhận</span>
                                        @elseif($appointment->status == 'completed')
                                            <span class="badge bg-success">Đã hoàn thành</span>
                                        @elseif($appointment->status == 'cancelled')
                                            <span class="badge bg-danger">Đã hủy</span>
                                        @elseif($appointment->status == 'no-show')
                                            <span class="badge bg-secondary">Không đến</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('staff.health-checkups.show', $appointment->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if(in_array($appointment->status, ['pending', 'confirmed']))
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $appointment->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            
                                            <!-- Cancel Modal -->
                                            <div class="modal fade" id="cancelModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $appointment->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="cancelModalLabel{{ $appointment->id }}">Hủy lịch khám</h5>
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
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#appointmentsTable').DataTable({
            "order": [[2, "desc"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json"
            }
        });
    });
</script>
@endsection
