@extends('layouts.admin')

@section('title', 'Phân quyền theo vai trò')

@section('styles')
<style>
    /* Tailwind đã được sử dụng trong layout */
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Phân quyền theo vai trò</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tổng quan</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Quản lý quyền</a></li>
        <li class="breadcrumb-item active">Phân quyền theo vai trò</li>
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
            <i class="fas fa-user-tag me-1"></i>
            Phân quyền theo vai trò
        </div>
        <div class="card-body">
            <form action="{{ route('admin.permissions.update-role-permissions') }}" method="POST" id="role-permissions-form">
                @csrf
                @method('PUT')

                <div class="role-selector">
                    <label for="role_id" class="form-label">Chọn vai trò <span class="text-danger">*</span></label>
                    <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                        <option value="">-- Chọn vai trò --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="permissions-container" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="permission-counter">
                            Tổng Routes: <span id="total-permissions">{{ $permissions->flatten()->count() }}</span> |
                            Đã chọn: <span id="selected-permissions">0</span>
                        </div>

                        <div class="permission-actions">
                            <button type="button" class="btn btn-secondary" id="select-all-btn">Chọn tất cả</button>
                            <button type="button" class="btn btn-secondary" id="deselect-all-btn">Bỏ chọn</button>
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
                            <div class="row">
                                @foreach($groupPermissions as $permission)
                                <div class="col-md-4 permission-item" data-permission-name="{{ $permission->name }}">
                                    <div class="form-check">
                                        <input class="form-check-input permission-checkbox" type="checkbox"
                                               name="permissions[]" value="{{ $permission->id }}"
                                               id="permission-{{ $permission->id }}">
                                        <label class="form-check-label" for="permission-{{ $permission->id }}"
                                               title="{{ $permission->description }}">
                                            {{ $permission->display_name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary me-2">Hủy</a>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role_id');
        const permissionsContainer = document.getElementById('permissions-container');
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
        const selectAllBtn = document.getElementById('select-all-btn');
        const deselectAllBtn = document.getElementById('deselect-all-btn');
        const selectedPermissionsCounter = document.getElementById('selected-permissions');
        const permissionSearch = document.getElementById('permission-search');
        const permissionGroups = document.querySelectorAll('.permission-group-header');
        const rolePermissions = @json($rolePermissions);

        // Hiển thị quyền khi chọn vai trò
        roleSelect.addEventListener('change', function() {
            if (this.value) {
                permissionsContainer.style.display = 'block';

                // Đặt lại tất cả checkbox
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Chọn các quyền đã được gán cho vai trò
                if (rolePermissions[this.value]) {
                    const permissions = rolePermissions[this.value];
                    permissionCheckboxes.forEach(checkbox => {
                        if (permissions.includes(checkbox.value)) {
                            checkbox.checked = true;
                        }
                    });
                }

                updateSelectedCount();
            } else {
                permissionsContainer.style.display = 'none';
            }
        });

        // Kiểm tra nếu đã chọn vai trò
        if (roleSelect.value) {
            permissionsContainer.style.display = 'block';

            // Chọn các quyền đã được gán cho vai trò
            if (rolePermissions[roleSelect.value]) {
                const permissions = rolePermissions[roleSelect.value];
                permissionCheckboxes.forEach(checkbox => {
                    if (permissions.includes(checkbox.value)) {
                        checkbox.checked = true;
                    }
                });
            }

            updateSelectedCount();
        }

        // Cập nhật số lượng quyền đã chọn
        function updateSelectedCount() {
            const selectedCount = document.querySelectorAll('.permission-checkbox:checked').length;
            selectedPermissionsCounter.textContent = selectedCount;
        }

        // Chọn tất cả quyền
        selectAllBtn.addEventListener('click', function() {
            permissionCheckboxes.forEach(checkbox => {
                const item = checkbox.closest('.permission-item');
                if (item.style.display !== 'none') {
                    checkbox.checked = true;
                }
            });
            updateSelectedCount();
        });

        // Bỏ chọn tất cả quyền
        deselectAllBtn.addEventListener('click', function() {
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectedCount();
        });

        // Cập nhật số lượng khi thay đổi checkbox
        permissionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectedCount();
            });
        });

        // Tìm kiếm quyền
        permissionSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            document.querySelectorAll('.permission-item').forEach(item => {
                const permissionName = item.getAttribute('data-permission-name').toLowerCase();
                const permissionLabel = item.querySelector('.form-check-label').textContent.toLowerCase();

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
