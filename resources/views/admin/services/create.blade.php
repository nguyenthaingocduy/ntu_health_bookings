@extends('layouts.admin')

@section('title', 'Thêm dịch vụ mới')

@section('content')


<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Thêm dịch vụ mới</h1>
        <a href="{{ route('admin.services.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-300 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    Vui lòng kiểm tra lại thông tin bên dưới
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-pink-500 from-pink-500 to-purple-600 px-6 py-4">
            <h2 class="text-white text-lg font-semibold">Thông tin dịch vụ</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên dịch vụ <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2 border border-gray-300  rounded-lg focus:ring-2 focus:ring-gray-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Danh mục <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 @error('category_id') border-red-500 @enderror">
                            <option value="">Chọn danh mục</option>
                            @foreach($categories as $category)
                                @if($category->parent_id === null)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @foreach($category->children as $childCategory)
                                        <option value="{{ $childCategory->id }}" {{ old('category_id') == $childCategory->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;└─ {{ $childCategory->name }}
                                        </option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Giá (VNĐ) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₫</span>
                            </div>
                            <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 @error('price') border-red-500 @enderror">
                        </div>
                        @error('price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="promotion" class="block text-sm font-medium text-gray-700 mb-1">Giảm giá (%)</label>
                        <div class="relative">
                            <input type="number" id="promotion" name="promotion" value="{{ old('promotion', 0) }}" min="0" max="100"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 @error('promotion') border-red-500 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                        @error('promotion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Thời gian (phút) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" id="duration" name="duration" value="{{ old('duration') }}" required min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 @error('duration') border-red-500 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">phút</span>
                            </div>
                        </div>
                        @error('duration')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="clinic_id" class="block text-sm font-medium text-gray-700 mb-1">Phòng khám <span class="text-red-500">*</span></label>
                        <select id="clinic_id" name="clinic_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 @error('clinic_id') border-red-500 @enderror">
                            <option value="">Chọn phòng khám</option>
                            @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}" {{ old('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                    {{ $clinic->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('clinic_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái <span class="text-red-500">*</span></label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="active" class="h-4 w-4 text-gray-600 focus:ring-gray-500" {{ old('status', 'active') == 'active' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Hoạt động</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="inactive" class="h-4 w-4 text-gray-600 focus:ring-gray-500" {{ old('status') == 'inactive' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Không hoạt động</span>
                            </label>
                        </div>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 border-gray-300 rounded-lg focus:ring-2  focus:ring-gray-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Hình ảnh</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex flex-col text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-pink-600 hover:text-pink-500 focus-within:outline-none">
                                    <span class="px-3 py-2 border border-gray-300 rounded-md hover:border-pink-500 transition-colors">Chọn hình ảnh</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                </label>
                                <div id="imagePreview" class="mt-4 flex justify-center"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                PNG, JPG, GIF tối đa 2MB
                            </p>
                        </div>
                    </div>
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.services.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="px-6 py-2 bg-pink-500 from-pink-500 to-purple-600 text-white rounded-lg hover:opacity-90 transition duration-300">
                        <i class="fas fa-save mr-2"></i>Lưu dịch vụ
                    </button>
                </div>
            </form>
        </div>
    </div>
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
