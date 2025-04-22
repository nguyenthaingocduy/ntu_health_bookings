@extends('layouts.admin')

@section('title', 'Quản lý vai trò')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Quản lý vai trò</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tổng quan</a></li>
        <li class="breadcrumb-item active">Quản lý vai trò</li>
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
            Danh sách vai trò
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> Thêm vai trò mới
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="roles-table">
                    <thead>
                        <tr>
                            <th>Tên vai trò</th>
                            <th>Mô tả</th>
                            <th>Số người dùng</th>
                            <th>Số quyền</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->description }}</td>
                            <td>{{ $role->users_count }}</td>
                            <td>{{ $role->permissions_count }}</td>
                            <td>
                                <a href="{{ route('admin.permissions.role-permissions', ['role_id' => $role->id]) }}" 
                                   class="btn btn-info btn-sm" title="Phân quyền">
                                    <i class="fas fa-key"></i>
                                </a>
                                <a href="{{ route('admin.roles.edit', $role->id) }}" 
                                   class="btn btn-primary btn-sm" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(!in_array($role->name, ['Admin', 'Nhân viên', 'Khách hàng']))
                                <button type="button" class="btn btn-danger btn-sm" 
                                        onclick="confirmDelete('{{ $role->id }}', '{{ $role->name }}')" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $role->id }}" 
                                      action="{{ route('admin.roles.destroy', $role->id) }}" 
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endif
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
        $('#roles-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json'
            },
            responsive: true
        });
    });
    
    function confirmDelete(id, name) {
        if (confirm('Bạn có chắc chắn muốn xóa vai trò "' + name + '" không?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection
