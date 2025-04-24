@extends('layouts.admin')

@section('title', 'Chỉnh sửa quyền')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chỉnh sửa quyền</h1>
            <p class="text-sm text-gray-500 mt-1">Cập nhật thông tin quyền</p>
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
            <li class="text-pink-500 font-medium">Chỉnh sửa quyền</li>
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-pink-50 to-pink-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Thông tin quyền
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tên quyền <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name', $permission->name) }}" required placeholder="Ví dụ: users.view">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Tên quyền nên theo định dạng: resource.action (ví dụ: users.view, users.create)</p>
                        </div>
                        
                        <div class="mb-6">
                            <label for="display_name" class="block text-sm font-medium text-gray-700 mb-2">Tên hiển thị <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('display_name') border-red-500 @enderror" id="display_name" name="display_name" value="{{ old('display_name', $permission->display_name) }}" required placeholder="Ví dụ: Xem người dùng">
                            @error('display_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="group" class="block text-sm font-medium text-gray-700 mb-2">Nhóm quyền <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('group') border-red-500 @enderror" id="group" name="group" required>
                                    <option value="">Chọn nhóm quyền</option>
                                    @foreach($groups as $group)
                                    <option value="{{ $group }}" {{ old('group', $permission->group) == $group ? 'selected' : '' }}>{{ $group }}</option>
                                    @endforeach
                                    <option value="new">+ Thêm nhóm mới</option>
                                </select>
                            </div>
                            <div id="new-group-container" class="mt-3 hidden">
                                <input type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50" id="new_group" name="new_group" placeholder="Nhập tên nhóm mới">
                            </div>
                            @error('group')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                            <textarea class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('description') border-red-500 @enderror" id="description" name="description" rows="4" placeholder="Mô tả chi tiết về quyền này">{{ old('description', $permission->description) }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex justify-end mt-8">
                            <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150 mr-4">
                                Hủy
                            </a>
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-lg hover:from-pink-600 hover:to-pink-700 transition-colors duration-150 shadow-md flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                Cập nhật
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
                        Thông tin bổ sung
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa quyền này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg transition-colors duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Xóa quyền
                            </button>
                        </form>
                    </div>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 mt-4">
                        <p class="font-medium">Lưu ý:</p>
                        <ul class="list-disc list-inside mt-2">
                            <li>Thay đổi tên quyền có thể ảnh hưởng đến mã kiểm tra quyền.</li>
                            <li>Xóa quyền sẽ xóa tất cả các liên kết với vai trò và người dùng.</li>
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
    document.addEventListener('DOMContentLoaded', function() {
        const groupSelect = document.getElementById('group');
        const newGroupContainer = document.getElementById('new-group-container');
        
        groupSelect.addEventListener('change', function() {
            if (this.value === 'new') {
                newGroupContainer.classList.remove('hidden');
                document.getElementById('new_group').focus();
            } else {
                newGroupContainer.classList.add('hidden');
            }
        });
    });
</script>
@endsection
