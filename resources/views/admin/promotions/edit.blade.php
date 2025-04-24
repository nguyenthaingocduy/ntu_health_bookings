@extends('layouts.admin')

@section('title', 'Chỉnh sửa khuyến mãi')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
        background: #f56565;
        border-color: #f56565;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chỉnh sửa khuyến mãi</h1>
            <p class="text-sm text-gray-500 mt-1">Cập nhật thông tin khuyến mãi</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.promotions.show', $promotion->id) }}" class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Xem chi tiết
            </a>
            <a href="{{ route('admin.promotions.index') }}" class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-150 shadow-sm border border-gray-200">
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
                <a href="{{ route('admin.promotions.index') }}" class="hover:text-pink-500">Quản lý khuyến mãi</a>
                <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </li>
            <li class="flex items-center">
                <a href="{{ route('admin.promotions.show', $promotion->id) }}" class="hover:text-pink-500">{{ $promotion->title }}</a>
                <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </li>
            <li class="text-pink-500 font-medium">Chỉnh sửa</li>
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
                            <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 9H10a3 3 0 013 3v1a1 1 0 102 0v-1a5 5 0 00-5-5H8.414l1.293-1.293z" clip-rule="evenodd"></path>
                        </svg>
                        Thông tin khuyến mãi
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST" id="promotion-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Tiêu đề <span class="text-red-500">*</span></label>
                                <input type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('title') border-red-500 @enderror" id="title" name="title" value="{{ old('title', $promotion->title) }}" required>
                                @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Mã khuyến mãi <span class="text-red-500">*</span></label>
                                <input type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('code') border-red-500 @enderror" id="code" name="code" value="{{ old('code', $promotion->code) }}" required>
                                @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Mã khuyến mãi sẽ được chuyển thành chữ hoa</p>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                            <textarea class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('description') border-red-500 @enderror" id="description" name="description" rows="3">{{ old('description', $promotion->description) }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="discount_type" class="block text-sm font-medium text-gray-700 mb-2">Loại giảm giá <span class="text-red-500">*</span></label>
                                <select class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('discount_type') border-red-500 @enderror" id="discount_type" name="discount_type" required>
                                    <option value="percentage" {{ old('discount_type', $promotion->discount_type) == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                                    <option value="fixed" {{ old('discount_type', $promotion->discount_type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
                                </select>
                                @error('discount_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-2">Giá trị giảm giá <span class="text-red-500">*</span></label>
                                <input type="number" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('discount_value') border-red-500 @enderror" id="discount_value" name="discount_value" value="{{ old('discount_value', $promotion->discount_value) }}" min="0" step="0.01" required>
                                @error('discount_value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="minimum_purchase" class="block text-sm font-medium text-gray-700 mb-2">Giá trị đơn hàng tối thiểu</label>
                                <input type="number" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('minimum_purchase') border-red-500 @enderror" id="minimum_purchase" name="minimum_purchase" value="{{ old('minimum_purchase', $promotion->minimum_purchase) }}" min="0" step="0.01">
                                @error('minimum_purchase')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="maximum_discount" class="block text-sm font-medium text-gray-700 mb-2">Giảm giá tối đa</label>
                                <input type="number" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('maximum_discount') border-red-500 @enderror" id="maximum_discount" name="maximum_discount" value="{{ old('maximum_discount', $promotion->maximum_discount) }}" min="0" step="0.01">
                                @error('maximum_discount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Chỉ áp dụng cho loại giảm giá theo phần trăm</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Ngày bắt đầu <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('start_date') border-red-500 @enderror" id="start_date" name="start_date" value="{{ old('start_date', $promotion->start_date->format('Y-m-d')) }}" required>
                                </div>
                                @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Ngày kết thúc <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('end_date') border-red-500 @enderror" id="end_date" name="end_date" value="{{ old('end_date', $promotion->end_date->format('Y-m-d')) }}" required>
                                </div>
                                @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="usage_limit" class="block text-sm font-medium text-gray-700 mb-2">Giới hạn sử dụng</label>
                                <input type="number" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 @error('usage_limit') border-red-500 @enderror" id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $promotion->usage_limit) }}" min="{{ $promotion->usage_count }}" step="1">
                                @error('usage_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Để trống nếu không giới hạn số lần sử dụng. Giá trị tối thiểu: {{ $promotion->usage_count }}</p>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="flex items-center h-5 mt-6">
                                    <input type="checkbox" class="w-4 h-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500" id="is_active" name="is_active" value="1" {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_active" class="font-medium text-gray-700">Kích hoạt</label>
                                    <p class="text-gray-500">Khuyến mãi sẽ được kích hoạt và có thể sử dụng</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end mt-8">
                            <a href="{{ route('admin.promotions.show', $promotion->id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150 mr-4">
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
                        Thông tin hiện tại
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Thông tin cơ bản</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="mb-3">
                                <p class="text-sm text-gray-500">Mã khuyến mãi</p>
                                <p class="text-base font-medium text-gray-800">{{ $promotion->code }}</p>
                            </div>
                            <div class="mb-3">
                                <p class="text-sm text-gray-500">Trạng thái</p>
                                <div class="mt-1">
                                    {!! $promotion->status_badge !!}
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Sử dụng</p>
                                <p class="text-base font-medium text-gray-800">
                                    {{ $promotion->usage_count }}
                                    @if($promotion->usage_limit)
                                    / {{ $promotion->usage_limit }}
                                    @else
                                    <span class="text-xs text-gray-500">(không giới hạn)</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Lưu ý</h4>
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4">
                            <ul class="list-disc list-inside text-sm">
                                <li class="mb-1">Nếu thay đổi loại giảm giá, hãy kiểm tra lại giá trị giảm giá</li>
                                <li class="mb-1">Giới hạn sử dụng không thể nhỏ hơn số lần đã sử dụng ({{ $promotion->usage_count }})</li>
                                <li>Thay đổi mã khuyến mãi có thể ảnh hưởng đến người dùng đang sử dụng</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date pickers
        flatpickr("#start_date", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            locale: "vn",
            disableMobile: true,
            onChange: function(selectedDates, dateStr, instance) {
                endDatePicker.set('minDate', dateStr);
            }
        });
        
        const endDatePicker = flatpickr("#end_date", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            locale: "vn",
            disableMobile: true
        });
        
        // Toggle maximum_discount field based on discount_type
        const discountTypeSelect = document.getElementById('discount_type');
        const maximumDiscountDiv = document.getElementById('maximum_discount').closest('div');
        
        if (discountTypeSelect && maximumDiscountDiv) {
            function toggleMaximumDiscount() {
                if (discountTypeSelect.value === 'percentage') {
                    maximumDiscountDiv.classList.remove('opacity-50', 'pointer-events-none');
                    document.getElementById('maximum_discount').disabled = false;
                } else {
                    maximumDiscountDiv.classList.add('opacity-50', 'pointer-events-none');
                    document.getElementById('maximum_discount').disabled = true;
                    document.getElementById('maximum_discount').value = '';
                }
            }
            
            discountTypeSelect.addEventListener('change', toggleMaximumDiscount);
            toggleMaximumDiscount(); // Run on page load
        }
    });
</script>
@endsection
