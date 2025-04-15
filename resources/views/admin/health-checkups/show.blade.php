@extends('layouts.admin')

@section('title', 'Chi tiết lịch khám sức khỏe')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết lịch khám sức khỏe</h1>
        <div>
            <a href="{{ route('admin.health-checkups.edit', $appointment->id) }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.health-checkups.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Appointment Details Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin lịch khám</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Thao tác:</div>
                    <a class="dropdown-item" href="{{ route('admin.health-checkups.edit', $appointment->id) }}">
                        <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Chỉnh sửa
                    </a>
                    @if(in_array($appointment->status, ['pending', 'confirmed']))
                        <a class="dropdown-item" href="{{ route('admin.health-checkups.record.form', $appointment->id) }}">
                            <i class="fas fa-notes-medical fa-sm fa-fw mr-2 text-gray-400"></i> Nhập kết quả khám
                        </a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#printModal">
                        <i class="fas fa-print fa-sm fa-fw mr-2 text-gray-400"></i> In phiếu khám
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Thông tin cán bộ</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Họ và tên:</th>
                            <td>{{ $appointment->user->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Mã cán bộ:</th>
                            <td>{{ $appointment->user->staff_id }}</td>
                        </tr>
                        <tr>
                            <th>Đơn vị:</th>
                            <td>{{ $appointment->user->department }}</td>
                        </tr>
                        <tr>
                            <th>Chức vụ:</th>
                            <td>{{ $appointment->user->position }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $appointment->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Số điện thoại:</th>
                            <td>{{ $appointment->user->phone }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Thông tin lịch khám</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Mã lịch hẹn:</th>
                            <td>{{ substr($appointment->id, 0, 8) }}</td>
                        </tr>
                        <tr>
                            <th>Dịch vụ khám:</th>
                            <td>{{ $appointment->service->name }}</td>
                        </tr>
                        <tr>
                            <th>Ngày khám:</th>
                            <td>{{ $appointment->appointment_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Giờ khám:</th>
                            <td>{{ $appointment->timeSlot ? $appointment->timeSlot->start_time->format('H:i') . ' - ' . $appointment->timeSlot->end_time->format('H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Bác sĩ phụ trách:</th>
                            <td>{{ $appointment->doctor ? $appointment->doctor->full_name : 'Chưa phân công' }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
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
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5 class="font-weight-bold">Ghi chú</h5>
                    <p>{{ $appointment->notes ?? 'Không có ghi chú' }}</p>
                </div>
            </div>
            
            @if($appointment->status == 'cancelled')
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <h5 class="font-weight-bold">Lý do hủy</h5>
                        <p>{{ $appointment->cancellation_reason }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            @if($appointment->is_completed)
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="alert alert-success">
                        <h5 class="font-weight-bold">Thông tin hoàn thành</h5>
                        <p><strong>Thời gian check-in:</strong> {{ $appointment->check_in_time ? $appointment->check_in_time->format('d/m/Y H:i') : 'N/A' }}</p>
                        <p><strong>Thời gian check-out:</strong> {{ $appointment->check_out_time ? $appointment->check_out_time->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Health Record Card (if exists) -->
    @if($appointment->healthRecord)
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Kết quả khám sức khỏe</h6>
            <a href="{{ route('admin.health-checkups.record.form', $appointment->id) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-edit fa-sm"></i> Cập nhật kết quả
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Chỉ số sức khỏe</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Chiều cao:</th>
                            <td>{{ $appointment->healthRecord->height ? $appointment->healthRecord->height . ' cm' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Cân nặng:</th>
                            <td>{{ $appointment->healthRecord->weight ? $appointment->healthRecord->weight . ' kg' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Huyết áp:</th>
                            <td>{{ $appointment->healthRecord->blood_pressure ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Nhịp tim:</th>
                            <td>{{ $appointment->healthRecord->heart_rate ? $appointment->healthRecord->heart_rate . ' bpm' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Nhóm máu:</th>
                            <td>{{ $appointment->healthRecord->blood_type ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Thông tin bổ sung</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Ngày khám:</th>
                            <td>{{ $appointment->healthRecord->check_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Dị ứng:</th>
                            <td>{{ $appointment->healthRecord->allergies ?? 'Không có' }}</td>
                        </tr>
                        <tr>
                            <th>Lịch sử bệnh:</th>
                            <td>{{ $appointment->healthRecord->medical_history ?? 'Không có' }}</td>
                        </tr>
                        <tr>
                            <th>Ngày tái khám:</th>
                            <td>{{ $appointment->healthRecord->next_check_date ? $appointment->healthRecord->next_check_date->format('d/m/Y') : 'Không có' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5 class="font-weight-bold">Chẩn đoán</h5>
                    <p>{{ $appointment->healthRecord->diagnosis ?? 'Không có chẩn đoán' }}</p>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5 class="font-weight-bold">Khuyến nghị</h5>
                    <p>{{ $appointment->healthRecord->recommendations ?? 'Không có khuyến nghị' }}</p>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5 class="font-weight-bold">Ghi chú của bác sĩ</h5>
                    <p>{{ $appointment->healthRecord->doctor_notes ?? 'Không có ghi chú' }}</p>
                </div>
            </div>
        </div>
    </div>
    @elseif(in_array($appointment->status, ['pending', 'confirmed']))
    <div class="card shadow mb-4">
        <div class="card-body text-center py-5">
            <h5 class="text-gray-500 mb-4">Chưa có kết quả khám sức khỏe</h5>
            <a href="{{ route('admin.health-checkups.record.form', $appointment->id) }}" class="btn btn-primary">
                <i class="fas fa-notes-medical"></i> Nhập kết quả khám
            </a>
        </div>
    </div>
    @endif
</div>

<!-- Print Modal -->
<div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="printModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printModalLabel">In phiếu khám sức khỏe</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn muốn in phiếu khám sức khỏe cho cán bộ <strong>{{ $appointment->user->full_name }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="printAppointment()">
                    <i class="fas fa-print"></i> In phiếu
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function printAppointment() {
        // You can implement the print functionality here
        // For now, we'll just open a new window with the appointment details
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>Phiếu khám sức khỏe - {{ $appointment->user->full_name }}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .title { font-size: 18px; font-weight: bold; margin: 10px 0; }
                    .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    .info-table th { text-align: left; width: 30%; padding: 8px; }
                    .info-table td { padding: 8px; }
                    .footer { margin-top: 50px; text-align: right; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>TRƯỜNG ĐẠI HỌC NHA TRANG</h2>
                    <h3>PHIẾU KHÁM SỨC KHỎE</h3>
                </div>
                
                <div class="title">THÔNG TIN CÁN BỘ</div>
                <table class="info-table">
                    <tr>
                        <th>Họ và tên:</th>
                        <td>{{ $appointment->user->full_name }}</td>
                    </tr>
                    <tr>
                        <th>Mã cán bộ:</th>
                        <td>{{ $appointment->user->staff_id }}</td>
                    </tr>
                    <tr>
                        <th>Đơn vị:</th>
                        <td>{{ $appointment->user->department }}</td>
                    </tr>
                    <tr>
                        <th>Chức vụ:</th>
                        <td>{{ $appointment->user->position }}</td>
                    </tr>
                </table>
                
                <div class="title">THÔNG TIN LỊCH KHÁM</div>
                <table class="info-table">
                    <tr>
                        <th>Mã lịch hẹn:</th>
                        <td>{{ substr($appointment->id, 0, 8) }}</td>
                    </tr>
                    <tr>
                        <th>Dịch vụ khám:</th>
                        <td>{{ $appointment->service->name }}</td>
                    </tr>
                    <tr>
                        <th>Ngày khám:</th>
                        <td>{{ $appointment->appointment_date->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Giờ khám:</th>
                        <td>{{ $appointment->timeSlot ? $appointment->timeSlot->start_time->format('H:i') . ' - ' . $appointment->timeSlot->end_time->format('H:i') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Bác sĩ phụ trách:</th>
                        <td>{{ $appointment->doctor ? $appointment->doctor->full_name : 'Chưa phân công' }}</td>
                    </tr>
                </table>
                
                <div class="footer">
                    <p>Ngày in: ${new Date().toLocaleDateString('vi-VN')}</p>
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
</script>
@endsection
