@extends('layouts.admin')

@section('title', 'Chi tiết hồ sơ sức khỏe')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết hồ sơ sức khỏe</h1>
        <div>
            @if($healthRecord->appointment)
            <a href="{{ route('admin.health-checkups.record.form', $healthRecord->appointment->id) }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Chỉnh sửa
            </a>
            @endif
            <a href="{{ route('admin.health-checkups.records') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
            </a>
            <button onclick="printRecord()" class="btn btn-sm btn-success shadow-sm">
                <i class="fas fa-print fa-sm text-white-50"></i> In hồ sơ
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin cán bộ</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Họ và tên:</th>
                            <td>{{ $healthRecord->user->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Mã cán bộ:</th>
                            <td>{{ $healthRecord->user->staff_id }}</td>
                        </tr>
                        <tr>
                            <th>Đơn vị:</th>
                            <td>{{ $healthRecord->user->department }}</td>
                        </tr>
                        <tr>
                            <th>Chức vụ:</th>
                            <td>{{ $healthRecord->user->position }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $healthRecord->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Số điện thoại:</th>
                            <td>{{ $healthRecord->user->phone }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin khám</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Mã hồ sơ:</th>
                            <td>{{ substr($healthRecord->id, 0, 8) }}</td>
                        </tr>
                        <tr>
                            <th>Ngày khám:</th>
                            <td>{{ $healthRecord->check_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Dịch vụ khám:</th>
                            <td>{{ $healthRecord->appointment && $healthRecord->appointment->service ? $healthRecord->appointment->service->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Bác sĩ phụ trách:</th>
                            <td>{{ $healthRecord->appointment && $healthRecord->appointment->doctor ? $healthRecord->appointment->doctor->full_name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Ngày tái khám:</th>
                            <td>{{ $healthRecord->next_check_date ? $healthRecord->next_check_date->format('d/m/Y') : 'Không có' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Chỉ số sức khỏe</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Chiều cao:</th>
                            <td>{{ $healthRecord->height ? $healthRecord->height . ' cm' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Cân nặng:</th>
                            <td>{{ $healthRecord->weight ? $healthRecord->weight . ' kg' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>BMI:</th>
                            <td>
                                @if($healthRecord->height && $healthRecord->weight)
                                    @php
                                        $heightInMeters = $healthRecord->height / 100;
                                        $bmi = $healthRecord->weight / ($heightInMeters * $heightInMeters);
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
                                    {{ number_format($bmi, 2) }} ({{ $bmiCategory }})
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Huyết áp:</th>
                            <td>{{ $healthRecord->blood_pressure ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Nhịp tim:</th>
                            <td>{{ $healthRecord->heart_rate ? $healthRecord->heart_rate . ' bpm' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Nhóm máu:</th>
                            <td>{{ $healthRecord->blood_type ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin bổ sung</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Dị ứng:</h6>
                        <p>{{ $healthRecord->allergies ?? 'Không có' }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Lịch sử bệnh:</h6>
                        <p>{{ $healthRecord->medical_history ?? 'Không có' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Kết quả khám</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <h6 class="font-weight-bold">Chẩn đoán:</h6>
                    <p>{{ $healthRecord->diagnosis ?? 'Không có chẩn đoán' }}</p>
                </div>
                
                <div class="col-md-12 mb-4">
                    <h6 class="font-weight-bold">Khuyến nghị:</h6>
                    <p>{{ $healthRecord->recommendations ?? 'Không có khuyến nghị' }}</p>
                </div>
                
                <div class="col-md-12">
                    <h6 class="font-weight-bold">Ghi chú của bác sĩ:</h6>
                    <p>{{ $healthRecord->doctor_notes ?? 'Không có ghi chú' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function printRecord() {
        // Create a new window for printing
        const printWindow = window.open('', '_blank');
        
        // Write the HTML content to the new window
        printWindow.document.write(`
            <html>
            <head>
                <title>Hồ sơ sức khỏe - {{ $healthRecord->user->full_name }}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; margin-bottom: 30px; }
                    .section { margin-bottom: 20px; }
                    .section-title { font-size: 16px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
                    .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    .info-table th { text-align: left; width: 30%; padding: 8px; vertical-align: top; }
                    .info-table td { padding: 8px; vertical-align: top; }
                    .footer { margin-top: 50px; text-align: right; }
                    .row { display: flex; }
                    .col { flex: 1; padding: 0 10px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>TRƯỜNG ĐẠI HỌC NHA TRANG</h2>
                    <h3>HỒ SƠ SỨC KHỎE</h3>
                </div>
                
                <div class="row">
                    <div class="col">
                        <div class="section">
                            <div class="section-title">THÔNG TIN CÁN BỘ</div>
                            <table class="info-table">
                                <tr>
                                    <th>Họ và tên:</th>
                                    <td>{{ $healthRecord->user->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>Mã cán bộ:</th>
                                    <td>{{ $healthRecord->user->staff_id }}</td>
                                </tr>
                                <tr>
                                    <th>Đơn vị:</th>
                                    <td>{{ $healthRecord->user->department }}</td>
                                </tr>
                                <tr>
                                    <th>Chức vụ:</th>
                                    <td>{{ $healthRecord->user->position }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="col">
                        <div class="section">
                            <div class="section-title">THÔNG TIN KHÁM</div>
                            <table class="info-table">
                                <tr>
                                    <th>Mã hồ sơ:</th>
                                    <td>{{ substr($healthRecord->id, 0, 8) }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày khám:</th>
                                    <td>{{ $healthRecord->check_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Dịch vụ khám:</th>
                                    <td>{{ $healthRecord->appointment && $healthRecord->appointment->service ? $healthRecord->appointment->service->name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Bác sĩ phụ trách:</th>
                                    <td>{{ $healthRecord->appointment && $healthRecord->appointment->doctor ? $healthRecord->appointment->doctor->full_name : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <div class="section">
                            <div class="section-title">CHỈ SỐ SỨC KHỎE</div>
                            <table class="info-table">
                                <tr>
                                    <th>Chiều cao:</th>
                                    <td>{{ $healthRecord->height ? $healthRecord->height . ' cm' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Cân nặng:</th>
                                    <td>{{ $healthRecord->weight ? $healthRecord->weight . ' kg' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>BMI:</th>
                                    <td>
                                        @if($healthRecord->height && $healthRecord->weight)
                                            @php
                                                $heightInMeters = $healthRecord->height / 100;
                                                $bmi = $healthRecord->weight / ($heightInMeters * $heightInMeters);
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
                                            {{ number_format($bmi, 2) }} ({{ $bmiCategory }})
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Huyết áp:</th>
                                    <td>{{ $healthRecord->blood_pressure ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Nhịp tim:</th>
                                    <td>{{ $healthRecord->heart_rate ? $healthRecord->heart_rate . ' bpm' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Nhóm máu:</th>
                                    <td>{{ $healthRecord->blood_type ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="col">
                        <div class="section">
                            <div class="section-title">THÔNG TIN BỔ SUNG</div>
                            <table class="info-table">
                                <tr>
                                    <th>Dị ứng:</th>
                                    <td>{{ $healthRecord->allergies ?? 'Không có' }}</td>
                                </tr>
                                <tr>
                                    <th>Lịch sử bệnh:</th>
                                    <td>{{ $healthRecord->medical_history ?? 'Không có' }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày tái khám:</th>
                                    <td>{{ $healthRecord->next_check_date ? $healthRecord->next_check_date->format('d/m/Y') : 'Không có' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">KẾT QUẢ KHÁM</div>
                    <table class="info-table">
                        <tr>
                            <th>Chẩn đoán:</th>
                            <td>{{ $healthRecord->diagnosis ?? 'Không có chẩn đoán' }}</td>
                        </tr>
                        <tr>
                            <th>Khuyến nghị:</th>
                            <td>{{ $healthRecord->recommendations ?? 'Không có khuyến nghị' }}</td>
                        </tr>
                        <tr>
                            <th>Ghi chú của bác sĩ:</th>
                            <td>{{ $healthRecord->doctor_notes ?? 'Không có ghi chú' }}</td>
                        </tr>
                    </table>
                </div>
                
                <div class="footer">
                    <p>Ngày in: ${new Date().toLocaleDateString('vi-VN')}</p>
                    <p>Người in: {{ auth()->user()->full_name }}</p>
                    <div style="margin-top: 30px;">
                        <p>Bác sĩ ký tên</p>
                        <div style="height: 60px;"></div>
                        <p>{{ $healthRecord->appointment && $healthRecord->appointment->doctor ? $healthRecord->appointment->doctor->full_name : '' }}</p>
                    </div>
                </div>
            </body>
            </html>
        `);
        
        // Close the document for writing
        printWindow.document.close();
        
        // Print the document
        setTimeout(function() {
            printWindow.print();
        }, 500);
    }
</script>
@endsection
