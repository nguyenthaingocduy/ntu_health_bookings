@extends('layouts.app')

@section('title', $service->name)

@section('content')
<!-- Service Header -->
<div class="bg-white pt-8 pb-4">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center justify-between">
            <div>
                {{-- <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}" class="text-gray-500 hover:text-pink-500">
                                <i class="fas fa-home mr-2"></i>Trang chủ
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                                <a href="{{ route('customer.services.index') }}" class="text-gray-500 hover:text-pink-500">Dịch vụ</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                                <span class="text-gray-500">{{ $service->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav> --}}
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $service->name }}</h1>
                <div class="flex items-center mb-2">
                    @if($service->category)
                    <span class="bg-pink-100 text-pink-600 text-sm px-3 py-1 rounded-full">
                        {{ $service->category->name }}
                    </span>
                    @endif
                    <span class="mx-3 text-gray-400">|</span>
                    <div class="flex items-center text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        <span>{{ $service->duration }} phút</span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-pink-500 mb-1">{{ number_format($service->price) }}đ</div>
                @if($service->promotion > 0)
                <div class="text-sm text-gray-500">
                    <span class="line-through">{{ number_format($service->price * (1 + $service->promotion/100)) }}đ</span>
                    <span class="ml-1 bg-pink-100 text-pink-600 px-2 py-0.5 rounded">-{{ $service->promotion }}%</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Service Details -->
<div class="bg-white py-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Service Info -->
            <div class="lg:col-span-2">
                <!-- Main Image -->
                <div class="rounded-lg overflow-hidden shadow-md mb-8">
                    <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="w-full h-auto object-cover">
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">
                        Chi tiết dịch vụ
                    </h2>
                    <div class="prose max-w-none text-gray-600">
                        {!! $service->description !!}
                    </div>
                </div>

                <!-- Features -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">
                        Ưu điểm dịch vụ
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center p-3 border border-gray-100 rounded-lg">
                            <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-pink-500"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Thời gian</h4>
                                <p class="text-sm text-gray-600">{{ $service->duration }} phút</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 border border-gray-100 rounded-lg">
                            <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user-md text-pink-500"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Chuyên viên</h4>
                                <p class="text-sm text-gray-600">Chuyên nghiệp</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 border border-gray-100 rounded-lg">
                            <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-certificate text-pink-500"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Chất lượng</h4>
                                <p class="text-sm text-gray-600">Đảm bảo</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 border border-gray-100 rounded-lg">
                            <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-shield-alt text-pink-500"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">An toàn</h4>
                                <p class="text-sm text-gray-600">100%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">
                        Câu hỏi thường gặp
                    </h2>
                    <div class="space-y-4">
                        @foreach($faqs as $faq)
                        <div class="border border-gray-100 rounded-lg" x-data="{ open: false }">
                            <button @click="open = !open" class="flex justify-between items-center w-full px-4 py-3 text-left">
                                <span class="font-medium text-gray-800">{{ $faq->question }}</span>
                                <i class="fas" :class="open ? 'fa-chevron-up text-pink-500' : 'fa-chevron-down text-gray-400'"></i>
                            </button>

                            <div x-show="open" class="px-4 pb-3 text-gray-600 text-sm">
                                <p>{{ $faq->answer }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm sticky top-24">
                    <div class="bg-pink-500 text-white py-3 px-4 rounded-t-lg">
                        <h3 class="text-lg font-semibold">Đặt lịch ngay</h3>
                    </div>

                    <form action="{{ route('customer.appointments.store') }}" method="POST" class="p-4">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->id }}">

                        <div class="space-y-3">
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Chọn ngày</label>
                                <div class="relative">
                                    <input type="date" name="date_appointments" required min="{{ date('Y-m-d') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Chọn giờ</label>
                                <div class="relative">
                                    <select name="time_appointments_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500 appearance-none">
                                        @foreach($timeSlots as $slot)
                                            <option value="{{ isset($slot['id']) ? $slot['id'] : $slot['time'] }}">
                                                {{ $slot['time'] }} (còn {{ isset($slot['available']) ? $slot['available'] : ($slot['capacity'] - $slot['booked']) }} chỗ)
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">* Mỗi khung giờ tối thiểu 10 khách</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Ghi chú</label>
                                <textarea name="notes" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500"
                                    placeholder="Nhập yêu cầu đặc biệt nếu có..."></textarea>
                            </div>

                            <button type="submit" class="w-full bg-pink-500 text-white px-4 py-2 rounded font-medium hover:bg-pink-600 transition">
                                Đặt lịch ngay
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Services -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Dịch vụ liên quan</h2>
            <a href="{{ route('customer.services.index') }}" class="text-pink-500 hover:text-pink-600 text-sm font-medium">
                Xem tất cả
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($relatedServices as $relatedService)
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <img src="{{ $relatedService->image_url }}" alt="{{ $relatedService->name }}" class="w-full h-40 object-cover">
                <div class="p-4">
                    <h3 class="font-medium text-gray-800 mb-1">{{ $relatedService->name }}</h3>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-pink-500 font-medium">{{ number_format($relatedService->price) }}đ</span>
                        <a href="{{ route('customer.services.show', $relatedService) }}" class="text-sm text-pink-500 hover:underline">
                            Chi tiết
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection