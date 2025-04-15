@extends('layouts.admin')

@section('title', 'Chi tiết khách hàng')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết khách hàng</h1>
        <a href="{{ route('admin.customers.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <!-- Customer Information -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin khách hàng</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="mx-auto bg-gray-200 rounded-circle mb-3" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user fa-3x text-gray-500"></i>
                        </div>
                        <h5 class="font-weight-bold">{{ $customer->first_name }} {{ $customer->last_name }}</h5>
                        <p class="text-gray-600">Khách hàng</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="font-weight-bold">Thông tin liên hệ</h6>
                        <div class="mb-2">
                            <i class="fas fa-envelope mr-2 text-gray-500"></i>
                            <span>{{ $customer->email }}</span>
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-phone mr-2 text-gray-500"></i>
                            <span>{{ $customer->phone }}</span>
                        </div>
                        @if($customer->address)
                        <div class="mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-gray-500"></i>
                            <span>{{ $customer->address }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h6 class="font-weight-bold">Thông tin tài khoản</h6>
                        <div class="mb-2">
                            <span class="text-gray-600">Ngày đăng ký:</span>
                            <span>{{ $customer->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-gray-600">Trạng thái:</span>
                            <span class="badge badge-{{ $customer->status == 'active' ? 'success' : 'danger' }}">
                                {{ $customer->status == 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Tổng lịch hẹn</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Hoàn thành</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['completed'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Đã xác nhận</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['confirmed'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Chờ xác nhận</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['pending'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments History -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lịch sử đặt lịch</h6>
                </div>
                <div class="card-body">
                    @if(count($appointments) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Mã đặt lịch</th>
                                        <th>Dịch vụ</th>
                                        <th>Ngày & Giờ</th>
                                        <th>Nhân viên</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->code }}</td>
                                        <td>
                                            <div class="font-weight-bold">{{ $appointment->service->name }}</div>
                                            <div class="small text-gray-500">{{ number_format($appointment->service->price, 0, ',', '.') }}đ</div>
                                        </td>
                                        <td>
                                            <div>{{ $appointment->date_appointments->format('d/m/Y') }}</div>
                                            <div class="small text-gray-500">{{ optional($appointment->timeAppointment)->started_time }}</div>
                                        </td>
                                        <td>
                                            @if($appointment->employee)
                                                {{ $appointment->employee->first_name }} {{ $appointment->employee->last_name }}
                                            @else
                                                <span class="text-gray-500">Chưa phân công</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ 
                                                $appointment->status == 'pending' ? 'warning' : 
                                                ($appointment->status == 'confirmed' ? 'primary' : 
                                                ($appointment->status == 'completed' ? 'success' : 'danger')) 
                                            }}">
                                                {{ $appointment->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn btn-info btn-sm">
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
                            <i class="fas fa-calendar-times fa-3x text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Khách hàng chưa có lịch hẹn nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
