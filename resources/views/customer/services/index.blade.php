@extends('layouts.app')

@section('title', 'Dịch vụ')

@section('content')
<!-- Header -->
<section class="bg-gray-900 text-white py-16">
    <div class="container mx-auto px-6">
        <h1 class="text-4xl font-bold mb-4">Dịch vụ của chúng tôi</h1>
        <p class="text-xl text-gray-300">Khám phá các dịch vụ chăm sóc sức khỏe và làm đẹp chuyên nghiệp.</p>
    </div>
</section>

<!-- Services Grid -->
<section class="py-16 bg-gray-100">
    <div class="container mx-auto px-6">
        @if($services->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-600 text-lg">Không tìm thấy dịch vụ nào.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($services as $service)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $service->name }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($service->description, 100) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-pink-500 font-bold">{{ number_format($service->price) }}đ</span>
                            <div class="space-x-2">
                                <a href="{{ route('customer.services.show', $service->id) }}" 
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
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-pink-500 text-white">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold mb-4">Bạn cần tư vấn thêm?</h2>
        <p class="text-xl mb-8">Đội ngũ chuyên viên của chúng tôi luôn sẵn sàng hỗ trợ bạn.</p>
        <a href="#" class="inline-block bg-white text-pink-500 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
            Liên hệ ngay
        </a>
    </div>
</section>
@endsection 