@extends('layouts.customer')

@section('title', 'Loại khách hàng')

@section('content')
<div class="container mx-auto px-10 py-5">
    <div class="max-w-5xl mx-auto">
        <!-- Tiêu đề trang -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Chương trình khách hàng thân thiết</h1>
            <p class="text-gray-600">Khám phá các đặc quyền và ưu đãi dành riêng cho từng hạng thành viên</p>
        </div>

        <!-- Thông tin khách hàng hiện tại -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8 border border-pink-100">
            <div class="p-6">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="relative mr-5">
                            <img class="h-16 w-16 rounded-full object-cover border-4 border-white shadow-md"
                                src="https://ui-avatars.com/api/?name={{ $user->first_name }}&background=f9a8d4&color=ffffff"
                                alt="{{ $user->first_name }}">
                            <div class="absolute bottom-0 right-0 h-4 w-4 rounded-full bg-green-400 border-2 border-white"></div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ $user->first_name }} {{ $user->last_name }}</h3>
                            <div class="flex items-center mt-1">
                                <span class="flex items-center font-medium text-sm mr-4" style="color: {{ $user->customerType->color_code ?? '#6B7280' }}">
                                    <i class="fas fa-crown mr-2"></i> {{ $user->customerType->type_name ?? 'Regular' }}
                                </span>
                                <span class="flex items-center text-gray-600 text-sm">
                                    <i class="fas fa-coins mr-2"></i> Tổng chi tiêu: {{ number_format($totalSpending, 0, ',', '.') }} VNĐ
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách các loại khách hàng -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach($customerTypes as $type)
            <div class="bg-white rounded-xl shadow-md overflow-hidden border {{ $user->type_id == $type->id ? 'border-2 border-' . substr($type->color_code, 1) : 'border-gray-200' }}">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold" style="color: {{ $type->color_code }}">{{ $type->type_name }}</h3>
                        @if($user->type_id == $type->id)
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Cấp độ hiện tại</span>
                        @endif
                    </div>

                    <div class="space-y-3 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-tag text-pink-500 w-6"></i>
                            <span class="ml-2">Giảm giá {{ $type->formatted_discount }} cho mọi dịch vụ</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-coins text-pink-500 w-6"></i>
                            <span class="ml-2">Chi tiêu tối thiểu: {{ number_format($type->min_spending, 0, ',', '.') }} VNĐ</span>
                        </div>

                        <div class="flex flex-wrap gap-2 mt-2">
                            @if($type->has_priority_booking)
                                <span class="inline-flex items-center px-2 py-1 bg-pink-100 text-pink-800 text-xs font-medium rounded-full">
                                    <i class="fas fa-calendar-check mr-1"></i> Ưu tiên đặt lịch
                                </span>
                            @endif

                            @if($type->has_personal_consultant)
                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                    <i class="fas fa-user-md mr-1"></i> Tư vấn riêng
                                </span>
                            @endif

                            @if($type->has_birthday_gift)
                                <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">
                                    <i class="fas fa-gift mr-1"></i> Quà sinh nhật
                                </span>
                            @endif

                            @if($type->has_free_service)
                                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                    <i class="fas fa-spa mr-1"></i> {{ $type->free_service_count }} dịch vụ miễn phí
                                </span>
                            @endif

                            @if($type->has_extended_warranty)
                                <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                    <i class="fas fa-shield-alt mr-1"></i> Bảo hành {{ $type->extended_warranty_days }} ngày
                                </span>
                            @endif
                        </div>
                    </div>

                    <p class="text-gray-600 text-sm mb-4">{{ $type->description }}</p>

                    <a href="{{ route('customer.customer-types.show', $type->id) }}" class="inline-block w-full text-center px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                        Xem chi tiết
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Thông tin về cách thức nâng cấp -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Làm thế nào để nâng cấp?</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center mr-4">
                            <i class="fas fa-shopping-bag text-pink-500"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-medium text-gray-800">Sử dụng dịch vụ</h4>
                            <p class="text-gray-600">Đặt lịch và sử dụng các dịch vụ của chúng tôi để tích lũy chi tiêu.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center mr-4">
                            <i class="fas fa-chart-line text-pink-500"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-medium text-gray-800">Đạt ngưỡng chi tiêu</h4>
                            <p class="text-gray-600">Khi tổng chi tiêu của bạn đạt đến ngưỡng của một hạng thành viên, bạn sẽ được tự động nâng cấp.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center mr-4">
                            <i class="fas fa-gift text-pink-500"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-medium text-gray-800">Tận hưởng đặc quyền</h4>
                            <p class="text-gray-600">Mỗi hạng thành viên sẽ có những đặc quyền riêng biệt, giúp bạn có trải nghiệm tốt hơn.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
