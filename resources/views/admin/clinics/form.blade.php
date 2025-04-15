@extends('layouts.admin')

@section('title', $clinic ? 'Chỉnh sửa cơ sở' : 'Thêm cơ sở mới')

@section('header', $clinic ? 'Chỉnh sửa cơ sở' : 'Thêm cơ sở mới')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ $clinic ? route('admin.clinics.update', $clinic) : route('admin.clinics.store') }}" 
        method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($clinic)
            @method('PUT')
        @endif
        
        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Tên cơ sở</label>
                <input type="text" name="name" id="name" value="{{ old('name', $clinic->name ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"
                    required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone', $clinic->phone ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"
                    required>
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                <input type="text" name="address" id="address" value="{{ old('address', $clinic->address ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"
                    required>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
                <textarea name="description" id="description" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"
                    required>{{ old('description', $clinic->description ?? '') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Image -->
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Hình ảnh</label>
            <input type="file" name="image" id="image" accept="image/*"
                class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-pink-50 file:text-pink-700
                    hover:file:bg-pink-100"
                {{ $clinic ? '' : 'required' }}>
            @error('image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            
            @if($clinic && $clinic->image_url)
                <div class="mt-2">
                    <img src="{{ $clinic->image_url }}" alt="{{ $clinic->name }}" 
                        class="h-32 w-32 object-cover rounded-lg">
                </div>
            @endif
        </div>
        
        <!-- Services -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Dịch vụ cung cấp</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($services as $service)
                <label class="inline-flex items-center">
                    <input type="checkbox" name="services[]" value="{{ $service->id }}"
                        {{ in_array($service->id, old('services', $clinic ? $clinic->services->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-pink-500 focus:ring-pink-500">
                    <span class="ml-2">{{ $service->name }}</span>
                </label>
                @endforeach
            </div>
            @error('services')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Status -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
            <div class="mt-2 space-x-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="status" value="active"
                        {{ old('status', $clinic->status ?? 'active') == 'active' ? 'checked' : '' }}
                        class="text-pink-500 focus:ring-pink-500">
                    <span class="ml-2">Đang hoạt động</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="status" value="inactive"
                        {{ old('status', $clinic->status ?? '') == 'inactive' ? 'checked' : '' }}
                        class="text-pink-500 focus:ring-pink-500">
                    <span class="ml-2">Ngừng hoạt động</span>
                </label>
            </div>
            @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.clinics.index') }}" 
                class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                Hủy
            </a>
            <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition">
                {{ $clinic ? 'Cập nhật' : 'Thêm mới' }}
            </button>
        </div>
    </form>
</div>
@endsection 