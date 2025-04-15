@extends('layouts.staff')

@section('title', 'Hồ sơ sức khỏe')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Hồ sơ sức khỏe</h1>
        <a href="{{ route('staff.health-checkups.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại lịch khám
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách hồ sơ sức khỏe</h6>
        </div>
        <div class="card-body">
            @if($healthRecords->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-file-medical fa-4x text-muted mb-3"></i>
                    <h5>Không có hồ sơ sức khỏe nào</h5>
                    <p class="text-muted">Bạn chưa có kết quả khám sức khỏe nào được lưu trữ.</p>
                    <a href="{{ route('staff.health-checkups.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus-circle me-1"></i> Đặt lịch khám ngay
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="healthRecordsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Ngày khám</th>
                                <th>Dịch vụ</th>
                                <th>Chỉ số cơ bản</th>
                                <th>Chẩn đoán</th>
                                <th>Ngày tái khám</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($healthRecords as $record)
                                <tr>
                                    <td>{{ $record->check_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if($record->appointment && $record->appointment->service)
                                            {{ $record->appointment->service->name }}
                                        @else
                                            <span class="text-muted">Không có thông tin</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->height && $record->weight)
                                            <span class="d-block">Chiều cao: {{ $record->height }} cm</span>
                                            <span class="d-block">Cân nặng: {{ $record->weight }} kg</span>
                                            @php
                                                $bmi = $record->weight / (($record->height / 100) * ($record->height / 100));
                                                $bmiCategory = '';
                                                if ($bmi < 18.5) {
                                                    $bmiCategory = 'Thiếu cân';
                                                } elseif ($bmi >= 18.5 && $bmi < 25) {
                                                    $bmiCategory = 'Bình thường';
                                                } elseif ($bmi >= 25 && $bmi < 30) {
                                                    $bmiCategory = 'Thừa cân';
                                                } else {
                                                    $bmiCategory = 'Béo phì';
                                                }
                                            @endphp
                                            <span class="d-block">BMI: {{ number_format($bmi, 1) }} ({{ $bmiCategory }})</span>
                                        @endif
                                        
                                        @if($record->blood_pressure)
                                            <span class="d-block">Huyết áp: {{ $record->blood_pressure }} mmHg</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->diagnosis)
                                            {{ Str::limit($record->diagnosis, 50) }}
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->next_check_date)
                                            {{ $record->next_check_date->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('staff.health-checkups.records.show', $record->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $healthRecords->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#healthRecordsTable').DataTable({
            "order": [[0, "desc"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json"
            }
        });
    });
</script>
@endsection
