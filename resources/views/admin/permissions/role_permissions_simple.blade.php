@extends('layouts.admin')

@section('title', 'Phân quyền theo vai trò')

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
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Vui lòng chọn một vai trò từ danh sách bên dưới để xem và cấu hình quyền cho vai trò đó.
            </div>

            <form action="{{ route('admin.permissions.update-role-permissions') }}" method="POST" id="role-permissions-form">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="role_id" class="form-label">Chọn vai trò <span class="text-danger">*</span></label>
                    <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                        <option value="">-- Chọn vai trò --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }} - {{ $role->description }}
                        </option>
                        @endforeach
                    </select>
                    @error('role_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div id="permissions-container" class="d-none">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Khi bạn cấp quyền cho một vai trò, tất cả người dùng có vai trò này sẽ được cấp các quyền tương ứng.
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="permission-counter">
                            Tổng quyền: <span id="total-permissions">{{ $permissions->flatten()->count() }}</span> |
                            Đã chọn: <span id="selected-permissions">0</span>
                        </div>

                        <div class="permission-actions">
                            <button type="button" class="btn btn-secondary" id="select-all-btn">
                                <i class="fas fa-check-square"></i> Chọn tất cả
                            </button>
                            <button type="button" class="btn btn-secondary" id="deselect-all-btn">
                                <i class="fas fa-square"></i> Bỏ chọn
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <input type="text" class="form-control" id="permission-search" placeholder="Tìm kiếm quyền...">
                    </div>

                    <div class="accordion" id="permissionsAccordion">
                        @foreach($permissions as $group => $groupPermissions)
                        <div class="accordion-item permission-group" data-group="{{ $group }}">
                            <h2 class="accordion-header" id="heading{{ Str::slug($group) }}">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ Str::slug($group) }}" aria-expanded="true" aria-controls="collapse{{ Str::slug($group) }}">
                                    {{ ucfirst($group) }} <span class="badge bg-primary ms-2">{{ $groupPermissions->count() }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary ms-auto select-group-btn">
                                        Chọn nhóm
                                    </button>
                                </button>
                            </h2>
                            <div id="collapse{{ Str::slug($group) }}" class="accordion-collapse collapse show" aria-labelledby="heading{{ Str::slug($group) }}" data-bs-parent="#permissionsAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        @foreach($groupPermissions as $permission)
                                        <div class="col-md-4 mb-2 permission-item" data-permission-name="{{ $permission->name }}">
                                            <div class="form-check">
                                                <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission-{{ $permission->id }}">
                                                <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                    <strong>{{ $permission->display_name }}</strong>
                                                    @if($permission->description)
                                                    <div class="text-muted small">{{ $permission->description }}</div>
                                                    @endif
                                                    <div class="text-primary small">{{ $permission->name }}</div>
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-info-circle me-1"></i>
            Hướng dẫn phân quyền
        </div>
        <div class="card-body">
            <h5>Vai trò và quyền là gì?</h5>
            <p>Vai trò là một nhóm các quyền được gán cho người dùng. Mỗi người dùng có thể có một vai trò, và mỗi vai trò có thể có nhiều quyền khác nhau.</p>

            <h5>Các vai trò mặc định</h5>
            <ul>
                <li><strong>Admin:</strong> Có toàn quyền truy cập và quản lý hệ thống</li>
                <li><strong>Lễ tân:</strong> Quản lý lịch hẹn, khách hàng và thanh toán</li>
                <li><strong>Kỹ thuật viên:</strong> Thực hiện các dịch vụ và cập nhật trạng thái</li>
                <li><strong>Khách hàng:</strong> Đặt lịch hẹn và xem thông tin cá nhân</li>
            </ul>

            <h5>Cách phân quyền hiệu quả</h5>
            <ol>
                <li>Chọn vai trò cần phân quyền từ danh sách</li>
                <li>Xem xét kỹ các quyền cần thiết cho vai trò đó</li>
                <li>Chỉ cấp những quyền thực sự cần thiết (nguyên tắc tối thiểu)</li>
                <li>Kiểm tra lại các quyền đã cấp trước khi lưu</li>
                <li>Thường xuyên rà soát và cập nhật quyền khi cần thiết</li>
            </ol>

            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Lưu ý quan trọng:</strong> Việc phân quyền không đúng có thể dẫn đến rủi ro bảo mật hoặc làm gián đoạn hoạt động của hệ thống. Hãy cẩn thận khi cấp quyền cho các vai trò.
            </div>
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
        const selectGroupBtns = document.querySelectorAll('.select-group-btn');
        const rolePermissions = @json($rolePermissions);

        // Hiển thị hướng dẫn khi trang được tải
        if (!roleSelect.value) {
            // Hiển thị thông báo hướng dẫn
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-primary mt-3';
            alertDiv.id = 'role-selection-guide';
            alertDiv.innerHTML = `
                <i class="fas fa-info-circle"></i>
                <strong>Bước tiếp theo:</strong> Sau khi chọn vai trò, bạn sẽ thấy danh sách các quyền có thể cấp cho vai trò đó.
            `;
            
            // Chèn thông báo sau select box
            roleSelect.parentNode.insertAdjacentElement('afterend', alertDiv);
        }

        // Hiển thị quyền khi chọn vai trò
        roleSelect.addEventListener('change', function() {
            // Xóa thông báo hướng dẫn nếu có
            const guideElement = document.getElementById('role-selection-guide');
            if (guideElement) {
                guideElement.remove();
            }
            
            if (this.value) {
                permissionsContainer.classList.remove('d-none');

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
                permissionsContainer.classList.add('d-none');
            }
        });

        // Kiểm tra nếu đã chọn vai trò
        if (roleSelect.value) {
            permissionsContainer.classList.remove('d-none');

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
                if (window.getComputedStyle(item).display !== 'none') {
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
                const permissionLabel = item.querySelector('label').textContent.toLowerCase();

                if (permissionName.includes(searchTerm) || permissionLabel.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });

            // Hiển thị/ẩn nhóm dựa trên kết quả tìm kiếm
            document.querySelectorAll('.permission-group').forEach(group => {
                const visibleItems = Array.from(group.querySelectorAll('.permission-item')).filter(item => 
                    window.getComputedStyle(item).display !== 'none'
                ).length;
                
                if (visibleItems === 0) {
                    group.style.display = 'none';
                } else {
                    group.style.display = '';
                }
            });
        });
        
        // Chọn tất cả quyền trong một nhóm
        selectGroupBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                
                const group = this.closest('.permission-group');
                const checkboxes = group.querySelectorAll('.permission-checkbox');
                
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                
                checkboxes.forEach(checkbox => {
                    checkbox.checked = !allChecked;
                });
                
                this.textContent = allChecked ? 'Chọn nhóm' : 'Bỏ chọn nhóm';
                
                updateSelectedCount();
            });
        });
    });
</script>
@endsection
