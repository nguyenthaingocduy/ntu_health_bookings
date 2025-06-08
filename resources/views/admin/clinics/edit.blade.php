@extends('layouts.admin')

@section('title', 'Chỉnh sửa cơ sở')

@section('header', 'Chỉnh sửa cơ sở')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.clinics.update', $clinic->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Thông tin cơ bản</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tên cơ sở <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $clinic->name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $clinic->email) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $clinic->phone) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                        <option value="active" {{ old('status', $clinic->status) == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="inactive" {{ old('status', $clinic->status) == 'inactive' ? 'selected' : '' }}>Ngừng hoạt động</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Địa chỉ</h3>
            
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ đầy đủ <span class="text-red-500">*</span></label>
                <input type="text" name="address" id="address" value="{{ old('address', $clinic->address) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Mô tả</h3>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả chi tiết</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">{{ old('description', $clinic->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Hình ảnh</h3>
            
            <div>
                @if($clinic->image_url)
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Hình ảnh hiện tại</p>
                        <img src="{{ secure_asset($clinic->image_url) }}" alt="{{ $clinic->name }}" class="w-48 h-48 object-cover rounded">
                    </div>
                @endif
                
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Thay đổi hình ảnh</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                <p class="text-sm text-gray-500 mt-1">Chọn hình ảnh có kích thước tối đa 2MB, định dạng JPG, PNG, GIF. Để trống nếu không muốn thay đổi hình ảnh.</p>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.clinics.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Hủy bỏ
            </a>
            <button type="submit" class="px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                Cập nhật cơ sở
            </button>
        </div>
    </form>
</div>
@endsection
