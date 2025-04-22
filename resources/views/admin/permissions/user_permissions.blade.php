@extends('layouts.admin')

@section('title', 'Phân quyền người dùng')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Phân quyền người dùng</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tổng quan</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Quản lý quyền</a></li>
        <li class="breadcrumb-item active">Phân quyền người dùng</li>
    </ol>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-cog me-1"></i>
            Danh sách người dùng
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="users-table">
                    <thead>
                        <tr>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role ? $user->role->name : 'Không có vai trò' }}</td>
                            <td>
                                <a href="{{ route('admin.permissions.edit-user-permissions', $user->id) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Phân quyền
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#users-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json'
            },
            responsive: true
        });
    });
</script>
@endsection
