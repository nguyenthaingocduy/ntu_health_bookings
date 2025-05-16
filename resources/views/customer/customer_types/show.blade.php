@extends('layouts.customer')

@section('title', 'Chi tiết loại khách hàng - ' . $customerType->type_name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <!-- Breadcrumb -->
        <div class="flex items-center text-sm text-gray-500 mb-6">
            <a href="{{ route('customer.customer-types.index') }}" class="hover:text-pink-500">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách loại khách hàng
            </a>
        </div>

        <!-- Tiêu đề trang -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2" style="color: {{ $customerType->color_code }}">{{ $customerType->type_name }}</h1>
            <p class="text-gray-600">{{ $customerType->description }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <!-- Thông tin chính -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Đặc quyền thành viên</h2>

                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-tag text-pink-500"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-800">Giảm giá dịch vụ</h4>
                                    <p class="text-gray-600">Được giảm {{ $customerType->formatted_discount }} cho tất cả các dịch vụ.</p>
                                </div>
                            </div>

                            @if($customerType->has_priority_booking)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-calendar-check text-pink-500"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-800">Ưu tiên đặt lịch</h4>
                                    <p class="text-gray-600">Được ưu tiên khi đặt lịch với mức ưu tiên: {{ $customerType->priority_level }}.</p>
                                </div>
                            </div>
                            @endif

                            @if($customerType->has_personal_consultant)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-user-md text-pink-500"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-800">Tư vấn riêng</h4>
                                    <p class="text-gray-600">Được tư vấn riêng với chuyên gia của chúng tôi.</p>
                                </div>
                            </div>
                            @endif

                            @if($customerType->has_birthday_gift)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-gift text-pink-500"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-800">Quà tặng sinh nhật</h4>
                                    <p class="text-gray-600">Nhận quà tặng đặc biệt vào dịp sinh nhật của bạn.</p>
                                </div>
                            </div>
                            @endif

                            @if($customerType->has_free_service)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-spa text-pink-500"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-800">Dịch vụ miễn phí</h4>
                                    <p class="text-gray-600">Được sử dụng {{ $customerType->free_service_count }} dịch vụ miễn phí.</p>
                                </div>
                            </div>
                            @endif

                            @if($customerType->has_extended_warranty)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-shield-alt text-pink-500"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-800">Bảo hành mở rộng</h4>
                                    <p class="text-gray-600">Được bảo hành thêm {{ $customerType->extended_warranty_days }} ngày cho các dịch vụ.</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Điều kiện duy trì</h2>
                        <p class="text-gray-600 mb-4">Để duy trì hạng thành viên {{ $customerType->type_name }}, bạn cần đạt tổng chi tiêu tối thiểu {{ number_format($customerType->min_spending, 0, ',', '.') }} VNĐ trong vòng 12 tháng.</p>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-2">Lưu ý:</h4>
                            <ul class="list-disc list-inside text-gray-600 space-y-1">
                                <li>Hạng thành viên được đánh giá lại vào ngày 1 hàng tháng.</li>
                                <li>Nếu tổng chi tiêu của bạn không đạt mức tối thiểu, hạng thành viên có thể bị hạ xuống.</li>
                                <li>Chi tiêu được tính dựa trên các đơn hàng đã hoàn thành.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Trạng thái hiện tại -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Trạng thái của bạn</h3>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Hạng thành viên hiện tại</p>
                                <p class="font-medium" style="color: {{ $user->customerType->color_code ?? '#6B7280' }}">
                                    <i class="fas fa-crown mr-2"></i> {{ $user->customerType->type_name ?? 'Regular' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tổng chi tiêu của bạn</p>
                                <p class="font-medium text-gray-800">{{ number_format($totalSpending, 0, ',', '.') }} VNĐ</p>
                            </div>

                            @if($user->type_id != $customerType->id && $totalSpending < $customerType->min_spending)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Cần chi tiêu thêm để đạt hạng</p>
                                <p class="font-medium text-pink-600">{{ number_format($remainingSpending, 0, ',', '.') }} VNĐ</p>
                            </div>

                            <div class="mt-2">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    @php
                                        $percentage = min(100, ($totalSpending / $customerType->min_spending) * 100);
                                    @endphp
                                    <div class="bg-pink-500 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ round($percentage) }}% hoàn thành</p>
                            </div>
                            @elseif($user->type_id == $customerType->id)
                            <div class="bg-green-50 text-green-800 rounded-lg p-4">
                                <p class="font-medium">Bạn đang ở hạng thành viên này!</p>
                            </div>
                            @else
                            <div class="bg-blue-50 text-blue-800 rounded-lg p-4">
                                <p class="font-medium">Bạn đã đủ điều kiện để nâng cấp lên hạng này!</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Đặt lịch ngay -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Muốn nâng cấp nhanh hơn?</h3>
                        <p class="text-gray-600 mb-4">Đặt lịch sử dụng dịch vụ ngay hôm nay để tích lũy chi tiêu và nâng cấp hạng thành viên của bạn.</p>

                        <a href="{{ route('services.index') }}" class="inline-block w-full text-center px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                            Xem dịch vụ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
