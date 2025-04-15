@extends('layouts.app')

@section('title', $service->name)

@section('content')
<!-- Service Details -->
<div class="bg-white">
    <div class="container mx-auto px-6 py-12">
        <!-- Breadcrumbs -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-gray-700 hover:text-pink-500">
                            <i class="fas fa-home mr-2"></i>
                            Trang chủ
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('services.index') }}" class="text-gray-700 hover:text-pink-500">Dịch vụ</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-500">{{ $service->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Service Image -->
            <div class="lg:col-span-2">
                <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="w-full h-auto rounded-lg shadow-lg">

                <!-- Service Description -->
                <div class="mt-8">
                    <h2 class="text-2xl font-bold mb-4">Mô tả dịch vụ</h2>
                    <div class="prose max-w-none">
                        <p>{{ $service->description }}</p>
                    </div>

                    <!-- Service Benefits -->
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold mb-4">Lợi ích</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                <span>Giúp làn da trở nên mịn màng, tươi trẻ</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                <span>Giảm căng thẳng, mệt mỏi</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                <span>Cải thiện lưu thông máu</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                <span>Tăng cường sức khỏe tổng thể</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                    <h2 class="text-2xl font-bold mb-4 text-gray-800">{{ $service->name }}</h2>

                    <div class="mb-6">
                        <p class="text-3xl font-bold text-pink-500">{{ number_format($service->price) }}đ</p>
                        <p class="text-gray-500">Đã bao gồm VAT</p>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-gray-600 w-6"></i>
                            <span class="ml-2">Thời gian: 60-90 phút</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-gray-600 w-6"></i>
                            <span class="ml-2">{{ $service->clinic ? $service->clinic->name : 'Không có thông tin' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt text-gray-600 w-6"></i>
                            <span class="ml-2">Có sẵn: {{ $service->start_date ? \Carbon\Carbon::parse($service->start_date)->format('d/m/Y') : 'N/A' }} - {{ $service->end_date ? \Carbon\Carbon::parse($service->end_date)->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <a href="{{ route('customer.appointments.create', ['service' => $service->id]) }}" class="block w-full bg-white text-pink-600 text-center font-bold py-3 px-4 rounded-full hover:bg-gray-50 transition shadow-md border border-pink-100">
                            Đặt lịch ngay
                        </a>
                    </div>

                    <div class="border-t border-gray-300 pt-4">
                        <h4 class="font-semibold mb-3">Chia sẻ:</h4>
                        <div class="flex space-x-3">
                            <a href="#" class="text-blue-600 hover:text-blue-800">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="text-blue-400 hover:text-blue-600">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="text-red-500 hover:text-red-700">
                                <i class="fab fa-pinterest"></i>
                            </a>
                            <a href="#" class="text-green-600 hover:text-green-800">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Services -->
        <div class="mt-16">
            <h2 class="text-2xl font-bold mb-8">Dịch vụ liên quan</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Placeholder for Related Services -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="{{ asset('images/service1.jpg') }}" alt="Service" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Massage Thư Giãn</h3>
                        <p class="text-gray-600 mb-4">Giúp thư giãn cơ thể, giảm căng thẳng và mệt mỏi.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-pink-500 font-bold">500,000đ</span>
                            <a href="#" class="text-pink-500 hover:text-pink-600">
                                Chi tiết <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="{{ asset('images/service2.jpg') }}" alt="Service" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Chăm Sóc Da Mặt</h3>
                        <p class="text-gray-600 mb-4">Làm sạch sâu, dưỡng ẩm và trẻ hóa làn da.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-pink-500 font-bold">750,000đ</span>
                            <a href="#" class="text-pink-500 hover:text-pink-600">
                                Chi tiết <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="{{ asset('images/service3.jpg') }}" alt="Service" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Tắm Thảo Dược</h3>
                        <p class="text-gray-600 mb-4">Thanh lọc cơ thể với các loại thảo dược tự nhiên.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-pink-500 font-bold">850,000đ</span>
                            <a href="#" class="text-pink-500 hover:text-pink-600">
                                Chi tiết <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection