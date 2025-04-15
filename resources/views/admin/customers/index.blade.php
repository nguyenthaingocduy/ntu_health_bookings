@extends('layouts.admin')

@section('title', 'Quản lý khách hàng')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý khách hàng</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách khách hàng</h6>
            <div>
                <form action="{{ route('admin.customers.index') }}" method="GET" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Tìm kiếm khách hàng..." aria-label="Search" aria-describedby="basic-addon2" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Ngày đăng ký</th>
                            <th>Lịch hẹn</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="bg-gray-200 rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-user text-gray-500"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                                        <div class="small text-gray-500">{{ $customer->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $appointmentCount = $customer->appointments()->count();
                                    $completedCount = $customer->appointments()->where('status', 'completed')->count();
                                @endphp
                                <div class="d-flex align-items-center">
                                    <span class="mr-2">{{ $appointmentCount }} lịch hẹn</span>
                                    @if($appointmentCount > 0)
                                        <div class="progress" style="width: 100px; height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($completedCount / $appointmentCount) * 100 }}%" 
                                                aria-valuenow="{{ $completedCount }}" aria-valuemin="0" aria-valuemax="{{ $appointmentCount }}"></div>
                                        </div>
                                        <span class="ml-2 small text-gray-600">{{ $completedCount }} hoàn thành</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Chi tiết
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Không có khách hàng nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
