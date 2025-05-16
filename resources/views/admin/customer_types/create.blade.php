@extends('layouts.admin')

@section('title', 'Thêm loại khách hàng mới')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-2xl font-medium text-gray-700 mb-6">Thêm loại khách hàng mới</h3>

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.customer-types.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="type_name" class="block text-sm font-medium text-gray-700 mb-1">Tên loại khách hàng <span class="text-red-500">*</span></label>
                    <input type="text" name="type_name" id="type_name" value="{{ old('type_name') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    @error('type_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="color_code" class="block text-sm font-medium text-gray-700 mb-1">Mã màu <span class="text-red-500">*</span></label>
                    <div class="flex">
                        <input type="color" name="color_code" id="color_code" value="{{ old('color_code', '#FFD700') }}" required
                            class="h-10 w-10 border border-gray-300 rounded-lg mr-2">
                        <input type="text" id="color_code_text" value="{{ old('color_code', '#FFD700') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" readonly>
                    </div>
                    @error('color_code')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-1">Phần trăm giảm giá (%) <span class="text-red-500">*</span></label>
                    <input type="number" name="discount_percentage" id="discount_percentage" value="{{ old('discount_percentage', 0) }}" min="0" max="100" step="0.01" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    @error('discount_percentage')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="priority_level" class="block text-sm font-medium text-gray-700 mb-1">Mức ưu tiên <span class="text-red-500">*</span></label>
                    <input type="number" name="priority_level" id="priority_level" value="{{ old('priority_level', 0) }}" min="0" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    <p class="mt-1 text-xs text-gray-500">Số càng lớn, mức ưu tiên càng cao</p>
                    @error('priority_level')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="min_spending" class="block text-sm font-medium text-gray-700 mb-1">Chi tiêu tối thiểu (VNĐ) <span class="text-red-500">*</span></label>
                    <input type="number" name="min_spending" id="min_spending" value="{{ old('min_spending', 0) }}" min="0" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    @error('min_spending')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center h-full">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="is_active" value="1" class="sr-only peer" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                            <span class="ms-3 text-sm font-medium text-gray-700">Kích hoạt</span>
                        </label>
                    </div>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-700 mb-3">Quyền lợi đặc biệt</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="has_priority_booking" id="has_priority_booking" value="1" class="w-4 h-4 text-pink-600 bg-gray-100 border-gray-300 rounded focus:ring-pink-500" {{ old('has_priority_booking') ? 'checked' : '' }}>
                        <label for="has_priority_booking" class="ms-2 text-sm font-medium text-gray-700">Ưu tiên đặt lịch</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="has_personal_consultant" id="has_personal_consultant" value="1" class="w-4 h-4 text-pink-600 bg-gray-100 border-gray-300 rounded focus:ring-pink-500" {{ old('has_personal_consultant') ? 'checked' : '' }}>
                        <label for="has_personal_consultant" class="ms-2 text-sm font-medium text-gray-700">Tư vấn viên riêng</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="has_birthday_gift" id="has_birthday_gift" value="1" class="w-4 h-4 text-pink-600 bg-gray-100 border-gray-300 rounded focus:ring-pink-500" {{ old('has_birthday_gift') ? 'checked' : '' }}>
                        <label for="has_birthday_gift" class="ms-2 text-sm font-medium text-gray-700">Quà tặng sinh nhật</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="has_extended_warranty" id="has_extended_warranty" value="1" class="w-4 h-4 text-pink-600 bg-gray-100 border-gray-300 rounded focus:ring-pink-500" {{ old('has_extended_warranty') ? 'checked' : '' }}>
                        <label for="has_extended_warranty" class="ms-2 text-sm font-medium text-gray-700">Bảo hành mở rộng</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="has_free_service" id="has_free_service" value="1" class="w-4 h-4 text-pink-600 bg-gray-100 border-gray-300 rounded focus:ring-pink-500" {{ old('has_free_service') ? 'checked' : '' }}>
                        <label for="has_free_service" class="ms-2 text-sm font-medium text-gray-700">Dịch vụ miễn phí</label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="free_service_count" class="block text-sm font-medium text-gray-700 mb-1">Số lượng dịch vụ miễn phí</label>
                        <input type="number" name="free_service_count" id="free_service_count" value="{{ old('free_service_count', 0) }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                        <p class="mt-1 text-xs text-gray-500">Chỉ áp dụng khi bật tính năng "Dịch vụ miễn phí"</p>
                        @error('free_service_count')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="extended_warranty_days" class="block text-sm font-medium text-gray-700 mb-1">Số ngày bảo hành mở rộng</label>
                        <input type="number" name="extended_warranty_days" id="extended_warranty_days" value="{{ old('extended_warranty_days', 0) }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                        <p class="mt-1 text-xs text-gray-500">Chỉ áp dụng khi bật tính năng "Bảo hành mở rộng"</p>
                        @error('extended_warranty_days')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.customer-types.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Hủy
                </a>
                <button type="submit" class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                    Lưu
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorInput = document.getElementById('color_code');
        const colorText = document.getElementById('color_code_text');

        colorInput.addEventListener('input', function() {
            colorText.value = this.value;
        });
    });
</script>
@endpush
@endsection
