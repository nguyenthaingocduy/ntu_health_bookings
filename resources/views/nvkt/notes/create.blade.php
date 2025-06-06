@extends('layouts.nvkt-new')

@section('title', 'Thêm ghi chú chuyên môn')

@section('header', 'Thêm ghi chú chuyên môn')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Thêm ghi chú chuyên môn</h1>
            <p class="text-sm text-gray-500 mt-1">Tạo ghi chú chuyên môn mới</p>
        </div>
        <div>
            <a href="{{ route('nvkt.notes.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <form action="{{ route('nvkt.notes.store') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700">Tiêu đề</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-2 border border-gray-300  rounded-lg focus:ring-2 focus:ring-gray-500 ">
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="service_id" class="block text-sm font-medium text-gray-700">Dịch vụ liên quan</label>
                    <select name="service_id" id="service_id"
                        class="w-full px-4 py-2 border border-gray-300  rounded-lg focus:ring-2 focus:ring-gray-500 ">
                        <option value="">-- Chọn dịch vụ --</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('service_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Khách hàng liên quan</label>
                    <select name="customer_id" id="customer_id"
                        class="w-full px-4 py-2 border border-gray-300  rounded-lg focus:ring-2 focus:ring-gray-500 ">
                        <option value="">-- Chọn khách hàng --</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->first_name }} {{ $customer->last_name }}
                        </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label for="content" class="block text-sm font-medium text-gray-700">Nội dung</label>
                    <textarea name="content" id="content" rows="6" required
                        class="w-full px-4 py-2 border border-gray-300  rounded-lg focus:ring-2 focus:ring-gray-500 ">{{ old('content') }}</textarea>
                    @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors duration-150">
                    Lưu ghi chú
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
