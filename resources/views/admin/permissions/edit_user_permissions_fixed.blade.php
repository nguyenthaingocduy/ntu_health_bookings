@extends('layouts.admin')

@section('title', 'Phân quyền cho người dùng')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Phân quyền cho người dùng</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý quyền của người dùng: {{ $user->full_name }}</p>
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
                    <form action="{{ route('admin.permissions.update-user-permissions', $user->id) }}" method="POST" id="permissionsForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-lg font-semibold text-gray-700">Danh sách quyền</h4>
                                <div class="flex space-x-2">
                                    <button type="button" onclick="document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) { checkbox.checked = true; });" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-150 text-sm">
                                        Chọn tất cả
                                    </button>
                                    <button type="button" onclick="document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) { checkbox.checked = false; });" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-150 text-sm">
                                        Bỏ chọn tất cả
                                    </button>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tên quyền
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nhóm
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Xem
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Thêm
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Sửa
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Xóa
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($permissions as $group => $groupPermissions)
                                            @foreach($groupPermissions as $permission)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">{{ $permission->display_name }}</div>
                                                            <div class="text-xs text-gray-500">{{ $permission->name }}</div>
                                                        </div>
                                                        @if(in_array($permission->id, $rolePermissions))
                                                        <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Vai trò</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-lg">{{ $permission->translated_group ?? $permission->group }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[{{ $loop->parent->index * 100 + $loop->index }}][can_view]" value="1" class="permission-checkbox w-5 h-5 text-pink-600 border-gray-300 rounded focus:ring-pink-500"
                                                        {{ isset($userPermissions[$permission->id]) && $userPermissions[$permission->id]['can_view'] ? 'checked' : '' }}>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[{{ $loop->parent->index * 100 + $loop->index }}][can_create]" value="1" class="permission-checkbox w-5 h-5 text-pink-600 border-gray-300 rounded focus:ring-pink-500"
                                                        {{ isset($userPermissions[$permission->id]) && $userPermissions[$permission->id]['can_create'] ? 'checked' : '' }}>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[{{ $loop->parent->index * 100 + $loop->index }}][can_edit]" value="1" class="permission-checkbox w-5 h-5 text-pink-600 border-gray-300 rounded focus:ring-pink-500"
                                                        {{ isset($userPermissions[$permission->id]) && $userPermissions[$permission->id]['can_edit'] ? 'checked' : '' }}>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="permissions[{{ $loop->parent->index * 100 + $loop->index }}][can_delete]" value="1" class="permission-checkbox w-5 h-5 text-pink-600 border-gray-300 rounded focus:ring-pink-500"
                                                        {{ isset($userPermissions[$permission->id]) && $userPermissions[$permission->id]['can_delete'] ? 'checked' : '' }}>
                                                </td>
                                                <input type="hidden" name="permissions[{{ $loop->parent->index * 100 + $loop->index }}][id]" value="{{ $permission->id }}">
                                            </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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


