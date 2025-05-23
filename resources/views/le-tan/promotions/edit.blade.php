@extends('layouts.le-tan')

@section('header', 'Chỉnh sửa khuyến mãi')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Chỉnh sửa khuyến mãi</h2>
                <p class="text-sm text-gray-600 mt-1">Cập nhật thông tin chương trình khuyến mãi</p>
            </div>
            <a href="{{ route('le-tan.promotions.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="p-6">
        <form action="{{ route('le-tan.promotions.update', $promotion->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Mã khuyến mãi -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Mã khuyến mãi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="code" 
                           name="code" 
                           value="{{ old('code', $promotion->code) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('code') border-red-500 @enderror"
                           placeholder="VD: SUMMER2025"
                           required>
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Loại giảm giá -->
                <div>
                    <label for="discount_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Loại giảm giá <span class="text-red-500">*</span>
                    </label>
                    <select id="discount_type" 
                            name="discount_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('discount_type') border-red-500 @enderror"
                            required>
                        <option value="">Chọn loại giảm giá</option>
                        <option value="percentage" {{ old('discount_type', $promotion->discount_type) == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                        <option value="fixed" {{ old('discount_type', $promotion->discount_type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
                    </select>
                    @error('discount_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Giá trị giảm -->
                <div>
                    <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-2">
                        Giá trị giảm <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="discount_value" 
                           name="discount_value" 
                           value="{{ old('discount_value', $promotion->discount_value) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('discount_value') border-red-500 @enderror"
                           placeholder="VD: 20 hoặc 50000"
                           min="0"
                           step="0.01"
                           required>
                    @error('discount_value')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Giới hạn sử dụng -->
                <div>
                    <label for="usage_limit" class="block text-sm font-medium text-gray-700 mb-2">
                        Giới hạn sử dụng
                    </label>
                    <input type="number" 
                           id="usage_limit" 
                           name="usage_limit" 
                           value="{{ old('usage_limit', $promotion->usage_limit) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('usage_limit') border-red-500 @enderror"
                           placeholder="Để trống = không giới hạn"
                           min="1">
                    @error('usage_limit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Để trống nếu không muốn giới hạn số lần sử dụng</p>
                </div>

                <!-- Ngày bắt đầu -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Ngày bắt đầu <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="start_date" 
                           name="start_date" 
                           value="{{ old('start_date', $promotion->start_date->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror"
                           required>
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ngày kết thúc -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Ngày kết thúc <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="end_date" 
                           name="end_date" 
                           value="{{ old('end_date', $promotion->end_date->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror"
                           required>
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Trạng thái -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm font-medium text-gray-700">Kích hoạt khuyến mãi</span>
                </label>
                <p class="mt-1 text-sm text-gray-500">Bỏ tick để tạm thời vô hiệu hóa khuyến mãi</p>
            </div>

            <!-- Mô tả -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Mô tả khuyến mãi
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                          placeholder="Mô tả chi tiết về chương trình khuyến mãi...">{{ old('description', $promotion->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Thông tin sử dụng -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-800 mb-2">Thông tin sử dụng</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Đã sử dụng:</span>
                        <span class="font-semibold text-blue-600">{{ $promotion->usage_count }} lần</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Ngày tạo:</span>
                        <span class="font-semibold">{{ $promotion->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Cập nhật cuối:</span>
                        <span class="font-semibold">{{ $promotion->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('le-tan.promotions.show', $promotion->id) }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors duration-200">
                    Xem chi tiết
                </a>
                <a href="{{ route('le-tan.promotions.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors duration-200">
                    Hủy
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                    Cập nhật khuyến mãi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const discountTypeSelect = document.getElementById('discount_type');
    const discountValueInput = document.getElementById('discount_value');
    
    // Update placeholder based on discount type
    function updatePlaceholder() {
        if (discountTypeSelect.value === 'percentage') {
            discountValueInput.placeholder = 'VD: 20 (cho 20%)';
            discountValueInput.max = '100';
        } else if (discountTypeSelect.value === 'fixed') {
            discountValueInput.placeholder = 'VD: 50000 (cho 50,000 VNĐ)';
            discountValueInput.removeAttribute('max');
        }
    }
    
    // Initialize placeholder
    updatePlaceholder();
    
    // Update on change
    discountTypeSelect.addEventListener('change', updatePlaceholder);
    
    // Set minimum date for start_date to today
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
    });
    
    // Initialize end date minimum
    if (startDateInput.value) {
        endDateInput.min = startDateInput.value;
    }
});
</script>
@endsection
