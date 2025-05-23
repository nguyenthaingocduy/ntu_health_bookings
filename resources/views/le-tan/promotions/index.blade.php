@extends('layouts.le-tan')

@section('header', 'Quản lý khuyến mãi')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Danh sách khuyến mãi</h2>
                <p class="text-sm text-gray-600 mt-1">Quản lý các chương trình khuyến mãi</p>
            </div>
            @if(auth()->user()->hasPermission('promotions.create') || auth()->user()->hasDirectPermission('promotions', 'create'))
            <a href="{{ route('le-tan.promotions.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Thêm khuyến mãi
            </a>
            @endif
        </div>
    </div>

    <!-- Content -->
    <div class="p-6">
        @if($promotions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mã khuyến mãi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Loại giảm giá
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Giá trị
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thời gian
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Sử dụng
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($promotions as $promotion)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $promotion->code }}</div>
                            @if($promotion->description)
                            <div class="text-sm text-gray-500">{{ Str::limit($promotion->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $promotion->discount_type == 'percentage' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $promotion->discount_type == 'percentage' ? 'Phần trăm' : 'Số tiền cố định' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($promotion->discount_type == 'percentage')
                                {{ $promotion->discount_value }}%
                            @else
                                {{ number_format($promotion->discount_value) }}đ
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>{{ \Carbon\Carbon::parse($promotion->start_date)->format('d/m/Y') }}</div>
                            <div class="text-gray-500">đến {{ \Carbon\Carbon::parse($promotion->end_date)->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $promotion->usage_count }}
                            @if($promotion->usage_limit)
                                / {{ $promotion->usage_limit }}
                            @else
                                / ∞
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $now = now();
                                $isActive = $promotion->is_active && 
                                           $promotion->start_date <= $now && 
                                           $promotion->end_date >= $now &&
                                           (!$promotion->usage_limit || $promotion->usage_count < $promotion->usage_limit);
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $isActive ? 'Hoạt động' : 'Không hoạt động' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('le-tan.promotions.show', $promotion->id) }}" 
                                   class="text-blue-600 hover:text-blue-900">Xem</a>
                                
                                @if(auth()->user()->hasPermission('promotions.edit') || auth()->user()->hasDirectPermission('promotions', 'edit'))
                                <a href="{{ route('le-tan.promotions.edit', $promotion->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                                @endif
                                
                                @if(auth()->user()->hasPermission('promotions.delete') || auth()->user()->hasDirectPermission('promotions', 'delete'))
                                <form action="{{ route('le-tan.promotions.destroy', $promotion->id) }}" 
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $promotions->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có khuyến mãi nào</h3>
            <p class="mt-1 text-sm text-gray-500">Bắt đầu bằng cách tạo khuyến mãi đầu tiên.</p>
            @if(auth()->user()->hasPermission('promotions.create') || auth()->user()->hasDirectPermission('promotions', 'create'))
            <div class="mt-6">
                <a href="{{ route('le-tan.promotions.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Thêm khuyến mãi
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
