@extends('layouts.le-tan')

@section('title', 'Tạo tư vấn dịch vụ mới')

@section('header', 'Tạo tư vấn dịch vụ mới')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tạo tư vấn dịch vụ mới</h1>
            <p class="text-sm text-gray-500 mt-1">Tạo buổi tư vấn dịch vụ mới cho khách hàng</p>
        </div>
        <div>
            <a href="{{ route('le-tan.consultations.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <form action="{{ route('le-tan.consultations.store') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">Khách hàng <span class="text-red-500">*</span></label>
                    <select id="customer_id" name="customer_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">Chọn khách hàng</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->first_name }} {{ $customer->last_name }} - {{ $customer->email }}
                        </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Dịch vụ <span class="text-red-500">*</span></label>
                    <select id="service_id" name="service_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">Chọn dịch vụ</option>
                        @foreach($categories as $category)
                            @if($category->services->count() > 0 || $category->children->count() > 0)
                                <optgroup label="{{ $category->name }}">
                                    @foreach($category->services as $service)
                                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }} - {{ number_format($service->price, 0, ',', '.') }} VNĐ
                                        </option>
                                    @endforeach
                                    
                                    @foreach($category->children as $childCategory)
                                        @if($childCategory->services->count() > 0)
                                            <optgroup label="&nbsp;&nbsp;&nbsp;{{ $childCategory->name }}">
                                                @foreach($childCategory->services as $service)
                                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                                        {{ $service->name }} - {{ number_format($service->price, 0, ',', '.') }} VNĐ
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach
                                </optgroup>
                            @endif
                        @endforeach
                    </select>
                    @error('service_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="recommended_date" class="block text-sm font-medium text-gray-700 mb-1">Ngày đề xuất</label>
                    <input type="date" id="recommended_date" name="recommended_date" value="{{ old('recommended_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('recommended_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Ghi chú tư vấn <span class="text-red-500">*</span></label>
                    <textarea id="notes" name="notes" rows="5" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('notes') }}</textarea>
                    @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Thông tin khuyến mãi -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Khuyến mãi đang áp dụng</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    @if($activePromotions->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($activePromotions as $promotion)
                        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-pink-100 text-pink-500 rounded-full">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $promotion->title }}</h4>
                                    <p class="text-xs text-gray-500">Mã: <span class="font-medium">{{ $promotion->code }}</span></p>
                                    <p class="text-xs text-gray-500">
                                        Giảm: 
                                        @if($promotion->discount_type == 'percentage')
                                            {{ $promotion->discount_value }}%
                                        @else
                                            {{ number_format($promotion->discount_value, 0, ',', '.') }} VNĐ
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Thời gian: {{ $promotion->start_date->format('d/m/Y') }} - {{ $promotion->end_date->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-sm">Không có khuyến mãi nào đang áp dụng</p>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded-lg hover:bg-pink-600 transition-colors duration-150 shadow-md">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Lưu tư vấn
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
