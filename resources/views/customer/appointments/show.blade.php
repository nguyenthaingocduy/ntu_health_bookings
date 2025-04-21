@extends('layouts.app')

@section('title', 'Chi tiết lịch hẹn')

@section('content')
<section class="bg-gray-900 text-white py-16">
    <div class="container mx-auto px-6">
        <h1 class="text-4xl font-bold mb-4">Chi tiết lịch hẹn</h1>
        <p class="text-xl text-gray-300">Xem thông tin chi tiết về lịch hẹn của bạn.</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-6">
        <div class="mb-6">
            <a href="{{ route('customer.appointments.index') }}" class="text-pink-500 hover:underline">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                    <h2 class="text-2xl font-bold">Lịch hẹn #{{ substr($appointment->id, 0, 8) }}</h2>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold inline-flex items-center
                        @if($appointment->status == 'pending')
                            bg-yellow-100 text-yellow-800
                        @elseif($appointment->status == 'confirmed')
                            bg-blue-100 text-blue-800
                        @elseif($appointment->status == 'completed')
                            bg-green-100 text-green-800
                        @elseif($appointment->status == 'cancelled')
                            bg-red-100 text-red-800
                        @else
                            bg-gray-100 text-gray-800
                        @endif">
                        @if($appointment->status == 'pending')
                            <i class="fas fa-clock mr-2"></i> Chờ xác nhận
                        @elseif($appointment->status == 'confirmed')
                            <i class="fas fa-check mr-2"></i> Đã xác nhận
                        @elseif($appointment->status == 'completed')
                            <i class="fas fa-check-double mr-2"></i> Hoàn thành
                        @elseif($appointment->status == 'cancelled')
                            <i class="fas fa-times mr-2"></i> Đã hủy
                        @else
                            {{ $appointment->status }}
                        @endif
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Thông tin dịch vụ -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Thông tin dịch vụ</h3>
                        <div class="space-y-4">
                            @if($appointment->service)
                            <div>
                                <p class="text-gray-600">Tên dịch vụ</p>
                                <p class="font-semibold text-lg">{{ $appointment->service->name }}</p>
                            </div>
                            
                            @if(isset($appointment->service->price))
                            <div>
                                <p class="text-gray-600">Giá</p>
                                <p class="font-semibold text-pink-500">{{ number_format($appointment->service->price) }}đ</p>
                            </div>
                            @endif
                            
                            @if(isset($appointment->service->duration))
                            <div>
                                <p class="text-gray-600">Thời gian thực hiện</p>
                                <p class="font-semibold">{{ $appointment->service->duration }} phút</p>
                            </div>
                            @endif
                            
                            @if(isset($appointment->service->category) && $appointment->service->category)
                            <div>
                                <p class="text-gray-600">Danh mục</p>
                                <p class="font-semibold">{{ $appointment->service->category->name }}</p>
                            </div>
                            @endif
                            
                            @if(isset($appointment->service->descriptive))
                            <div>
                                <p class="text-gray-600">Mô tả</p>
                                <p>{{ $appointment->service->descriptive }}</p>
                            </div>
                            @endif
                            @else
                            <p class="text-gray-500 italic">Không có thông tin dịch vụ</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Thông tin lịch hẹn -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Thông tin lịch hẹn</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-gray-600">Ngày đặt lịch</p>
                                <p class="font-semibold">{{ $appointment->created_at->format('d/m/y') }}</p>
                            </div>
                            
                            <div>
                                <p class="text-gray-600">Ngày hẹn</p>
                                <p class="font-semibold">
                                    <i class="fas fa-calendar-alt text-pink-500 mr-2"></i>
                                    {{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y') }}
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-gray-600">Giờ hẹn</p>
                                <p class="font-semibold">
                                    <i class="fas fa-clock text-pink-500 mr-2"></i>
                                    @if($appointment->timeAppointment)
                                        {{ \Carbon\Carbon::parse($appointment->timeAppointment->started_time)->format('H:i') }}
                                    @else
                                        --:--
                                    @endif
                                </p>
                            </div>
                            
                            @if($appointment->notes)
                            <div>
                                <p class="text-gray-600">Ghi chú</p>
                                <p class="italic bg-gray-50 p-3 rounded-lg">{{ $appointment->notes }}</p>
                            </div>
                            @endif
                            
                            @if($appointment->employee)
                            <div>
                                <p class="text-gray-600">Nhân viên phụ trách</p>
                                <p class="font-semibold">
                                    <i class="fas fa-user text-pink-500 mr-2"></i>
                                    {{ $appointment->employee->first_name ?? '' }} {{ $appointment->employee->last_name ?? '' }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Hành động -->
                @if(in_array($appointment->status, ['pending', 'confirmed']))
                <div class="mt-8 border-t pt-6">
                    <h3 class="font-semibold mb-4">Hành động</h3>
                    <form action="{{ route('customer.appointments.cancel', $appointment->id) }}" method="POST" class="inline"
                        onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?');">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                            <i class="fas fa-times-circle mr-2"></i> Hủy lịch hẹn
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection