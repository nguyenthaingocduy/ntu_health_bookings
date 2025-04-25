@extends('layouts.admin')

@section('title', 'Phân quyền cho người dùng')

@section('styles')
<style>
    .permission-group {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        margin-bottom: 1rem;
    }

    .permission-group-header {
        background-color: #f8f9fa;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #dee2e6;
        font-weight: bold;
        cursor: pointer;
    }

    .permission-group-body {
        padding: 1rem;
    }

    .permission-item {
        margin-bottom: 1rem;
    }

    .permission-search {
        margin-bottom: 1rem;
    }

    .permission-counter {
        background-color: #007bff;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        margin-bottom: 1rem;
    }

    .permission-actions {
        margin-bottom: 1rem;
    }

    .permission-table {
        width: 100%;
    }

    .permission-table th {
        text-align: center;
        font-weight: normal;
        font-size: 0.9rem;
    }

    .role-permission {
        background-color: #e9ecef;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.8rem;
        margin-top: 0.25rem;
        display: inline-block;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Phân quyền cho người dùng</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tổng quan</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Quản lý quyền</a></li>
        <li class="breadcrumb-item active">{{ $user->full_name }}</li>
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
            Phân quyền cho người dùng: {{ $user->full_name }} ({{ $user->email }})
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Người dùng này có vai trò <strong>{{ $user->role->name }}</strong>.
                Các quyền được gán cho vai trò này được hiển thị dưới dạng <span class="role-permission">quyền từ vai trò</span>.
                Bạn có thể gán thêm quyền riêng cho người dùng này.
            </div>

            <form action="{{ route('admin.permissions.role-permissions') }}" method="POST" id="user-permissions-form">
                @csrf
                @method('PUT')

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="permission-counter">
                        Tổng Routes: <span id="total-permissions">{{ $permissions->flatten()->count() }}</span> |
                        Đã chọn: <span id="selected-permissions">0</span>
                    </div>

                    <div class="permission-actions">
                        <button type="button" class="btn btn-secondary" id="select-all-btn" onclick="selectAll(); return false;">Chọn tất cả</button>
                        <button type="button" class="btn btn-secondary" id="deselect-all-btn" onclick="deselectAll(); return false;">Bỏ chọn</button>
                    </div>
                </div>

                <div class="permission-search mb-3">
                    <input type="text" class="form-control" id="permission-search"
                           placeholder="Tìm kiếm quyền..." aria-label="Tìm kiếm quyền">
                </div>

                @foreach($permissions as $group => $groupPermissions)
                <div class="permission-group" data-group="{{ $group }}">
                    <div class="permission-group-header d-flex justify-content-between align-items-center">
                        <span>{{ ucfirst($group) }}</span>
                        <span class="badge bg-primary">{{ $groupPermissions->count() }}</span>
                    </div>
                    <div class="permission-group-body">
                        <table class="permission-table">
                            <thead>
                                <tr>
                                    <th style="width: 40%">Quyền</th>
                                    <th style="width: 15%">Xem</th>
                                    <th style="width: 15%">Thêm</th>
                                    <th style="width: 15%">Sửa</th>
                                    <th style="width: 15%">Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupPermissions as $permission)
                                <tr class="permission-item" data-permission-name="{{ $permission->name }}">
                                    <td>
                                        <div>{{ $permission->display_name }}</div>
                                        @if(in_array($permission->id, $rolePermissions))
                                        <div class="role-permission">quyền từ vai trò</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                   name="permissions[{{ $permission->id }}][can_view]" value="1"
                                                   id="permission-{{ $permission->id }}-view"
                                                   {{ isset($userPermissions[$permission->id]) && $userPermissions[$permission->id]['can_view'] ? 'checked' : '' }}
                                                   {{ in_array($permission->id, $rolePermissions) ? 'disabled' : '' }}>
                                            <input type="hidden" name="permissions[{{ $permission->id }}][id]" value="{{ $permission->id }}">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                   name="permissions[{{ $permission->id }}][can_create]" value="1"
                                                   id="permission-{{ $permission->id }}-create"
                                                   {{ isset($userPermissions[$permission->id]) && $userPermissions[$permission->id]['can_create'] ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                   name="permissions[{{ $permission->id }}][can_edit]" value="1"
                                                   id="permission-{{ $permission->id }}-edit"
                                                   {{ isset($userPermissions[$permission->id]) && $userPermissions[$permission->id]['can_edit'] ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                   name="permissions[{{ $permission->id }}][can_delete]" value="1"
                                                   id="permission-{{ $permission->id }}-delete"
                                                   {{ isset($userPermissions[$permission->id]) && $userPermissions[$permission->id]['can_delete'] ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach

                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary me-2">Hủy</a>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Định nghĩa các hàm trong phạm vi toàn cục (window)
    window.selectAll = function() {
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox:not([disabled])');
        permissionCheckboxes.forEach(checkbox => {
            const item = checkbox.closest('.permission-item');
            if (!item || item.style.display !== 'none') {
                checkbox.checked = true;
            }
        });
        window.updateSelectedCount();
    };

    window.deselectAll = function() {
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox:not([disabled])');
        permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        window.updateSelectedCount();
    };

    window.updateSelectedCount = function() {
        const selectedCount = document.querySelectorAll('.permission-checkbox:checked:not([disabled])').length;
        const selectedPermissionsCounter = document.getElementById('selected-permissions');
        if (selectedPermissionsCounter) {
            selectedPermissionsCounter.textContent = selectedCount;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox:not([disabled])');
        const selectAllBtn = document.getElementById('select-all-btn');
        const deselectAllBtn = document.getElementById('deselect-all-btn');
        const selectedPermissionsCounter = document.getElementById('selected-permissions');
        const permissionSearch = document.getElementById('permission-search');
        const permissionGroups = document.querySelectorAll('.permission-group-header');

        // Khởi tạo số lượng
        window.updateSelectedCount();

        // Gắn sự kiện cho các nút
        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.selectAll();
                return false;
            });
        }

        if (deselectAllBtn) {
            deselectAllBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.deselectAll();
                return false;
            });
        }

        // Cập nhật số lượng khi thay đổi checkbox
        permissionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                window.updateSelectedCount();
            });
        });

        // Tìm kiếm quyền
        permissionSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            document.querySelectorAll('.permission-item').forEach(item => {
                const permissionName = item.getAttribute('data-permission-name').toLowerCase();
                const permissionLabel = item.querySelector('td:first-child').textContent.toLowerCase();

                if (permissionName.includes(searchTerm) || permissionLabel.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });

            // Hiển thị/ẩn nhóm dựa trên kết quả tìm kiếm
            document.querySelectorAll('.permission-group').forEach(group => {
                const visibleItems = group.querySelectorAll('.permission-item[style=""]').length;
                if (visibleItems === 0) {
                    group.style.display = 'none';
                } else {
                    group.style.display = '';
                }
            });
        });

        // Mở/đóng nhóm quyền
        permissionGroups.forEach(header => {
            header.addEventListener('click', function() {
                const body = this.nextElementSibling;
                body.style.display = body.style.display === 'none' ? '' : 'none';
            });
        });
    });
</script>
@endsection
