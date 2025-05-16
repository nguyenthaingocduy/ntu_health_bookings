@extends('layouts.admin')

@section('title', 'Phân quyền cho người dùng')

@section('styles')
<style>
    .permission-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .permission-card.active {
        border-color: #f56565;
    }

    .permission-checkbox:checked + label {
        background-color: #fed7e2;
        border-color: #f56565;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Phân quyền cho người dùng</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý quyền của người dùng: {{ $user->full_name }}</p>
        </div>
        <div class="flex space-x-2">
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
            <li class="flex items-center">
                <a href="{{ route('admin.permissions.index') }}" class="hover:text-pink-500">Quản lý quyền</a>
                <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </li>
            <li class="text-pink-500 font-medium">{{ $user->full_name }}</li>
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

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="md:col-span-3">
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-pink-50 to-pink-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Quyền của người dùng
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.permissions.role-permissions') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <input type="text" id="searchInput" placeholder="Tìm kiếm quyền..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">

                                <div class="relative">
                                    <select id="groupFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                        <option value="">Tất cả nhóm</option>
                                        @foreach($permissions->keys() as $group)
                                        <option value="{{ $group }}">{{ $group }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="selectAll()" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-150 text-sm">
                                    Chọn tất cả
                                </button>
                                <button type="button" onclick="deselectAll()" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-150 text-sm">
                                    Bỏ chọn tất cả
                                </button>
                            </div>
                        </div>

                        @foreach($permissions as $group => $groupPermissions)
                        <div class="mb-8 permission-group" data-group="{{ $group }}">
                            <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                                {{ $group }}
                                <button type="button" class="ml-2 px-2 py-1 bg-pink-100 text-pink-700 rounded-lg hover:bg-pink-200 transition-colors duration-150 text-xs select-group-btn" data-group="{{ $group }}">
                                    Chọn nhóm
                                </button>
                                <button type="button" class="ml-2 px-2 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-150 text-xs deselect-group-btn" data-group="{{ $group }}">
                                    Bỏ chọn nhóm
                                </button>
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($groupPermissions as $permission)
                                <div class="permission-card border rounded-lg p-4 transition-all duration-150 permission-item" data-name="{{ $permission->name }}" data-display-name="{{ $permission->display_name }}">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h5 class="font-medium text-gray-800">{{ $permission->display_name }}</h5>
                                            <p class="text-xs text-gray-500">{{ $permission->name }}</p>
                                        </div>
                                        @if(in_array($permission->id, $rolePermissions))
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Vai trò</span>
                                        @endif
                                    </div>

                                    <p class="text-sm text-gray-600 mb-4 h-12 overflow-hidden">
                                        {{ $permission->description ?: 'Không có mô tả' }}
                                    </p>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="can_view_{{ $permission->id }}" name="permissions[{{ $permission->id }}][can_view]" value="1" class="permission-checkbox hidden"
                                                {{ isset($userPermissions[$permission->id]) && $userPermissions[$permission->id]['can_view'] ? 'checked' : '' }}>
                                            <label for="can_view_{{ $permission->id }}" class="flex items-center justify-center w-full px-2 py-1 border border-gray-300 rounded-lg cursor-pointer text-sm transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Xem
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" id="can_create_{{ $permission->id }}" name="permissions[{{ $permission->id }}][can_create]" value="1" class="permission-checkbox hidden"
                                                {{ isset($userPermissions[$permission->id]) && $userPermissions[$permission->id]['can_create'] ? 'checked' : '' }}>
                                            <label for="can_create_{{ $permission->id }}" class="flex items-center justify-center w-full px-2 py-1 border border-gray-300 rounded-lg cursor-pointer text-sm transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Thêm
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" id="can_edit_{{ $permission->id }}" name="permissions[{{ $permission->id }}][can_edit]" value="1" class="permission-checkbox hidden"
                                                {{ isset($userPermissions[$permission->id]) && $userPermissions[$permission->id]['can_edit'] ? 'checked' : '' }}>
                                            <label for="can_edit_{{ $permission->id }}" class="flex items-center justify-center w-full px-2 py-1 border border-gray-300 rounded-lg cursor-pointer text-sm transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Sửa
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" id="can_delete_{{ $permission->id }}" name="permissions[{{ $permission->id }}][can_delete]" value="1" class="permission-checkbox hidden"
                                                {{ isset($userPermissions[$permission->id]) && $userPermissions[$permission->id]['can_delete'] ? 'checked' : '' }}>
                                            <label for="can_delete_{{ $permission->id }}" class="flex items-center justify-center w-full px-2 py-1 border border-gray-300 rounded-lg cursor-pointer text-sm transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Xóa
                                            </label>
                                        </div>
                                    </div>

                                    <input type="hidden" name="permissions[{{ $permission->id }}][id]" value="{{ $permission->id }}">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

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
                    </form>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 sticky top-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Thông tin người dùng
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                            <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-900">{{ $user->full_name }}</h4>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h5 class="text-sm font-medium text-gray-500 uppercase mb-2">Thông tin</h5>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="mb-3">
                                <p class="text-sm text-gray-500">Vai trò</p>
                                <p class="text-base font-medium text-gray-800">{{ $user->role->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Số quyền riêng</p>
                                <p class="text-base font-medium text-gray-800">{{ count($userPermissions) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 mt-4">
                        <p class="font-medium">Lưu ý:</p>
                        <ul class="list-disc list-inside mt-2 text-sm">
                            <li>Quyền riêng của người dùng sẽ ghi đè quyền của vai trò.</li>
                            <li>Các quyền được đánh dấu "Vai trò" đã được cấp thông qua vai trò của người dùng.</li>
                            <li>Bạn có thể ghi đè quyền của vai trò bằng cách đặt quyền riêng cho người dùng.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Định nghĩa các hàm trong phạm vi toàn cục
    function selectAll() {
        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
            if (checkbox.closest('.permission-item').style.display !== 'none') {
                checkbox.checked = true;
            }
        });
        updateCardHighlights();
    }

    function deselectAll() {
        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
            checkbox.checked = false;
        });
        updateCardHighlights();
    }

    // Hàm cập nhật làm nổi bật thẻ với quyền đã chọn
    function updateCardHighlights() {
        document.querySelectorAll('.permission-item').forEach(function(card) {
            const checkboxes = card.querySelectorAll('.permission-checkbox');
            let hasChecked = false;

            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    hasChecked = true;
                }
            });

            if (hasChecked) {
                card.classList.add('active');
            } else {
                card.classList.remove('active');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Chức năng tìm kiếm
        const searchInput = document.getElementById('searchInput');
        const permissionItems = document.querySelectorAll('.permission-item');

        searchInput.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();

            permissionItems.forEach(function(item) {
                const name = item.dataset.name.toLowerCase();
                const displayName = item.dataset.displayName.toLowerCase();

                if (name.includes(searchValue) || displayName.includes(searchValue)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });

            // Ẩn các nhóm trống
            document.querySelectorAll('.permission-group').forEach(function(group) {
                const visibleItems = group.querySelectorAll('.permission-item[style=""]').length;
                group.style.display = visibleItems > 0 ? '' : 'none';
            });
        });

        // Bộ lọc nhóm
        const groupFilter = document.getElementById('groupFilter');

        groupFilter.addEventListener('change', function() {
            const selectedGroup = this.value;

            document.querySelectorAll('.permission-group').forEach(function(group) {
                if (!selectedGroup || group.dataset.group === selectedGroup) {
                    group.style.display = '';
                } else {
                    group.style.display = 'none';
                }
            });
        });

        // Các nút chọn/bỏ chọn tất cả đã được xử lý bằng các hàm selectAll() và deselectAll()

        // Chọn/bỏ chọn nhóm
        document.querySelectorAll('.select-group-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const group = this.dataset.group;
                const groupElement = document.querySelector(`.permission-group[data-group="${group}"]`);

                groupElement.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                    if (checkbox.closest('.permission-item').style.display !== 'none') {
                        checkbox.checked = true;
                    }
                });
                updateCardHighlights();
            });
        });

        document.querySelectorAll('.deselect-group-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const group = this.dataset.group;
                const groupElement = document.querySelector(`.permission-group[data-group="${group}"]`);

                groupElement.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                    if (checkbox.closest('.permission-item').style.display !== 'none') {
                        checkbox.checked = false;
                    }
                });
                updateCardHighlights();
            });
        });

        // Làm nổi bật ban đầu
        updateCardHighlights();

        // Cập nhật làm nổi bật khi checkbox thay đổi
        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                updateCardHighlights();
            });
        });
    });
</script>
@endsection
