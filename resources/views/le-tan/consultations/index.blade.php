@extends('layouts.le-tan')

@section('title', 'Quản lý tư vấn dịch vụ')

@section('header', 'Quản lý tư vấn dịch vụ')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Quản lý tư vấn dịch vụ</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý các buổi tư vấn dịch vụ cho khách hàng</p>
        </div>
        <div class="flex flex-wrap gap-2">
           
            <a href="{{ route('le-tan.consultations.create') }}" class="flex items-center px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors duration-150 shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tạo tư vấn mới
            </a>
       
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('le-tan.consultations.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                <select id="status" name="status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                    <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Đã chuyển đổi</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Tên khách hàng, dịch vụ..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors duration-150">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Lọc
                </button>
                <a href="{{ route('le-tan.consultations.index') }}" class="ml-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150">
                    Đặt lại
                </a>
            </div>
        </form>
    </div>

    <!-- Danh sách tư vấn -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Khách hàng
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dịch vụ
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ngày đề xuất
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ngày tạo
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($consultations as $consultation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ $consultation->customer->first_name }}&background=0D8ABC&color=fff" alt="{{ $consultation->customer->first_name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $consultation->customer->first_name }} {{ $consultation->customer->last_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $consultation->customer->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $consultation->service->name }}</div>
                            <div class="text-sm text-gray-500">{{ number_format($consultation->service->price, 0, ',', '.') }} VNĐ</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $consultation->recommended_date ? $consultation->recommended_date->format('d/m/Y') : 'Chưa xác định' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($consultation->status == 'pending') bg-yellow-100 text-yellow-800 
                                @elseif($consultation->status == 'converted') bg-green-100 text-green-800 
                                @else bg-red-100 text-red-800 @endif">
                                @if($consultation->status == 'pending') Đang chờ 
                                @elseif($consultation->status == 'converted') Đã chuyển đổi 
                                @else Đã hủy @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $consultation->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('le-tan.consultations.show', $consultation->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                Chi tiết
                            </a>
                            @if($consultation->status == 'pending')
                            <a href="{{ route('le-tan.consultations.edit', $consultation->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                Sửa
                            </a>
                            <a href="{{ route('le-tan.consultations.convert', $consultation->id) }}" class="text-green-600 hover:text-green-900 mr-3">
                                Đặt lịch
                            </a>
                            <form action="{{ route('le-tan.consultations.destroy', $consultation->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Bạn có chắc chắn muốn xóa tư vấn này?')">
                                    Xóa
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            Không có dữ liệu tư vấn dịch vụ
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $consultations->links() }}
        </div>
    </div>
</div>
@endsection
