@extends('layouts.admin')

@section('title', $employee ? 'Chỉnh sửa nhân viên' : 'Thêm nhân viên mới')

@section('header', $employee ? 'Chỉnh sửa nhân viên' : 'Thêm nhân viên mới')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ $employee ? route('admin.employees.update', $employee) : route('admin.employees.store') }}" 
        method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($employee)
            @method('PUT')
        @endif
        
        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Họ và tên</label>
                <input type="text" name="name" id="name" value="{{ old('name', $employee->name ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"
                    required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $employee->email ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"
                    required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone', $employee->phone ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"
                    required>
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="clinic_id" class="block text-sm font-medium text-gray-700">Cơ sở làm việc</label>
                <select name="clinic_id" id="clinic_id" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"
                    required>
                    <option value="">Chọn cơ sở</option>
                    @foreach($clinics as $clinic)
                        <option value="{{ $clinic->id }}" 
                            {{ old('clinic_id', $employee->clinic_id ?? '') == $clinic->id ? 'selected' : '' }}>
                            {{ $clinic->name }}
                        </option>
                    @endforeach
                </select>
                @error('clinic_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Services -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Dịch vụ có thể thực hiện</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($services as $service)
                <label class="inline-flex items-center">
                    <input type="checkbox" name="services[]" value="{{ $service->id }}"
                        {{ in_array($service->id, old('services', $employee ? $employee->services->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-pink-500 focus:ring-pink-500">
                    <span class="ml-2">{{ $service->name }}</span>
                </label>
                @endforeach
            </div>
            @error('services')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Avatar -->
        <div>
            <label for="avatar" class="block text-sm font-medium text-gray-700">Ảnh đại diện</label>
            <input type="file" name="avatar" id="avatar" accept="image/*"
                class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-pink-50 file:text-pink-700
                    hover:file:bg-pink-100"
                {{ $employee ? '' : 'required' }}>
            @error('avatar')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            
            @if($employee && $employee->avatar_url)
                <div class="mt-2">
                    <img src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}" 
                        class="h-32 w-32 rounded-full object-cover">
                </div>
            @endif
        </div>
        
        <!-- Status -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
            <div class="mt-2 space-x-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="status" value="active"
                        {{ old('status', $employee->status ?? 'active') == 'active' ? 'checked' : '' }}
                        class="text-pink-500 focus:ring-pink-500">
                    <span class="ml-2">Đang làm việc</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="status" value="inactive"
                        {{ old('status', $employee->status ?? '') == 'inactive' ? 'checked' : '' }}
                        class="text-pink-500 focus:ring-pink-500">
                    <span class="ml-2">Đã nghỉ việc</span>
                </label>
            </div>
            @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.employees.index') }}" 
                class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                Hủy
            </a>
            <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition">
                {{ $employee ? 'Cập nhật' : 'Thêm mới' }}
            </button>
        </div>
    </form>
</div>
@endsection 