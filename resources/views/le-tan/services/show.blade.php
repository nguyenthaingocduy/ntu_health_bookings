@extends('layouts.le-tan')

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
            <a href="{{ route('le-tan.services.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
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
                <img class="h-64 w-full object-cover md:h-full" src="{{ secure_asset('storage/' . $service->image) }}" alt="{{ $service->name }}">
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
                    <span class="ml-2 text-gray-900">{{ $service->duration ?? 60 }} phút</span>
                </div>

                @if($service->promotions && $service->promotions->count() > 0)
                <div class="mt-4">
                    <h3 class="text-gray-700 font-medium mb-2">Khuyến mãi đang áp dụng:</h3>
                    <div class="space-y-2">
                        @foreach($service->promotions as $promotion)
                        <div class="flex items-center p-2 bg-green-50 rounded-lg">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <span class="font-medium">{{ $promotion->name }}</span>
                                <span class="ml-2 text-green-600">Giảm {{ $promotion->discount_percent }}%</span>
                                <p class="text-xs text-gray-500">Từ {{ $promotion->start_date->format('d/m/Y') }} đến {{ $promotion->end_date->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-6">
                    <h3 class="text-gray-700 font-medium mb-2">Mô tả:</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $service->description }}</p>
                </div>

                <div class="mt-8 flex space-x-4">
                    <a href="{{ route('le-tan.appointments.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors duration-150">
                        Đặt lịch cho khách hàng
                    </a>
                    <a href="{{ route('le-tan.consultations.create') }}" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900 transition-colors duration-150">
                        Tư vấn dịch vụ
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($relatedServices && $relatedServices->count() > 0)
    <div class="mt-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Dịch vụ liên quan</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach($relatedServices as $relatedService)
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="h-40 overflow-hidden">
                    @if($relatedService->image)
                    <img class="w-full h-full object-cover" src="{{ secure_asset('storage/' . $relatedService->image) }}" alt="{{ $relatedService->name }}">
                    @else
                    <div class="w-full h-full bg-indigo-100 flex items-center justify-center">
                        <svg class="h-12 w-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $relatedService->name }}</h3>
                    <p class="text-gray-500 text-sm">{{ $relatedService->category->name ?? 'Không có danh mục' }}</p>
                    <div class="mt-2 flex justify-between items-center">
                        <span class="font-bold text-indigo-600">{{ number_format($relatedService->price, 0, ',', '.') }} VNĐ</span>
                        <a href="{{ route('le-tan.services.show', $relatedService->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Chi tiết</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
