@extends('layouts.app')

@section('title', 'Dịch vụ của chúng tôi')

@section('content')
<!-- Services Banner -->
<section class="relative bg-gray-900 text-white">
    <div class="absolute inset-0">
        <img src="{{ asset('images/services-banner.jpg') }}" alt="Services background" class="w-full h-full object-cover opacity-50">
    </div>
    
    <div class="relative container mx-auto px-6 py-24">
        <div class="max-w-3xl">
            <h1 class="text-4xl font-bold mb-4">Dịch vụ của chúng tôi</h1>
            <p class="text-xl">Khám phá các dịch vụ chăm sóc sức khỏe và làm đẹp chuyên nghiệp tại Beauty Spa.</p>
        </div>
    </div>
</section>

<!-- Services List -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <!-- Filter and Search (To be implemented) -->
        <div class="mb-12 p-6 bg-gray-100 rounded-lg">
            <form action="{{ route('services.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-gray-700 mb-2">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full rounded border-gray-300" placeholder="Tên dịch vụ...">
                </div>
                
                <div class="w-48">
                    <label class="block text-gray-700 mb-2">Danh mục</label>
                    <select name="category" class="w-full rounded border-gray-300">
                        <option value="">Tất cả</option>
                        <option value="spa">Spa</option>
                        <option value="massage">Massage</option>
                        <option value="facial">Chăm sóc da</option>
                    </select>
                </div>
                
                <div class="w-48">
                    <label class="block text-gray-700 mb-2">Sắp xếp theo</label>
                    <select name="sort" class="w-full rounded border-gray-300">
                        <option value="newest">Mới nhất</option>
                        <option value="price_low">Giá: Thấp - Cao</option>
                        <option value="price_high">Giá: Cao - Thấp</option>
                    </select>
                </div>
                
                <button type="submit" class="bg-white text-pink-600 py-3 px-6 rounded-full hover:bg-gray-50 transition shadow-md border border-pink-100 font-semibold">
                    Lọc
                </button>
            </form>
        </div>
        
        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $service)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="w-full h-56 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">{{ $service->name }}</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit($service->description, 120) }}</p>
                    
                    <div class="flex items-center text-gray-500 mb-4">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <span>{{ $service->clinic->name }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-pink-500 font-bold text-xl">{{ number_format($service->price) }}đ</span>
                        <a href="{{ route('services.show', $service->id) }}" class="inline-block bg-white text-pink-600 px-4 py-2 rounded-full hover:bg-gray-50 transition shadow-md border border-pink-100 font-semibold">
                            Chi tiết
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-12">
            {{ $services->links() }}
        </div>
    </div>
</section>
@endsection 