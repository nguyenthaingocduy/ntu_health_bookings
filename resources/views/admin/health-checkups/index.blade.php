@extends('layouts.admin')

@section('title', 'Quản lý lịch khám sức khỏe')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý lịch khám sức khỏe</h1>
        <a href="{{ route('admin.health-checkups.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tạo lịch khám mới
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng số lịch khám</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Chờ xác nhận</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Đã xác nhận</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['confirmed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Đã hoàn thành</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Bộ lọc</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.health-checkups.index') }}" method="GET" class="row">
                <div class="col-md-3 mb-3">
                    <label for="status">Trạng thái</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        <option value="no-show" {{ request('status') == 'no-show' ? 'selected' : '' }}>Không đến</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="date_from">Từ ngày</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="date_to">Đến ngày</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="search">Tìm kiếm</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Tên, email, SĐT, mã nhân viên..." value="{{ request('search') }}">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    <a href="{{ route('admin.health-checkups.index') }}" class="btn btn-secondary">Đặt lại</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách lịch khám sức khỏe</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã lịch hẹn</th>
                            <th>Cán bộ</th>
                            <th>Dịch vụ</th>
                            <th>Ngày giờ</th>
                            <th>Bác sĩ</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $appointment)
                        <tr>
                            <td>{{ substr($appointment->id, 0, 8) }}</td>
                            <td>
                                {{ $appointment->user->full_name }}<br>
                                <small class="text-muted">{{ $appointment->user->staff_id }}</small><br>
                                <small class="text-muted">{{ $appointment->user->department }}</small>
                            </td>
                            <td>{{ $appointment->service->name }}</td>
                            <td>
                                {{ $appointment->appointment_date->format('d/m/Y') }}<br>
                                <small class="text-muted">{{ $appointment->timeSlot ? $appointment->timeSlot->start_time->format('H:i') . ' - ' . $appointment->timeSlot->end_time->format('H:i') : 'N/A' }}</small>
                            </td>
                            <td>{{ $appointment->doctor ? $appointment->doctor->full_name : 'Chưa phân công' }}</td>
                            <td>
                                @if($appointment->status == 'pending')
                                    <span class="badge badge-warning">Chờ xác nhận</span>
                                @elseif($appointment->status == 'confirmed')
                                    <span class="badge badge-info">Đã xác nhận</span>
                                @elseif($appointment->status == 'completed')
                                    <span class="badge badge-success">Đã hoàn thành</span>
                                @elseif($appointment->status == 'cancelled')
                                    <span class="badge badge-danger">Đã hủy</span>
                                @elseif($appointment->status == 'no-show')
                                    <span class="badge badge-dark">Không đến</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.health-checkups.show', $appointment->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.health-checkups.edit', $appointment->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(in_array($appointment->status, ['confirmed', 'pending']))
                                    <a href="{{ route('admin.health-checkups.record.form', $appointment->id) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-notes-medical"></i> Nhập kết quả
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có lịch khám sức khỏe nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize datepickers if needed
        if ($.fn.datepicker) {
            $('#date_from, #date_to').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        }
    });
</script>
@endsection
