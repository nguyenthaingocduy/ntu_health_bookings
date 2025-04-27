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

<!-- Search & Filter -->
<section class="bg-white shadow">
    <div class="container mx-auto px-6 py-6">
        <form action="{{ route('services.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Tìm kiếm dịch vụ..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
            </div>

            <div class="w-full md:w-auto">
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-auto">
                <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                </select>
            </div>

            <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded-lg hover:bg-pink-600 transition">
                <i class="fas fa-search mr-2"></i>Tìm kiếm
            </button>
        </form>
    </div>
</section>

<!-- Services Grid -->
<section class="py-16 bg-gray-100">
    <div class="container mx-auto px-6">
        @if($services->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-600 text-lg">Không tìm thấy dịch vụ nào phù hợp với tiêu chí tìm kiếm.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($services as $service)
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

            <!-- Pagination -->
            <div class="mt-8">
                {{ $services->withQueryString()->links() }}
            </div>
        @endif
    </div>
</section>

<!-- Related Categories -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-12">Danh mục dịch vụ</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('services.index', ['category' => $category->id]) }}"
                class="bg-gray-100 p-6 rounded-lg text-center hover:bg-pink-50 transition group">
                <i class="{{ $category->icon }} text-4xl text-pink-500 mb-4"></i>
                <h3 class="text-lg font-semibold group-hover:text-pink-500">{{ $category->name }}</h3>
                <p class="text-gray-600 text-sm">{{ $category->service_count }} dịch vụ</p>
            </a>
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