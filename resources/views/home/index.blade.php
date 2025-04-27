@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<main class="py-4">
    <!-- Hero Section -->
<section class="relative bg-gray-900 text-white">
    <div class="absolute inset-0">
        <img src="{{ asset('images\clinics\1745049992.jpg') }}" alt="Hero background" class="w-full h-full object-cover opacity-50">
    </div>
    
    <div class="relative container mx-auto px-6 py-32">
        <div class="max-w-3xl">
            <h1 class="text-5xl font-bold mb-6">Chào mừng đến với Beauty Spa</h1>
            <p class="text-xl mb-8">Khám phá trải nghiệm chăm sóc sức khỏe và làm đẹp tuyệt vời với các dịch vụ chuyên nghiệp của chúng tôi.</p>
            <a href="{{ route('services.index') }}" class="inline-block bg-pink text-pink-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-50 transition shadow-md border border-pink-100">
                Xem dịch vụ
            </a>
        </div>
    </div>
</section>

<!-- Featured Services -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-12">Dịch vụ nổi bật</h2>
        
        {{-- <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredServices as $service)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">{{ $service->name }}</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit($service->description, 100) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-pink-500 font-bold">{{ number_format($service->price) }}đ</span>
                        <a href="{{ route('services.show', $service) }}" class="text-pink-500 hover:text-pink-600">
                            Chi tiết <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div> --}}

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredServices as $service)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden relative">
                @if($service->hasActivePromotion())
                <div class="absolute top-0 right-0 bg-pink-500 text-white px-3 py-1 z-10">
                    <div class="font-bold">{{ $service->promotion_value }}</div>
                    @if($service->promotion_details && !$service->promotion_details['is_direct'])
                    <div class="text-xs">
                        {{ $service->promotion_details['start_date'] }} - {{ $service->promotion_details['end_date'] }}
                    </div>
                    @endif
                </div>
                @endif
                <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex items-center mb-2">
                        <span class="px-3 py-1 bg-pink-100 text-pink-500 rounded-full text-sm">
                            {{ $service->category ? $service->category->name : 'Không phân loại' }}
                        </span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ $service->name }}</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit($service->description, 100) }}</p>
                    <div class="flex justify-between items-center">
                        <div>
                            @if($service->hasActivePromotion())
                            <div class="flex flex-col">
                                <span class="text-pink-500 font-bold text-lg">{{ $service->formatted_discounted_price }}</span>
                                <span class="text-gray-500 line-through text-sm font-medium">{{ number_format($service->price) }}đ</span>
                            </div>
                            @else
                            <span class="text-pink-500 font-bold">{{ number_format($service->price) }}đ</span>
                            @endif
                        </div>
                        <div class="space-x-2">
                            <a href="{{ route('services.show', $service->id) }}"
                                class="inline-block bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition">
                                Chi tiết
                            </a>
                            <a href="{{ route('customer.appointments.create', ['service' => $service->id]) }}"
                                class="inline-block bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 transition">
                                Đặt lịch
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        
        <div class="text-center mt-12">
            <a href="{{ route('services.index') }}" class="inline-block bg-white text-pink-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-50 transition shadow-md border border-pink-100">
                Xem tất cả dịch vụ
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 bg-gray-100">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-12">Tại sao chọn Beauty Spa?</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-pink-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-md text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Chuyên gia giàu kinh nghiệm</h3>
                <p class="text-gray-600">Đội ngũ bác sĩ và chuyên viên của chúng tôi được đào tạo chuyên sâu và có nhiều năm kinh nghiệm.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-pink-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-spa text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Dịch vụ chất lượng cao</h3>
                <p class="text-gray-600">Chúng tôi sử dụng các sản phẩm và thiết bị hiện đại nhất để đảm bảo kết quả tốt nhất cho khách hàng.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-pink-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-heart text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Chăm sóc tận tâm</h3>
                <p class="text-gray-600">Chúng tôi luôn đặt sự hài lòng và thoải mái của khách hàng lên hàng đầu.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-12">Khách hàng nói gì về chúng tôi</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($testimonials as $testimonial)
            <div class="bg-gray-100 p-6 rounded-lg">
                <div class="flex items-center mb-4">
                    <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold">{{ $testimonial->name }}</h4>
                        <div class="text-yellow-400">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star{{ $i < $testimonial->rating ? '' : '-o' }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">{{ $testimonial->content }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-pink-500 text-white">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold mb-4">Sẵn sàng trải nghiệm dịch vụ của chúng tôi?</h2>
        <p class="text-xl mb-8">Đặt lịch ngay hôm nay để nhận ưu đãi đặc biệt!</p>
        <a href="{{ route('customer.appointments.create') }}" class="inline-block bg-pink text-pink-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-50 transition shadow-md border border-pink-100">
            Đặt lịch ngay
        </a>
    </div>
</section>
</main>
@endsection