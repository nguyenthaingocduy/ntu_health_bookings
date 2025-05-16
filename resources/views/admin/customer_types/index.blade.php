@extends('layouts.admin')

@section('title', 'Quản lý loại khách hàng')

@section('header', 'Quản lý loại khách hàng')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">Danh sách loại khách hàng</h3>
        <a href="{{ route('admin.customer-types.create') }}" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition">
            <i class="fas fa-plus mr-2"></i>Thêm loại khách hàng
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="text-left">
                    <th class="px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Tên loại</th>
                    <th class="px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Giảm giá</th>
                    <th class="px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Mức ưu tiên</th>
                    <th class="px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Chi tiêu tối thiểu</th>
                    <th class="px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Số khách hàng</th>
                    <th class="px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($customerTypes as $type)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $type->color_code }}"></div>
                            <span class="font-medium">{{ $type->type_name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $type->formatted_discount }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $type->priority_level }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $type->formatted_min_spending }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $type->users_count }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $type->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $type->is_active ? 'Đang hoạt động' : 'Vô hiệu hóa' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.customer-types.edit', $type) }}" class="text-yellow-500 hover:text-yellow-700" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form action="{{ route('admin.customer-types.toggle-status', $type) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-blue-500 hover:text-blue-700" title="{{ $type->is_active ? 'Vô hiệu hóa' : 'Kích hoạt' }}">
                                    <i class="fas {{ $type->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.customer-types.destroy', $type) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa loại khách hàng này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Không có loại khách hàng nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- <div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-semibold mb-4">Thông tin về loại khách hàng</h3>
    <div class="space-y-4">
        <div class="p-4 bg-blue-50 rounded-lg">
            <h4 class="font-medium text-blue-800 mb-2">Cách thức hoạt động</h4>
            <p class="text-blue-600">Hệ thống phân loại khách hàng giúp bạn quản lý và ưu đãi khách hàng dựa trên mức chi tiêu của họ. Khách hàng sẽ được tự động nâng cấp khi đạt đến mức chi tiêu tối thiểu của loại cao hơn.</p>
        </div>
        
        <div class="p-4 bg-yellow-50 rounded-lg">
            <h4 class="font-medium text-yellow-800 mb-2">Ưu đãi theo loại khách hàng</h4>
            <p class="text-yellow-600">Mỗi loại khách hàng có mức giảm giá mặc định khác nhau. Mức giảm giá này sẽ được áp dụng tự động khi khách hàng đặt lịch sử dụng dịch vụ.</p>
        </div>
        
        <div class="p-4 bg-green-50 rounded-lg">
            <h4 class="font-medium text-green-800 mb-2">Mức ưu tiên</h4>
            <p class="text-green-600">Mức ưu tiên càng cao, khách hàng càng được ưu tiên khi đặt lịch trong trường hợp nhiều người cùng đặt một khung giờ.</p>
        </div>
    </div>
</div> --}}
@endsection
