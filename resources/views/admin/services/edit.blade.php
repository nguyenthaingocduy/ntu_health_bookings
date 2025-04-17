@extends('layouts.admin')

@section('title', 'Chỉnh sửa dịch vụ')

@section('header', 'Chỉnh sửa dịch vụ')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Thông tin cơ bản</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tên dịch vụ <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $service->name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $category)
                            @if($category->parent_id === null)
                                <option value="{{ $category->id }}" {{ old('category_id', $service->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @foreach($category->children as $childCategory)
                                    <option value="{{ $childCategory->id }}" {{ old('category_id', $service->category_id) == $childCategory->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;&nbsp;└─ {{ $childCategory->name }}
                                    </option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Giá (VNĐ) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">₫</span>
                        </div>
                        <input type="number" name="price" id="price" value="{{ old('price', $service->price) }}" required min="0"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="promotion" class="block text-sm font-medium text-gray-700 mb-2">Giảm giá (%)</label>
                    <div class="relative">
                        <input type="number" name="promotion" id="promotion" value="{{ old('promotion', $service->promotion) }}" min="0" max="100"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">%</span>
                        </div>
                    </div>
                    @error('promotion')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Thời gian (phút) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="duration" id="duration" value="{{ old('duration', $service->duration) }}" required min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">phút</span>
                        </div>
                    </div>
                    @error('duration')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Thông tin bổ sung</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="clinic_id" class="block text-sm font-medium text-gray-700 mb-2">Phòng khám <span class="text-red-500">*</span></label>
                    <select name="clinic_id" id="clinic_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <option value="">Chọn phòng khám</option>
                        @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}" {{ old('clinic_id', $service->clinic_id) == $clinic->id ? 'selected' : '' }}>
                                {{ $clinic->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('clinic_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <option value="active" {{ old('status', $service->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ old('status', $service->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
            <textarea name="description" id="description" rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">{{ old('description', $service->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Hình ảnh</h3>

            <div>
                @if($service->image_url)
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Hình ảnh hiện tại</p>
                        <img src="{{ asset($service->image_url) }}" alt="{{ $service->name }}" class="h-48 object-cover rounded-lg border border-gray-300">
                    </div>
                @endif

                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Thay đổi hình ảnh</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex flex-col text-sm text-gray-600">
                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-pink-600 hover:text-pink-500 focus-within:outline-none">
                                <span class="px-3 py-2 border border-gray-300 rounded-md hover:border-pink-500 transition-colors">Chọn hình ảnh mới</span>
                                <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                            </label>
                            <div id="imagePreview" class="mt-4 flex justify-center"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            PNG, JPG, GIF tối đa 2MB. Để trống nếu không muốn thay đổi hình ảnh.
                        </p>
                    </div>
                </div>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.services.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Hủy bỏ
            </a>
            <button type="submit" class="px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                Cập nhật dịch vụ
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Preview image before upload
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Check file size
                const fileSize = file.size / 1024 / 1024; // in MB
                if (fileSize > 2) {
                    alert('Hình ảnh quá lớn. Vui lòng chọn hình ảnh nhỏ hơn 2MB.');
                    this.value = ''; // Clear the input
                    imagePreview.innerHTML = '';
                    return;
                }

                // Preview image
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.innerHTML = `
                        <div class="relative group">
                            <img src="${event.target.result}" class="rounded-lg max-h-40 border border-gray-300" />
                            <button type="button" class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 transform translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity" id="removeImage">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    `;

                    // Add event listener to remove button
                    document.getElementById('removeImage').addEventListener('click', function() {
                        imageInput.value = '';
                        imagePreview.innerHTML = '';
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
