@extends('layouts.nvkt-new')

@section('title', 'Chi tiết khách hàng')

@section('header', 'Chi tiết khách hàng')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $customer->first_name }} {{ $customer->last_name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Thông tin chi tiết về khách hàng</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('nvkt.customers.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
            <a href="{{ route('nvkt.customers.service-history', $customer->id) }}" class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Lịch sử dịch vụ
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="md:flex">
            <div class="md:flex-shrink-0 md:w-1/4 bg-gray-50 p-8">
                <div class="flex flex-col items-center">
                    <img class="h-32 w-32 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ $customer->first_name }}&background=0D8ABC&color=fff&size=128" alt="{{ $customer->first_name }}">
                    <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</h2>
                    <p class="text-sm text-gray-500">Khách hàng</p>

                    <div class="mt-6 w-full">
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-500">Mã khách hàng</span>
                            <span class="text-sm font-medium text-gray-900">{{ substr($customer->id, 0, 8) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-500">Ngày đăng ký</span>
                            <span class="text-sm font-medium text-gray-900">{{ $customer->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-8 md:w-3/4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin cá nhân</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Họ và tên</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Giới tính</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $customer->gender == 'male' ? 'Nam' : 'Nữ' }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ngày sinh</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $customer->date_of_birth ? $customer->date_of_birth->format('d/m/Y') : 'Chưa cập nhật' }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $customer->address ?: 'Chưa cập nhật' }}</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin liên hệ</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $customer->email }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $customer->phone ?: 'Chưa cập nhật' }}</div>
                            </div>
                        </div>

                        <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4">Thông tin sức khỏe</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tiền sử bệnh</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $customer->medical_history ?: 'Không có' }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Dị ứng</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $customer->allergies ?: 'Không có' }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ghi chú</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $customer->notes ?: 'Không có' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
