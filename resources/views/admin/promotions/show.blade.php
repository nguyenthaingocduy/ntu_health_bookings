@extends('layouts.admin')

@section('title', 'Chi tiết khuyến mãi')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chi tiết khuyến mãi</h1>
            <p class="text-sm text-gray-500 mt-1">Xem thông tin chi tiết khuyến mãi</p>
        </div>
        <div class="flex space-x-2">
            @can('promotions.edit')
            <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="flex items-center px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Chỉnh sửa
            </a>
            @endcan
            <a href="{{ route('admin.promotions.index') }}" class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-150 shadow-sm border border-gray-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    <nav class="mb-8">
        <ol class="flex text-sm text-gray-500">
            <li class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-pink-500">Tổng quan</a>
                <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </li>
            <li class="flex items-center">
                <a href="{{ route('admin.promotions.index') }}" class="hover:text-pink-500">Quản lý khuyến mãi</a>
                <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </li>
            <li class="text-pink-500 font-medium">{{ $promotion->title }}</li>
        </ol>
    </nav>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-pink-50 to-pink-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 9H10a3 3 0 013 3v1a1 1 0 102 0v-1a5 5 0 00-5-5H8.414l1.293-1.293z" clip-rule="evenodd"></path>
                        </svg>
                        Thông tin khuyến mãi
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Thông tin cơ bản</h4>
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <div class="mb-3">
                                    <p class="text-sm text-gray-500">Tiêu đề</p>
                                    <p class="text-base font-medium text-gray-800">{{ $promotion->title }}</p>
                                </div>
                                <div class="mb-3">
                                    <p class="text-sm text-gray-500">Mã khuyến mãi</p>
                                    <div class="flex items-center">
                                        <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded font-mono text-base">{{ $promotion->code }}</span>
                                        <button type="button" class="ml-2 text-blue-500 hover:text-blue-700" onclick="copyToClipboard('{{ $promotion->code }}')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <p class="text-sm text-gray-500">Trạng thái</p>
                                    <div class="mt-1">
                                        {!! $promotion->status_badge !!}
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Mô tả</p>
                                    <p class="text-base text-gray-800">{{ $promotion->description ?: 'Không có mô tả' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Chi tiết giảm giá</h4>
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <div class="mb-3">
                                    <p class="text-sm text-gray-500">Loại giảm giá</p>
                                    <p class="text-base font-medium text-gray-800">
                                        {{ $promotion->discount_type == 'percentage' ? 'Phần trăm (%)' : 'Số tiền cố định (VNĐ)' }}
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <p class="text-sm text-gray-500">Giá trị giảm giá</p>
                                    <p class="text-base font-medium text-gray-800">{{ $promotion->formatted_discount_value }}</p>
                                </div>
                                <div class="mb-3">
                                    <p class="text-sm text-gray-500">Giá trị đơn hàng tối thiểu</p>
                                    <p class="text-base font-medium text-gray-800">
                                        {{ $promotion->minimum_purchase > 0 ? $promotion->formatted_minimum_purchase : 'Không giới hạn' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Giảm giá tối đa</p>
                                    <p class="text-base font-medium text-gray-800">
                                        {{ $promotion->maximum_discount ? $promotion->formatted_maximum_discount : 'Không giới hạn' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Thời gian</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="mb-3">
                                    <p class="text-sm text-gray-500">Ngày bắt đầu</p>
                                    <p class="text-base font-medium text-gray-800">{{ $promotion->start_date->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Ngày kết thúc</p>
                                    <p class="text-base font-medium text-gray-800">{{ $promotion->end_date->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Sử dụng</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="mb-3">
                                    <p class="text-sm text-gray-500">Số lần đã sử dụng</p>
                                    <p class="text-base font-medium text-gray-800">{{ $promotion->usage_count }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Giới hạn sử dụng</p>
                                    <p class="text-base font-medium text-gray-800">
                                        {{ $promotion->usage_limit ? $promotion->usage_limit : 'Không giới hạn' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div>
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 sticky top-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Thông tin bổ sung
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Thông tin tạo</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="mb-3">
                                <p class="text-sm text-gray-500">Người tạo</p>
                                <p class="text-base font-medium text-gray-800">{{ $promotion->creator->full_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Ngày tạo</p>
                                <p class="text-base font-medium text-gray-800">{{ $promotion->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Thao tác</h4>
                        <div class="space-y-3">
                            @can('promotions.edit')
                            <form action="{{ route('admin.promotions.toggle-active', $promotion->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 m-2 {{ $promotion->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg transition-colors duration-150">
                                    @if($promotion->is_active)
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Vô hiệu hóa
                                    @else
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Kích hoạt
                                    @endif
                                </button>
                            </form>
                            @endcan
                            
                            @can('promotions.edit')
                            <form action="{{ route('admin.promotions.reset-usage', $promotion->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn đặt lại số lần sử dụng về 0?');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-yellow-100 text-yellow-700 hover:bg-yellow-200 rounded-lg transition-colors duration-150 m-2">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Đặt lại số lần sử dụng
                                </button>
                            </form>
                            @endcan
                            
                            @can('promotions.delete')
                            <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg transition-colors duration-150 m-2">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Xóa khuyến mãi
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Đã sao chép mã khuyến mãi: ' + text);
        }, function(err) {
            console.error('Không thể sao chép: ', err);
        });
    }
</script>
@endsection
