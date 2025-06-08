@extends('layouts.admin')

@section('title', 'Quản lý nhân viên')

@section('header', 'Quản lý nhân viên')

@section('content')
<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form action="{{ route('admin.employees.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Tìm kiếm theo tên hoặc email..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
        </div>

        <div class="w-full md:w-auto">
            <select name="clinic" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                <option value="">Tất cả cơ sở</option>
                @foreach($clinics as $clinic)
                    <option value="{{ $clinic->id }}" {{ request('clinic') == $clinic->id ? 'selected' : '' }}>
                        {{ $clinic->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="w-full md:w-auto">
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                <option value="">Tất cả trạng thái</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang làm việc</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Đã nghỉ việc</option>
            </select>
        </div>

        <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded-lg hover:bg-pink-600 transition">
            <i class="fas fa-search mr-2"></i>Tìm kiếm
        </button>
    </form>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-users text-blue-500 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-600">Tổng số nhân viên</p>
                <p class="text-2xl font-bold">{{ $statistics['total'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-600">Đang làm việc</p>
                <p class="text-2xl font-bold">{{ $statistics['active'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-times-circle text-red-500 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-600">Đã nghỉ việc</p>
                <p class="text-2xl font-bold">{{ $statistics['inactive'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-star text-yellow-500 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-600">Đánh giá trung bình</p>
                <p class="text-2xl font-bold">{{ number_format($statistics['average_rating'], 1) }}/5</p>
            </div>
        </div>
    </div>
</div>

<!-- Employees Table -->
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold">Danh sách nhân viên</h3>
            <a href="{{ route('admin.employees.create') }}" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition">
                <i class="fas fa-plus mr-2"></i>Thêm nhân viên
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b">
                        <th class="pb-4 font-semibold">Ảnh</th>
                        <th class="pb-4 font-semibold">Thông tin</th>
                        <th class="pb-4 font-semibold">Cơ sở</th>
                        <th class="pb-4 font-semibold">Dịch vụ</th>
                        <th class="pb-4 font-semibold">Trạng thái</th>
                        <th class="pb-4 font-semibold">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($employees as $employee)
                    <tr>
                        <td class="py-4">
                            <img src="{{ secure_asset($employee->avatar_url ?? 'images/employees/default-avatar.svg') }}" alt="{{ $employee->name }}"
                                class="w-16 h-16 rounded-full object-cover">
                        </td>
                        <td class="py-4">
                            <div>
                                <p class="font-semibold">{{ $employee->name }}</p>
                                <p class="text-sm text-gray-500">{{ $employee->email }}</p>
                                <p class="text-sm text-gray-500">{{ $employee->phone }}</p>
                            </div>
                        </td>
                        <td class="py-4">
                            <span class="px-3 py-1 bg-pink-100 text-pink-500 rounded-full text-sm">
                                {{ $employee->clinic->name }}
                            </span>
                        </td>
                        <td class="py-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach($employee->services as $service)
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">
                                    {{ $service->name }}
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-4">
                            <span class="px-3 py-1 rounded-full text-sm
                                {{ $employee->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $employee->status == 'active' ? 'Đang làm việc' : 'Đã nghỉ việc' }}
                            </span>
                        </td>
                        <td class="py-4">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.employees.show', $employee) }}"
                                    class="text-blue-500 hover:text-blue-700" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.employees.edit', $employee) }}"
                                    class="text-yellow-500 hover:text-yellow-700" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')"
                                        title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                                <form action="{{ route('admin.employees.toggle-status', $employee) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-500 hover:text-gray-700"
                                        title="{{ $employee->status == 'active' ? 'Cho nghỉ việc' : 'Kích hoạt' }}">
                                        <i class="fas {{ $employee->status == 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            Không tìm thấy nhân viên nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $employees->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection