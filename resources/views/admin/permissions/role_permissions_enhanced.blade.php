@extends('layouts.admin')

@section('title', 'Phân quyền theo vai trò')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Phân quyền theo vai trò</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý quyền truy cập cho từng vai trò trong hệ thống</p>
        </div>
        <a href="{{ route('admin.permissions.index') }}" class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-150 shadow-sm border border-gray-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Quay lại
        </a>
    </div>

    <nav class="mb-8">
        <ol class="flex text-sm text-gray-500">
            <li class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-pink-500">Tổng quan</a>
                <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </li>
            <li class="flex items-center">
                <a href="{{ route('admin.permissions.index') }}" class="hover:text-pink-500">Quản lý quyền</a>
                <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </li>
            <li class="text-pink-500 font-medium">Phân quyền theo vai trò</li>
        </ol>
    </nav>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <div class="bg-gradient-to-r from-pink-50 to-pink-100 px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                Phân quyền theo vai trò
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.permissions.update-role-permissions') }}" method="POST" id="role-permissions-form">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
                        <div class="flex">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p><span class="font-bold">Hướng dẫn:</span> Vui lòng chọn một vai trò từ danh sách bên dưới để xem và cấu hình quyền cho vai trò đó.</p>
                        </div>
                    </div>

                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">Chọn vai trò <span class="text-red-500">*</span></label>
                    <select class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('role_id') border-red-500 @enderror" id="role_id" name="role_id" required>
                        <option value="">-- Chọn vai trò --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }} - {{ $role->description }}
                        </option>
                        @endforeach
                    </select>
                    @error('role_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="permissions-container" class="hidden">
                    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p><span class="font-bold">Lưu ý:</span> Khi bạn cấp quyền cho một vai trò, tất cả người dùng có vai trò này sẽ được cấp các quyền tương ứng.</p>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 space-y-4 md:space-y-0">
                        <div class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-sm">
                            <span class="font-medium">Tổng quyền:</span> <span id="total-permissions">{{ $permissions->flatten()->count() }}</span> |
                            <span class="font-medium">Đã chọn:</span> <span id="selected-permissions">0</span>
                        </div>

                        <div class="flex space-x-3">
                            <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center" id="select-all-btn">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Chọn tất cả
                            </button>
                            <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center" id="deselect-all-btn">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Bỏ chọn
                            </button>
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50" id="permission-search" placeholder="Tìm kiếm quyền...">
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($permissions as $group => $groupPermissions)
                        <div class="permission-group border border-gray-200 rounded-lg overflow-hidden" data-group="{{ $group }}">
                            <div class="permission-group-header bg-gray-50 px-4 py-3 flex justify-between items-center cursor-pointer hover:bg-gray-100 transition-colors duration-150">
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-700">{{ ucfirst($group) }}</span>
                                    <span class="bg-blue-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full ml-2">{{ $groupPermissions->count() }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" class="select-group-btn text-xs px-2 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors duration-150">
                                        Chọn nhóm
                                    </button>
                                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="permission-group-body p-4 bg-white">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($groupPermissions as $permission)
                                    <div class="permission-item bg-gray-50 p-3 rounded-lg hover:bg-gray-100 transition-colors duration-150" data-permission-name="{{ $permission->name }}">
                                        <label class="flex items-start space-x-3 cursor-pointer group">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" class="permission-checkbox w-4 h-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500" name="permissions[]" value="{{ $permission->id }}" id="permission-{{ $permission->id }}">
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-700 group-hover:text-pink-600">{{ $permission->display_name }}</span>
                                                @if($permission->description)
                                                <span class="text-xs text-gray-500">{{ $permission->description }}</span>
                                                @endif
                                                <span class="text-xs text-blue-500 mt-1">{{ $permission->name }}</span>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end mt-8">
                        <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150 mr-4">
                            Hủy
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-lg hover:from-pink-600 hover:to-pink-700 transition-colors duration-150 shadow-md flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Lưu thay đổi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Phần giải thích về các quyền -->
    <div class="mt-8 bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                Hướng dẫn phân quyền
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Vai trò và quyền là gì?</h4>
                    <p class="text-gray-600">Vai trò là một nhóm các quyền được gán cho người dùng. Mỗi người dùng có thể có một vai trò, và mỗi vai trò có thể có nhiều quyền khác nhau.</p>
                </div>

                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Các vai trò mặc định</h4>
                    <ul class="list-disc pl-5 text-gray-600 space-y-1">
                        <li><span class="font-medium">Admin</span>: Có toàn quyền truy cập và quản lý hệ thống</li>
                        <li><span class="font-medium">Lễ tân</span>: Quản lý lịch hẹn, khách hàng và thanh toán</li>
                        <li><span class="font-medium">Kỹ thuật viên</span>: Thực hiện các dịch vụ và cập nhật trạng thái</li>
                        <li><span class="font-medium">Khách hàng</span>: Đặt lịch hẹn và xem thông tin cá nhân</li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Cách phân quyền hiệu quả</h4>
                    <ol class="list-decimal pl-5 text-gray-600 space-y-1">
                        <li>Chọn vai trò cần phân quyền từ danh sách</li>
                        <li>Xem xét kỹ các quyền cần thiết cho vai trò đó</li>
                        <li>Chỉ cấp những quyền thực sự cần thiết (nguyên tắc tối thiểu)</li>
                        <li>Kiểm tra lại các quyền đã cấp trước khi lưu</li>
                        <li>Thường xuyên rà soát và cập nhật quyền khi cần thiết</li>
                    </ol>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p><span class="font-bold">Lưu ý quan trọng:</span> Việc phân quyền không đúng có thể dẫn đến rủi ro bảo mật hoặc làm gián đoạn hoạt động của hệ thống. Hãy cẩn thận khi cấp quyền cho các vai trò.</p>
                    </div>
                </div>
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
        const permissionGroups = document.querySelectorAll('.permission-group-header');
        const selectGroupBtns = document.querySelectorAll('.select-group-btn');
        const rolePermissions = @json($rolePermissions);

        // Hiển thị hướng dẫn khi trang được tải
        if (!roleSelect.value) {
            // Hiển thị thông báo hướng dẫn
            const alertDiv = document.createElement('div');
            alertDiv.className = 'bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 mt-4';
            alertDiv.id = 'role-selection-guide';
            alertDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p><span class="font-bold">Bước tiếp theo:</span> Sau khi chọn vai trò, bạn sẽ thấy danh sách các quyền có thể cấp cho vai trò đó.</p>
                </div>
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
                permissionsContainer.classList.remove('hidden');

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
                permissionsContainer.classList.add('hidden');
            }
        });

        // Kiểm tra nếu đã chọn vai trò
        if (roleSelect.value) {
            permissionsContainer.classList.remove('hidden');

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
                if (getComputedStyle(item).display !== 'none') {
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
                const permissionLabel = item.querySelector('span').textContent.toLowerCase();

                if (permissionName.includes(searchTerm) || permissionLabel.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });

            // Hiển thị/ẩn nhóm dựa trên kết quả tìm kiếm
            document.querySelectorAll('.permission-group').forEach(group => {
                const visibleItems = Array.from(group.querySelectorAll('.permission-item')).filter(item =>
                    getComputedStyle(item).display !== 'none'
                ).length;

                if (visibleItems === 0) {
                    group.style.display = 'none';
                } else {
                    group.style.display = '';
                }
            });
        });

        // Mở/đóng nhóm quyền
        permissionGroups.forEach(header => {
            header.addEventListener('click', function(e) {
                // Không xử lý nếu click vào nút chọn nhóm
                if (e.target.classList.contains('select-group-btn') || e.target.closest('.select-group-btn')) {
                    return;
                }

                const body = this.nextElementSibling;
                const arrow = this.querySelector('svg');

                if (body.classList.contains('hidden')) {
                    body.classList.remove('hidden');
                    arrow.classList.remove('rotate-180');
                } else {
                    body.classList.add('hidden');
                    arrow.classList.add('rotate-180');
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
                this.classList.toggle('bg-green-100');
                this.classList.toggle('text-green-700');
                this.classList.toggle('bg-red-100');
                this.classList.toggle('text-red-700');

                updateSelectedCount();
            });
        });
    });
</script>
@endsection
