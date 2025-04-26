@extends('layouts.app')

@section('title', $service->name)

@section('content')
<!-- Service Details -->
<div class="bg-white">
    <div class="container mx-auto px-6 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Image Gallery -->
            <div class="space-y-4">
                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                    <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="w-full h-full object-cover">
                </div>

                @if($service->gallery)
                <div class="grid grid-cols-4 gap-4">
                    @foreach($service->gallery as $image)
                    <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden">
                        <img src="{{ $image }}" alt="{{ $service->name }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Service Info -->
            <div>
                <div class="mb-6">
                    <span class="px-3 py-1 bg-pink-100 text-pink-500 rounded-full text-sm">
                        {{ $service->category ? $service->category->name : 'Không phân loại' }}
                    </span>
                </div>

                <h1 class="text-3xl font-bold mb-4">{{ $service->name }}</h1>

                @if($service->hasActivePromotion())
                <div class="bg-pink-100 border-l-4 border-pink-500 p-4 mb-6">
                    <div class="flex items-center">
                        <div class="text-pink-500 mr-2">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-pink-700">Khuyến mãi {{ $service->promotion_value }}</h3>
                            @if($service->promotion_details && !$service->promotion_details['is_direct'])
                            <p class="text-sm text-pink-600">
                                {{ $service->promotion_details['title'] }} - Từ {{ $service->promotion_details['start_date'] }} đến {{ $service->promotion_details['end_date'] }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex items-center mb-6">
                    @if($service->hasActivePromotion())
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-pink-500">{{ $service->formatted_discounted_price }}</span>
                        <span class="text-gray-500 ml-2">/{{ $service->duration }} phút</span>
                        <span class="text-gray-500 line-through ml-4">{{ number_format($service->price) }}đ</span>
                    </div>
                    @else
                    <span class="text-2xl font-bold text-pink-500">{{ number_format($service->price) }}đ</span>
                    <span class="text-gray-500 ml-2">/{{ $service->duration }} phút</span>
                    @endif
                </div>

                <div class="prose max-w-none mb-8">
                    {!! $service->description !!}
                </div>

                <!-- Booking Form -->
                <div class="bg-gray-100 rounded-lg p-6 mb-8">
                    <h3 class="text-xl font-semibold mb-4">Đặt lịch ngay</h3>
                    <a href="{{ route('customer.appointments.create', ['service' => $service->id]) }}" class="w-full bg-pink-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-pink-600 transition block text-center">
                        Đặt lịch ngay
                    </a>
                </div>

                <!-- Features -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-pink-500"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Thời gian</h4>
                            <p class="text-gray-600">{{ $service->duration }} phút</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-md text-pink-500"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Chuyên viên</h4>
                            <p class="text-gray-600">Chuyên nghiệp</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-certificate text-pink-500"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Chất lượng</h4>
                            <p class="text-gray-600">Đảm bảo</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-shield-alt text-pink-500"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">An toàn</h4>
                            <p class="text-gray-600">100%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Services -->
<section class="py-16 bg-gray-100">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-12">Dịch vụ liên quan</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($relatedServices as $relatedService)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden relative">
                @if($relatedService->hasActivePromotion())
                <div class="absolute top-0 right-0 bg-pink-500 text-white px-3 py-1 z-10">
                    <div class="font-bold">{{ $relatedService->promotion_value }}</div>
                    @if($relatedService->promotion_details && !$relatedService->promotion_details['is_direct'])
                    <div class="text-xs">
                        {{ $relatedService->promotion_details['start_date'] }} - {{ $relatedService->promotion_details['end_date'] }}
                    </div>
                    @endif
                </div>
                @endif
                <img src="{{ $relatedService->image_url }}" alt="{{ $relatedService->name }}" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex items-center mb-2">
                        <span class="px-3 py-1 bg-pink-100 text-pink-500 rounded-full text-sm">
                            {{ $relatedService->category ? $relatedService->category->name : 'Không phân loại' }}
                        </span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ $relatedService->name }}</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit($relatedService->description, 100) }}</p>
                    <div class="flex justify-between items-center">
                        <div>
                            @if($relatedService->hasActivePromotion())
                            <div class="flex items-center gap-2">
                                <span class="text-pink-500 font-bold">{{ $relatedService->formatted_discounted_price }}</span>
                                <span class="text-gray-500 line-through text-sm">{{ number_format($relatedService->price) }}đ</span>
                            </div>
                            @else
                            <span class="text-pink-500 font-bold">{{ number_format($relatedService->price) }}đ</span>
                            @endif
                        </div>
                        <a href="{{ route('services.show', $relatedService->id) }}"
                            class="inline-block bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition">
                            Chi tiết
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-12">Câu hỏi thường gặp</h2>

        <div class="max-w-3xl mx-auto space-y-4">
            @foreach($faqs as $faq)
            <div class="border border-gray-200 rounded-lg" x-data="{ open: false }">
                <button @click="open = !open" class="flex justify-between items-center w-full px-6 py-4 text-left">
                    <span class="font-semibold">{{ $faq->question }}</span>
                    <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>

                <div x-show="open" class="px-6 pb-4">
                    <p class="text-gray-600">{{ $faq->answer }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-pink-500 text-white">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold mb-4">Bạn cần tư vấn thêm?</h2>
        <p class="text-xl mb-8">Đội ngũ chuyên viên của chúng tôi luôn sẵn sàng hỗ trợ bạn.</p>
        <a href="{{ route('contact') }}" class="inline-block bg-white text-pink-500 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
            Liên hệ ngay
        </a>
    </div>
</section>
@endsection