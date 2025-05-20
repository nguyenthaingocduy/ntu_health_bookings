@extends('layouts.admin')

@section('title', 'Phân quyền theo vai trò - Ma trận')

@section('styles')
<style>
    .role-permission-cell:hover {
        background-color: #f3f4f6;
    }

    .role-permission-cell.active {
        background-color: #fce7f3;
    }

    .sticky-header {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: white;
    }

    .sticky-first-col {
        position: sticky;
        left: 0;
        z-index: 5;
        background-color: white;
    }

    .permission-group-header {
        background-color: #f9fafb;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Phân quyền theo vai trò</h1>
            <p class="text-sm text-gray-500 mt-1">Phân quyền cho các vai trò trong hệ thống</p>
        </div>
        <div class="flex space-x-2">
            {{-- <a href="{{ route('admin.permissions.role-permissions') }}" class="flex items-center px-4 py-2 bg-pink-100 text-pink-700 rounded-lg hover:bg-pink-200 transition-colors duration-150 shadow-sm border border-pink-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                Chế độ danh sách
            </a> --}}
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
                    <path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z" clip-rule="evenodd"></path>
                </svg>
             Phân quyền theo vai trò
            </h3>
        </div>
        <div class="p-6">
            <div class="mb-6">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" id="permissionSearch" placeholder="Tìm kiếm quyền..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                        <svg class="w-5 h-5 text-gray-400 absolute right-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    {{-- <div class="relative">
                        <select id="groupFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                            <option value="">Tất cả nhóm</option>
                            @foreach($permissions->keys() as $group)
                            <option value="{{ $group }}">{{ $group }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                </div>
            </div>

            <div class="overflow-x-auto">
                <form action="{{ route('admin.permissions.update-role-permissions-matrix') }}" method="POST" id="role-permissions-form">
                    @csrf
                    @method('PUT')

                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky-header sticky-first-col border-r">
                                    Quyền / Vai trò
                                </th>
                                @foreach($roles as $role)
                                <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider sticky-header border-r">
                                    {{ $role->name }}
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($permissions as $group => $groupPermissions)
                            <tr class="permission-group-header" data-group="{{ $group }}">
                                <td colspan="{{ count($roles) + 1 }}" class="px-6 py-3 text-left text-sm font-medium text-gray-900 bg-gray-100">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                        </svg>
                                        {{ $groupPermissions[0]->translated_group ?? $group }}
                                    </div>
                                </td>
                            </tr>
                            @foreach($groupPermissions as $permission)
                            <tr class="permission-row" data-permission-name="{{ $permission->name }}" data-permission-display-name="{{ $permission->display_name }}" data-group="{{ $group }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky-first-col border-r">
                                    <div>
                                        <div class="font-medium">{{ $permission->display_name ?: ucfirst(str_replace('.', ' ', $permission->name)) }}</div>
                                        <div class="text-xs text-gray-500">{{ $permission->name }}</div>
                                    </div>
                                </td>
                                @foreach($roles as $role)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center border-r role-permission-cell {{ in_array($permission->id, $rolePermissions[$role->id] ?? []) ? 'active' : '' }}" data-role="{{ $role->id }}" data-permission="{{ $permission->id }}">
                                    <label class="inline-flex items-center justify-center cursor-pointer">
                                        <input type="checkbox" class="form-checkbox h-5 w-5 text-pink-600 rounded border-gray-300 focus:ring-pink-500" name="matrix[{{ $role->id }}][{{ $permission->id }}]" value="1" {{ in_array($permission->id, $rolePermissions[$role->id] ?? []) ? 'checked' : '' }}>
                                    </label>
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>

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
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chức năng tìm kiếm
        const permissionSearch = document.getElementById('permissionSearch');
        const permissionRows = document.querySelectorAll('.permission-row');

        permissionSearch.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();

            permissionRows.forEach(function(row) {
                const permissionName = row.dataset.permissionName.toLowerCase();
                const permissionDisplayName = row.dataset.permissionDisplayName.toLowerCase();

                if (permissionName.includes(searchValue) || permissionDisplayName.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Ẩn các nhóm trống
            document.querySelectorAll('.permission-group-header').forEach(function(groupHeader) {
                const group = groupHeader.dataset.group;
                const visibleRows = document.querySelectorAll(`.permission-row[data-group="${group}"]:not([style="display: none;"])`).length;

                if (visibleRows === 0) {
                    groupHeader.style.display = 'none';
                } else {
                    groupHeader.style.display = '';
                }
            });
        });

        // Bộ lọc nhóm
        const groupFilter = document.getElementById('groupFilter');

        groupFilter.addEventListener('change', function() {
            const selectedGroup = this.value;

            if (selectedGroup === '') {
                // Hiển thị tất cả các nhóm
                document.querySelectorAll('.permission-group-header, .permission-row').forEach(function(el) {
                    el.style.display = '';
                });
            } else {
                // Chỉ hiển thị nhóm được chọn
                document.querySelectorAll('.permission-group-header').forEach(function(groupHeader) {
                    const group = groupHeader.dataset.group;
                    groupHeader.style.display = (group === selectedGroup) ? '' : 'none';
                });

                document.querySelectorAll('.permission-row').forEach(function(row) {
                    const group = row.dataset.group;
                    row.style.display = (group === selectedGroup) ? '' : 'none';
                });
            }
        });

        // Làm nổi bật ô khi di chuột qua
        document.querySelectorAll('.role-permission-cell').forEach(function(cell) {
            cell.addEventListener('mouseenter', function() {
                this.classList.add('bg-gray-100');
            });

            cell.addEventListener('mouseleave', function() {
                this.classList.remove('bg-gray-100');
            });
        });

        // Chuyển đổi checkbox khi nhấp vào ô
        document.querySelectorAll('.role-permission-cell').forEach(function(cell) {
            cell.addEventListener('click', function(e) {
                if (e.target.tagName !== 'INPUT') {
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;

                    // Chuyển đổi lớp active
                    this.classList.toggle('active', checkbox.checked);
                }
            });
        });

        // Cập nhật lớp active khi checkbox thay đổi
        document.querySelectorAll('.role-permission-cell input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const cell = this.closest('.role-permission-cell');
                cell.classList.toggle('active', this.checked);
            });
        });
    });
</script>
@endsection
