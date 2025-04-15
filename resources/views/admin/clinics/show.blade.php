@extends('layouts.admin')

@section('title', 'Chi tiết cơ sở')

@section('header', 'Chi tiết cơ sở')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.clinics.index') }}" 
                class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-semibold text-gray-800">{{ $clinic->name }}</h2>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.clinics.edit', $clinic) }}" 
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-edit mr-2"></i>Chỉnh sửa
            </a>
            <form action="{{ route('admin.clinics.destroy', $clinic) }}" method="POST" 
                onsubmit="return confirm('Bạn có chắc chắn muốn xóa cơ sở này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                    <i class="fas fa-trash mr-2"></i>Xóa
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Clinic Information -->
        <div class="md:col-span-2 space-y-6">
            <!-- Image -->
            <div class="relative">
                <img src="{{ $clinic->image_url }}" alt="{{ $clinic->name }}" 
                    class="w-full h-64 object-cover rounded-lg">
                <div class="absolute top-4 right-4">
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        {{ $clinic->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $clinic->status == 'active' ? 'Đang hoạt động' : 'Ngừng hoạt động' }}
                    </span>
                </div>
            </div>

            <!-- Description -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Mô tả</h3>
                <p class="text-gray-600">{{ $clinic->description }}</p>
            </div>

            <!-- Contact Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Thông tin liên hệ</h3>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <i class="fas fa-phone text-gray-500 w-6"></i>
                        <span class="text-gray-600">{{ $clinic->phone }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-gray-500 w-6"></i>
                        <span class="text-gray-600">{{ $clinic->address }}</span>
                    </div>
                </div>
            </div>

            <!-- Services -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Dịch vụ cung cấp</h3>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($clinic->services as $service)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900">{{ $service->name }}</h4>
                        <p class="text-sm text-gray-600">{{ $service->description }}</p>
                        <div class="mt-2 flex justify-between items-center">
                            <span class="text-pink-500 font-medium">{{ number_format($service->price) }}đ</span>
                            <span class="text-gray-500 text-sm">{{ $service->duration }} phút</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Statistics and Employees -->
        <div class="space-y-6">
            <!-- Statistics -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thống kê</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tổng số nhân viên</span>
                            <span class="font-medium text-gray-900">{{ $clinic->employees_count }}</span>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-pink-500 h-2 rounded-full" style="width: {{ ($clinic->employees_count / $total_employees) * 100 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tổng số dịch vụ</span>
                            <span class="font-medium text-gray-900">{{ $clinic->services_count }}</span>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-pink-500 h-2 rounded-full" style="width: {{ ($clinic->services_count / $total_services) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employees -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Nhân viên</h3>
                    <a href="{{ route('admin.employees.create', ['clinic_id' => $clinic->id]) }}" 
                        class="text-pink-500 hover:text-pink-600">
                        <i class="fas fa-plus"></i> Thêm nhân viên
                    </a>
                </div>
                <div class="space-y-4">
                    @foreach($clinic->employees as $employee)
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                        <img src="{{ $employee->image_url }}" alt="{{ $employee->name }}" 
                            class="w-12 h-12 rounded-full object-cover">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $employee->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $employee->position }}</p>
                        </div>
                        <a href="{{ route('admin.employees.show', $employee) }}" 
                            class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 