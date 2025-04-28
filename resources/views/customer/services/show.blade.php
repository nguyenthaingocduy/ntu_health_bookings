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

                @if(isset($service->gallery) && $service->gallery)
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
                        {{ $service->category->name ?? 'Không phân loại' }}
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

                <div class="mb-6">
                    <div class="flex items-center mb-2">
                        @if($service->hasActivePromotion())
                        <div class="flex items-center">
                            <span class="text-3xl font-bold text-pink-500">{{ $service->formatted_discounted_price }}</span>
                            <span class="text-gray-500 ml-2">/{{ $service->duration }} phút</span>
                        </div>
                        @else
                        <span class="text-3xl font-bold text-pink-500">{{ number_format($service->price) }}đ</span>
                        <span class="text-gray-500 ml-2">/{{ $service->duration }} phút</span>
                        @endif
                    </div>

                    @if($service->hasActivePromotion())
                    <div class="flex items-center">
                        <span class="text-gray-500 line-through text-lg">Giá gốc: {{ number_format($service->price) }}đ</span>
                        <span class="ml-3 bg-pink-100 text-pink-800 text-sm font-semibold px-2 py-1 rounded-full">
                            Giảm {{ $service->promotion_value }}
                        </span>
                    </div>

                    @php
                        $discountedPrice = $service->discounted_price;
                        $originalPrice = $service->price;
                        $savedAmount = $originalPrice - $discountedPrice;
                        $savedPercent = round(($savedAmount / $originalPrice) * 100);
                    @endphp

                    <div class="mt-2 bg-green-50 p-2 rounded-md text-sm border border-green-100">
                        <div class="flex items-center text-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Tiết kiệm: <span class="font-semibold">{{ number_format($savedAmount) }}đ ({{ $savedPercent }}%)</span></span>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Promotion Code Form -->
                <div class="mb-6 bg-gradient-to-r from-pink-50 to-pink-100 rounded-lg p-4 border border-pink-200">
                    <h3 class="font-semibold mb-2 flex items-center text-pink-800">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 9H10a3 3 0 013 3v1a1 1 0 102 0v-1a5 5 0 00-5-5H8.414l1.293-1.293z" clip-rule="evenodd"></path>
                        </svg>
                        Bạn có mã khuyến mãi?
                    </h3>
                    <div class="flex">
                        <input type="text" id="promotion_code_preview"
                            class="flex-1 px-4 py-2 border border-pink-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                            placeholder="Nhập mã khuyến mãi...">
                        <button type="button" id="apply_promotion_preview"
                            class="px-4 py-2 bg-pink-500 text-white rounded-r-lg hover:bg-pink-600 transition">
                            Áp dụng
                        </button>
                    </div>
                    <div id="promotion_preview_result" class="mt-2 text-sm hidden"></div>
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
                            {{ $relatedService->category->name ?? 'Không phân loại' }}
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const promotionCodeInput = document.getElementById('promotion_code_preview');
    const applyButton = document.getElementById('apply_promotion_preview');
    const resultDiv = document.getElementById('promotion_preview_result');

    if (applyButton && promotionCodeInput && resultDiv) {
        applyButton.addEventListener('click', function() {
            validatePromotionCode();
        });

        promotionCodeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                validatePromotionCode();
            }
        });
    }

    function validatePromotionCode() {
        const code = promotionCodeInput.value.trim();
        if (!code) {
            showResult(`
                <div class="bg-yellow-100 border-l-4 border-yellow-500 p-3 rounded-md">
                    <div class="flex items-center text-yellow-700">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        Vui lòng nhập mã khuyến mãi
                    </div>
                </div>
            `, 'warning');
            return;
        }

        // Lấy giá dịch vụ
        const servicePrice = {{ $service->price }};

        // Hiển thị trạng thái đang tải
        resultDiv.innerHTML = '<div class="flex items-center text-gray-500"><div class="loading-spinner mr-2"></div> Đang kiểm tra mã khuyến mãi...</div>';
        resultDiv.classList.remove('hidden');

        // Gọi API để kiểm tra mã khuyến mãi
        fetch('/api/validate-promotion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                code: code,
                amount: servicePrice,
                service_id: {{ $service->id }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hiển thị thông tin khuyến mãi
                const discountAmount = data.data.formatted_discount;
                const newPrice = new Intl.NumberFormat('vi-VN').format(servicePrice - data.data.discount) + 'đ';

                showResult(`
                    <div class="bg-green-100 border-l-4 border-green-500 p-3 rounded-md">
                        <div class="flex items-center text-green-700 font-semibold">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Mã khuyến mãi hợp lệ!
                        </div>
                    </div>

                    <div class="mt-3 p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Giá gốc:</span>
                            <span class="text-gray-500 line-through">{{ number_format($service->price) }}đ</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Giảm giá:</span>
                            <span class="font-semibold text-green-600">${discountAmount}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t">
                            <span class="font-semibold">Giá sau khuyến mãi:</span>
                            <span class="font-bold text-pink-600 text-xl">${newPrice}</span>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('customer.appointments.create', ['service' => $service->id]) }}?promotion_code=${code}"
                           class="inline-block bg-pink-500 text-white px-6 py-3 rounded-lg hover:bg-pink-600 transition font-semibold w-full">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Đặt lịch với mã này
                        </a>
                    </div>
                `, 'success');
            } else {
                // Hiển thị thông báo lỗi
                showResult(`
                    <div class="bg-red-100 border-l-4 border-red-500 p-3 rounded-md">
                        <div class="flex items-center text-red-700">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            ${data.message}
                        </div>
                    </div>
                `, 'error');
            }
        })
        .catch(error => {
            console.error('Lỗi khi kiểm tra mã khuyến mãi:', error);
            showResult(`
                <div class="bg-red-100 border-l-4 border-red-500 p-3 rounded-md">
                    <div class="flex items-center text-red-700">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        Đã xảy ra lỗi khi kiểm tra mã khuyến mãi. Vui lòng thử lại sau.
                    </div>
                </div>
            `, 'error');
        });
    }

    function showResult(message, type) {
        resultDiv.innerHTML = message;
        resultDiv.classList.remove('hidden', 'text-yellow-600', 'text-red-600', 'text-green-600');

        switch (type) {
            case 'success':
                resultDiv.classList.add('text-green-600');
                break;
            case 'error':
                resultDiv.classList.add('text-red-600');
                break;
            case 'warning':
                resultDiv.classList.add('text-yellow-600');
                break;
        }
    }
});
</script>
@endpush

@push('styles')
<style>
.loading-spinner {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 2px solid rgba(236, 72, 153, 0.3);
    border-radius: 50%;
    border-top-color: #ec4899;
    animation: spin 1s ease-in-out infinite;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
@endpush
