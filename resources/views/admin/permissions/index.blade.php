@extends('layouts.admin')

@section('title', 'Quản lý quyền')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Quản lý quyền</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tổng quan</a></li>
        <li class="breadcrumb-item active">Quản lý quyền</li>
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
            <i class="fas fa-table me-1"></i>
            Danh sách quyền
            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> Thêm quyền mới
            </a>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('admin.permissions.role-permissions') }}" class="btn btn-info">
                    <i class="fas fa-user-tag"></i> Phân quyền theo vai trò
                </a>
                <a href="{{ route('admin.permissions.user-permissions') }}" class="btn btn-info">
                    <i class="fas fa-user-cog"></i> Phân quyền theo người dùng
                </a>
            </div>

            @foreach($permissions as $group => $groupPermissions)
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">{{ ucfirst($group) }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tên</th>
                                    <th>Tên hiển thị</th>
                                    <th>Mô tả</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupPermissions as $permission)
                                <tr>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->display_name }}</td>
                                    <td>{{ $permission->description }}</td>
                                    <td>
                                        <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete('{{ $permission->id }}', '{{ $permission->display_name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $permission->id }}" 
                                              action="{{ route('admin.permissions.destroy', $permission->id) }}" 
                                              method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmDelete(id, name) {
        if (confirm('Bạn có chắc chắn muốn xóa quyền "' + name + '" không?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection
