@extends('layouts.admin')

@section('title', 'Quản lý hồ sơ sức khỏe')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý hồ sơ sức khỏe</h1>
        <a href="{{ route('admin.health-checkups.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại lịch khám
        </a>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Bộ lọc</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.health-checkups.records') }}" method="GET" class="row">
                <div class="col-md-4 mb-3">
                    <label for="date_from">Từ ngày</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="date_to">Đến ngày</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="search">Tìm kiếm</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Tên, email, SĐT, mã nhân viên..." value="{{ request('search') }}">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    <a href="{{ route('admin.health-checkups.records') }}" class="btn btn-secondary">Đặt lại</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Health Records Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách hồ sơ sức khỏe</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã hồ sơ</th>
                            <th>Cán bộ</th>
                            <th>Ngày khám</th>
                            <th>Dịch vụ</th>
                            <th>Chỉ số sức khỏe</th>
                            <th>Ngày tái khám</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($healthRecords as $record)
                        <tr>
                            <td>{{ substr($record->id, 0, 8) }}</td>
                            <td>
                                {{ $record->user->full_name }}<br>
                                <small class="text-muted">{{ $record->user->staff_id }}</small><br>
                                <small class="text-muted">{{ $record->user->department }}</small>
                            </td>
                            <td>{{ $record->check_date->format('d/m/Y') }}</td>
                            <td>{{ $record->appointment && $record->appointment->service ? $record->appointment->service->name : 'N/A' }}</td>
                            <td>
                                @if($record->height && $record->weight)
                                    <strong>Chiều cao:</strong> {{ $record->height }} cm<br>
                                    <strong>Cân nặng:</strong> {{ $record->weight }} kg<br>
                                    <strong>Huyết áp:</strong> {{ $record->blood_pressure ?? 'N/A' }}
                                @else
                                    <span class="text-muted">Không có dữ liệu</span>
                                @endif
                            </td>
                            <td>{{ $record->next_check_date ? $record->next_check_date->format('d/m/Y') : 'Không có' }}</td>
                            <td>
                                <a href="{{ route('admin.health-checkups.records.show', $record->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($record->appointment)
                                <a href="{{ route('admin.health-checkups.record.form', $record->appointment->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                <a href="#" class="btn btn-success btn-sm" onclick="printRecord('{{ $record->id }}')">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có hồ sơ sức khỏe nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $healthRecords->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function printRecord(id) {
        // Open a new window with the printable version of the health record
        window.open("{{ url('admin/health-checkups/records') }}/" + id + "?print=true", "_blank");
    }
    
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
