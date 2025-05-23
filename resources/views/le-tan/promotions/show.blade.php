@extends('layouts.le-tan')

@section('header', 'Chi tiết khuyến mãi')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Chi tiết khuyến mãi</h2>
                <p class="text-sm text-gray-600 mt-1">Thông tin chi tiết về chương trình khuyến mãi</p>
            </div>
            <div class="flex space-x-3">
                @if(auth()->user()->hasAnyPermission('promotions', 'edit'))
                <a href="{{ route('le-tan.promotions.edit', $promotion->id) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Chỉnh sửa
                </a>
                @endif
                
                <a href="{{ route('le-tan.promotions.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Thông tin cơ bản -->
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Thông tin cơ bản</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Mã khuyến mãi</label>
                            <div class="mt-1 flex items-center">
                                <span class="text-lg font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg">{{ $promotion->code }}</span>
                                <button onclick="copyToClipboard('{{ $promotion->code }}')" 
                                        class="ml-2 text-gray-500 hover:text-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">Loại giảm giá</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
                                {{ $promotion->discount_type == 'percentage' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $promotion->discount_type == 'percentage' ? 'Phần trăm' : 'Số tiền cố định' }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">Giá trị giảm</label>
                            <div class="mt-1 text-2xl font-bold text-green-600">
                                @if($promotion->discount_type == 'percentage')
                                    {{ $promotion->discount_value }}%
                                @else
                                    {{ number_format($promotion->discount_value) }}đ
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">Trạng thái</label>
                            @php
                                $now = now();
                                $isActive = $promotion->is_active && 
                                           $promotion->start_date <= $now && 
                                           $promotion->end_date >= $now &&
                                           (!$promotion->usage_limit || $promotion->usage_count < $promotion->usage_limit);
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
                                {{ $isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $isActive ? 'Đang hoạt động' : 'Không hoạt động' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Mô tả -->
                @if($promotion->description)
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Mô tả</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $promotion->description }}</p>
                </div>
                @endif
            </div>

            <!-- Thông tin thời gian và sử dụng -->
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Thời gian áp dụng</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Ngày bắt đầu</label>
                            <div class="mt-1 text-lg font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($promotion->start_date)->format('d/m/Y') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($promotion->start_date)->diffForHumans() }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">Ngày kết thúc</label>
                            <div class="mt-1 text-lg font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($promotion->end_date)->format('d/m/Y') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($promotion->end_date)->diffForHumans() }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">Thời gian còn lại</label>
                            @if($promotion->end_date >= now())
                                <div class="mt-1 text-lg font-semibold text-green-600">
                                    {{ \Carbon\Carbon::parse($promotion->end_date)->diffForHumans(null, false, false, 2) }}
                                </div>
                            @else
                                <div class="mt-1 text-lg font-semibold text-red-600">
                                    Đã hết hạn
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Thống kê sử dụng</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Số lần đã sử dụng</label>
                            <div class="mt-1 text-2xl font-bold text-blue-600">
                                {{ $promotion->usage_count }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">Giới hạn sử dụng</label>
                            <div class="mt-1 text-lg font-semibold text-gray-800">
                                @if($promotion->usage_limit)
                                    {{ $promotion->usage_limit }} lần
                                @else
                                    Không giới hạn
                                @endif
                            </div>
                        </div>

                        @if($promotion->usage_limit)
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Tỷ lệ sử dụng</label>
                            <div class="mt-2">
                                <div class="bg-gray-200 rounded-full h-3">
                                    @php
                                        $percentage = ($promotion->usage_count / $promotion->usage_limit) * 100;
                                        $percentage = min($percentage, 100);
                                    @endphp
                                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" 
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="mt-1 text-sm text-gray-600">
                                    {{ number_format($percentage, 1) }}% đã sử dụng
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Thông tin tạo -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Thông tin tạo</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Người tạo</label>
                            <div class="mt-1 text-gray-800">
                                @if($promotion->creator)
                                    {{ $promotion->creator->first_name }} {{ $promotion->creator->last_name }}
                                @else
                                    Không xác định
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">Ngày tạo</label>
                            <div class="mt-1 text-gray-800">
                                {{ $promotion->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        @if($promotion->updated_at != $promotion->created_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Cập nhật lần cuối</label>
                            <div class="mt-1 text-gray-800">
                                {{ $promotion->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        @if(auth()->user()->hasAnyPermission('promotions', 'edit') || auth()->user()->hasAnyPermission('promotions', 'delete'))
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex justify-end space-x-4">
                @if(auth()->user()->hasAnyPermission('promotions', 'edit'))
                <a href="{{ route('le-tan.promotions.edit', $promotion->id) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    Chỉnh sửa khuyến mãi
                </a>
                @endif
                
                @if(auth()->user()->hasAnyPermission('promotions', 'delete'))
                <form action="{{ route('le-tan.promotions.destroy', $promotion->id) }}" 
                      method="POST" class="inline"
                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa khuyến mãi này? Hành động này không thể hoàn tác.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        Xóa khuyến mãi
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Hiển thị thông báo thành công
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        notification.textContent = 'Đã sao chép mã khuyến mãi!';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    });
}
</script>
@endsection
