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
                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">Chọn vai trò <span class="text-red-500">*</span></label>
                    <select class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('role_id') border-red-500 @enderror" id="role_id" name="role_id" required>
                        <option value="">-- Chọn vai trò --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('role_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div id="permissions-container" class="hidden">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 space-y-4 md:space-y-0">
                        <div class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-sm">
                            <span class="font-medium">Tổng Routes:</span> <span id="total-permissions">{{ $permissions->flatten()->count() }}</span> | 
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
                                <span class="font-medium text-gray-700">{{ ucfirst($group) }}</span>
                                <span class="bg-blue-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $groupPermissions->count() }}</span>
                            </div>
                            <div class="permission-group-body p-4 bg-white">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($groupPermissions as $permission)
                                    <div class="permission-item" data-permission-name="{{ $permission->name }}">
                                        <label class="flex items-start space-x-3 cursor-pointer group">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" class="permission-checkbox w-4 h-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500" name="permissions[]" value="{{ $permission->id }}" id="permission-{{ $permission->id }}">
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-700 group-hover:text-pink-600">{{ $permission->display_name }}</span>
                                                @if($permission->description)
                                                <span class="text-xs text-gray-500">{{ $permission->description }}</span>
                                                @endif
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
            header.addEventListener('click', function() {
                const body = this.nextElementSibling;
                if (body.classList.contains('hidden')) {
                    body.classList.remove('hidden');
                } else {
                    body.classList.add('hidden');
                }
            });
        });
    });
</script>
@endsection
