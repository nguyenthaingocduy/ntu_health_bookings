@extends('layouts.admin')
{{-- Debug info --}}
@php
    \Illuminate\Support\Facades\Log::info('Rendering role_permissions_tailwind.blade.php');
@endphp

@section('title', 'Phân quyền theo vai trò')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Phân quyền theo vai trò</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý quyền truy cập cho từng vai trò trong hệ thống</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.permissions.role-permissions-matrix') }}" class="flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-150 shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                Chế độ ma trận
            </a>
            <a href="{{ route('admin.permissions.index') }}" class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-150 shadow-sm border border-gray-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
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
                    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-4">
                        <div class="flex">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="font-bold mb-1">Hướng dẫn sử dụng:</p>
                                <ol class="list-decimal pl-5 space-y-1">
                                    <li>Chọn vai trò từ danh sách bên dưới</li>
                                    <li>Danh sách quyền sẽ hiển thị với các quyền đã được gán cho vai trò đó</li>
                                    <li>Chọn hoặc bỏ chọn các quyền theo nhu cầu</li>
                                    <li>Nhấn "Lưu thay đổi" để cập nhật quyền cho vai trò</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">Chọn vai trò <span class="text-red-500">*</span></label>
                    <select class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('role_id') border-red-500 @enderror" id="role_id" name="role_id" required>
                        <option value="">-- Chọn vai trò --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ (old('role_id') == $role->id || (isset($selectedRoleId) && $selectedRoleId == $role->id)) ? 'selected' : '' }}>
                            {{ $role->name }} - {{ $role->description ?? '' }}
                        </option>
                        @endforeach
                    </select>
                    @error('role_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="permissions-container">
                    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p><span class="font-bold">Lưu ý:</span> Khi bạn cấp quyền cho một vai trò, tất cả người dùng có vai trò này sẽ được cấp các quyền tương ứng.</p>
                        </div>
                    </div>

                    <div class="hidden bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert" id="selected-role-info">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="font-bold">Đang phân quyền cho vai trò: <span id="selected-role-title" class="font-medium"></span></p>
                                <p class="text-sm mt-1">Bạn có thể chọn hoặc bỏ chọn các quyền bên dưới để cấu hình quyền cho vai trò này.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 space-y-4 md:space-y-0">
                        <div class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-sm">
                            <span class="font-medium">Tổng quyền:</span> <span id="total-permissions">{{ $permissions->flatten()->count() }}</span> |
                            <span class="font-medium">Đã chọn:</span> <span id="selected-permissions">0</span>
                        </div>

                        <div class="flex space-x-3">
                            <button type="button" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-150 flex items-center shadow-sm" id="select-all-btn">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Chọn tất cả
                            </button>
                            <button type="button" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-150 flex items-center shadow-sm" id="deselect-all-btn">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Bỏ chọn tất cả
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
                                    <button type="button" class="select-group-btn text-xs px-2 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors duration-150" id="select-group-btn-{{ $group }}">
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
                                                <span class="text-sm font-medium text-gray-700 group-hover:text-pink-600">{{ $permission->display_name ?: ucfirst(str_replace('.', ' ', $permission->name)) }}</span>
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
                        <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150 mr-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
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
</div>
@endsection

@section('scripts')
<script type="text/javascript">
// Thêm debug helper
function addClickDebugger(selector, name) {
    document.querySelectorAll(selector).forEach(function(el) {
        el.addEventListener('click', function() {
            console.log(name + ' clicked via event listener');
        });

        // Thêm inline onclick attribute
        el.setAttribute('onclick', "console.log('" + name + " clicked via onclick attribute'); return true;");
    });
}

// Thêm debug khi trang đã tải
window.addEventListener('load', function() {
    console.log('Window loaded - adding debug helpers');
    addClickDebugger('#select-all-btn', 'Select all button');
    addClickDebugger('#deselect-all-btn', 'Deselect all button');
    addClickDebugger('.select-group-btn', 'Group button');
});

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded');

    // Lấy các phần tử DOM
    const roleSelect = document.getElementById('role_id');
    console.log('Role select:', roleSelect);

    const permissionsContainer = document.getElementById('permissions-container');
    console.log('Permissions container:', permissionsContainer);

    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
    console.log('Permission checkboxes:', permissionCheckboxes.length);

    const selectAllBtn = document.getElementById('select-all-btn');
    console.log('Select all button:', selectAllBtn);

    const deselectAllBtn = document.getElementById('deselect-all-btn');
    console.log('Deselect all button:', deselectAllBtn);

    const selectedPermissionsCounter = document.getElementById('selected-permissions');
    console.log('Selected permissions counter:', selectedPermissionsCounter);

    const permissionSearch = document.getElementById('permission-search');
    console.log('Permission search:', permissionSearch);

    const permissionGroups = document.querySelectorAll('.permission-group-header');
    console.log('Permission groups:', permissionGroups.length);

    const selectGroupBtns = document.querySelectorAll('.select-group-btn');
    console.log('Select group buttons:', selectGroupBtns.length);

    const rolePermissions = @json($rolePermissions);
    console.log('Role permissions:', Object.keys(rolePermissions).length);

    // Đảm bảo vai trò được chọn khi trang được tải
    if (!roleSelect.value && roleSelect.options.length > 1) {
        // Chọn vai trò đầu tiên mặc định
        roleSelect.selectedIndex = 1; // Chọn tùy chọn đầu tiên (không phải "-- Chọn vai trò --")
    }

    // Hiển thị quyền cho vai trò đã chọn ngay khi trang được tải
    if (roleSelect.value) {
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
        updateGroupSelectButtons();

        // Hiển thị tên vai trò đã chọn
        const selectedRoleName = roleSelect.options[roleSelect.selectedIndex].text;
        const roleTitle = document.getElementById('selected-role-title');
        const roleInfo = document.getElementById('selected-role-info');
        if (roleTitle) {
            roleTitle.textContent = selectedRoleName;
            roleInfo.classList.remove('hidden');
        }
    } else {
        // Hiển thị thông báo hướng dẫn nếu không có vai trò nào được chọn
        const alertDiv = document.createElement('div');
        alertDiv.className = 'bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 mt-4';
        alertDiv.id = 'role-selection-guide';
        alertDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p><span class="font-bold">Bước tiếp theo:</span> Vui lòng chọn một vai trò từ danh sách để xem và cấu hình quyền.</p>
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
            updateGroupSelectButtons();

            // Hiển thị tên vai trò đã chọn
            const selectedRoleName = roleSelect.options[roleSelect.selectedIndex].text;
            const roleTitle = document.getElementById('selected-role-title');
            const roleInfo = document.getElementById('selected-role-info');
            if (roleTitle) {
                roleTitle.textContent = selectedRoleName;
                roleInfo.classList.remove('hidden');
            }
        } else {
            permissionsContainer.classList.add('hidden');
            const roleInfo = document.getElementById('selected-role-info');
            if (roleInfo) {
                roleInfo.classList.add('hidden');
            }
        }
    });

    // Mở tất cả các nhóm quyền khi trang được tải
    document.querySelectorAll('.permission-group-body').forEach(body => {
        // Mặc định hiển thị tất cả các nhóm
        body.classList.remove('hidden');
    });

    // Cập nhật số lượng quyền đã chọn
    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('.permission-checkbox:checked').length;
        selectedPermissionsCounter.textContent = selectedCount;

        // Cập nhật màu sắc dựa trên số lượng quyền đã chọn
        const totalCount = permissionCheckboxes.length;
        const percentSelected = (selectedCount / totalCount) * 100;

        const counterElement = selectedPermissionsCounter.closest('div');

        // Đổi màu dựa trên phần trăm quyền đã chọn
        if (percentSelected === 0) {
            counterElement.className = 'bg-gray-500 text-white px-4 py-2 rounded-lg shadow-sm';
        } else if (percentSelected < 30) {
            counterElement.className = 'bg-blue-500 text-white px-4 py-2 rounded-lg shadow-sm';
        } else if (percentSelected < 70) {
            counterElement.className = 'bg-green-500 text-white px-4 py-2 rounded-lg shadow-sm';
        } else {
            counterElement.className = 'bg-pink-500 text-white px-4 py-2 rounded-lg shadow-sm';
        }
    }

    // Chọn tất cả quyền - Sử dụng cách tiếp cận đơn giản nhất
    const selectAllButton = document.getElementById('select-all-btn');
    if (selectAllButton) {
        selectAllButton.onclick = function() {
            console.log('Select all button clicked (simplest)');

            // Lấy tất cả các checkbox hiện tại
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            console.log('Found ' + checkboxes.length + ' checkboxes');

            // Chọn tất cả
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });

            updateSelectedCount();
            updateGroupSelectButtons();

            return false; // Ngăn chặn hành vi mặc định
        };
    }

    // Bỏ chọn tất cả quyền - Sử dụng cách tiếp cận đơn giản nhất
    const deselectAllButton = document.getElementById('deselect-all-btn');
    if (deselectAllButton) {
        deselectAllButton.onclick = function() {
            console.log('Deselect all button clicked (simplest)');

            // Lấy tất cả các checkbox hiện tại
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            console.log('Found ' + checkboxes.length + ' checkboxes');

            // Bỏ chọn tất cả
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });

            updateSelectedCount();
            updateGroupSelectButtons();

            return false; // Ngăn chặn hành vi mặc định
        };
    }

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



    // Cập nhật trạng thái nút chọn nhóm
    function updateGroupSelectButtons() {
        console.log('Updating group select buttons');

        const groups = document.querySelectorAll('.permission-group');
        console.log('Found ' + groups.length + ' permission groups');

        groups.forEach(group => {
            const checkboxes = group.querySelectorAll('.permission-checkbox');
            const selectBtn = group.querySelector('.select-group-btn');

            if (!checkboxes.length) {
                console.log('No checkboxes found in group');
                return;
            }

            if (!selectBtn) {
                console.log('No select button found in group');
                return;
            }

            // Kiểm tra xem tất cả các checkbox đã được chọn chưa
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);

            // Cập nhật văn bản và lớp CSS của nút
            if (allChecked) {
                selectBtn.textContent = 'Bỏ chọn nhóm';
                selectBtn.classList.remove('bg-green-100', 'text-green-700');
                selectBtn.classList.add('bg-red-100', 'text-red-700');
            } else {
                selectBtn.textContent = 'Chọn nhóm';
                selectBtn.classList.remove('bg-red-100', 'text-red-700');
                selectBtn.classList.add('bg-green-100', 'text-green-700');
            }
        });
    }

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

    // Chọn tất cả quyền trong một nhóm - Sử dụng cách tiếp cận đơn giản nhất
    // Đăng ký sự kiện click cho tất cả các nút chọn nhóm
    const groupButtons = document.querySelectorAll('.select-group-btn');
    console.log('Setting up ' + groupButtons.length + ' group buttons');

    groupButtons.forEach(function(btn) {
        // Sử dụng thuộc tính onclick trực tiếp
        btn.onclick = function() {
            console.log('Group button clicked (simplest)');

            // Tìm nhóm chứa nút
            const group = this.closest('.permission-group');
            if (!group) {
                console.error('Group not found');
                return false;
            }

            // Tìm tất cả các checkbox trong nhóm
            const checkboxes = group.querySelectorAll('.permission-checkbox');
            console.log('Found ' + checkboxes.length + ' checkboxes in group');

            if (checkboxes.length === 0) {
                console.error('No checkboxes found in group');
                return false;
            }

            // Kiểm tra xem tất cả các checkbox đã được chọn chưa
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);

            // Đảo ngược trạng thái của tất cả các checkbox
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = !allChecked;
            });

            // Cập nhật văn bản và lớp CSS của nút
            this.textContent = allChecked ? 'Chọn nhóm' : 'Bỏ chọn nhóm';

            if (allChecked) {
                this.classList.remove('bg-red-100', 'text-red-700');
                this.classList.add('bg-green-100', 'text-green-700');
            } else {
                this.classList.remove('bg-green-100', 'text-green-700');
                this.classList.add('bg-red-100', 'text-red-700');
            }

            // Cập nhật số lượng quyền đã chọn và trạng thái nút
            updateSelectedCount();
            updateGroupSelectButtons();

            return false; // Ngăn chặn hành vi mặc định
        };
    });

    // Cập nhật trạng thái nút khi thay đổi checkbox
    permissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            updateGroupSelectButtons();
        });
    });
});
</script>
@endsection
