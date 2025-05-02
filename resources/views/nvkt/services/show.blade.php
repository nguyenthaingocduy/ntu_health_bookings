@extends('layouts.nvkt-new')

@section('title', 'Chi tiết dịch vụ')

@section('header', 'Chi tiết dịch vụ')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $service->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Thông tin chi tiết về dịch vụ</p>
        </div>
        <div>
            <a href="{{ route('nvkt.services.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="md:flex">
            <div class="md:flex-shrink-0 md:w-1/3">
                @if($service->image)
                <img class="h-64 w-full object-cover md:h-full" src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}">
                @else
                <div class="h-64 w-full bg-indigo-100 flex items-center justify-center md:h-full">
                    <svg class="h-16 w-16 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                @endif
            </div>
            <div class="p-8 md:w-2/3">
                <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">{{ $service->category->name ?? 'Không có danh mục' }}</div>
                <h2 class="mt-2 text-2xl font-bold text-gray-900">{{ $service->name }}</h2>

                <div class="mt-4 flex items-center">
                    <span class="text-gray-700 font-medium">Giá:</span>
                    <span class="ml-2 text-xl font-bold text-gray-900">{{ number_format($service->price, 0, ',', '.') }} VNĐ</span>
                    @if($service->discount_percent > 0)
                    <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Giảm {{ $service->discount_percent }}%</span>
                    @endif
                </div>

                <div class="mt-2 flex items-center">
                    <span class="text-gray-700 font-medium">Thời gian:</span>
                    <span class="ml-2 text-gray-900">{{ $service->duration }} phút</span>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900">Mô tả dịch vụ</h3>
                    <div class="mt-2 text-gray-600">
                        {!! nl2br(e($service->description)) !!}
                    </div>
                </div>

                @if($service->benefits)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900">Lợi ích</h3>
                    <div class="mt-2 text-gray-600">
                        {!! nl2br(e($service->benefits)) !!}
                    </div>
                </div>
                @endif

                @if($service->procedure)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900">Quy trình thực hiện</h3>
                    <div class="mt-2 text-gray-600">
                        {!! nl2br(e($service->procedure)) !!}
                    </div>
                </div>
                @endif

                @if($service->notes)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900">Lưu ý</h3>
                    <div class="mt-2 text-gray-600">
                        {!! nl2br(e($service->notes)) !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
