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
                        @if($service->category)
                            {{ $service->category->name }}
                        @else
                            Chưa phân loại
                        @endif
                    </span>
                </div>
                
                <h1 class="text-3xl font-bold mb-4">{{ $service->name }}</h1>
                
                <div class="flex items-center mb-6">
                    <span class="text-2xl font-bold text-pink-500">{{ number_format($service->price) }}đ</span>
                    <span class="text-gray-500 ml-2">/{{ $service->duration }} phút</span>
                </div>
                
                <div class="prose max-w-none mb-8">
                    {!! $service->description !!}
                </div>
                
                <!-- Booking Form -->
                <div class="bg-gray-100 rounded-lg p-6 mb-8">
                    <h3 class="text-xl font-semibold mb-4">Đặt lịch ngay</h3>
                    <form action="{{ route('customer.appointments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 mb-2">Ngày</label>
                                <input type="date" name="date" required min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 mb-2">Thời gian</label>
                                <select name="time" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                                    @foreach($timeSlots as $slot)
                                        <option value="{{ $slot }}">{{ $slot }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Ghi chú</label>
                            <textarea name="notes" rows="3" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                                placeholder="Nhập yêu cầu đặc biệt nếu có..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-pink-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-pink-600 transition">
                            Đặt lịch ngay
                        </button>
                    </form>
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
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="{{ $relatedService->image_url }}" alt="{{ $relatedService->name }}" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex items-center mb-2">
                        <span class="px-3 py-1 bg-pink-100 text-pink-500 rounded-full text-sm">
                            @if($relatedService->category)
                                {{ $relatedService->category->name }}
                            @else
                                Chưa phân loại
                            @endif
                        </span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ $relatedService->name }}</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit($relatedService->description, 100) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-pink-500 font-bold">{{ number_format($relatedService->price) }}đ</span>
                        <a href="{{ route('customer.services.show', $relatedService) }}" 
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