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
                                @php
                                    // Log để debug
                                    \Illuminate\Support\Facades\Log::info('Chi tiết giá trong trang chi tiết lịch hẹn khách hàng', [
                                        'appointment_id' => $appointment->id,
                                        'original_price' => $appointment->service->price,
                                        'final_price' => $appointment->final_price,
                                        'discount_amount' => $appointment->discount_amount,
                                        'direct_discount_percent' => $appointment->direct_discount_percent
                                    ]);
                                @endphp

                                @if($appointment->final_price < $appointment->service->price)
                                    <p class="font-semibold text-pink-500 text-xl">{{ number_format($appointment->final_price, 0, ',', '.') }}đ</p>
                                    <p class="text-gray-500 line-through text-base font-medium">{{ number_format($appointment->service->price, 0, ',', '.') }}đ</p>
                                    <p class="mt-1">
                                        <span class="bg-pink-100 text-pink-800 text-sm font-semibold px-2 py-1 rounded-full">
                                            Giảm {{ $appointment->direct_discount_percent ?? round(($appointment->service->price - $appointment->final_price) / $appointment->service->price * 100) }}%
                                        </span>
                                    </p>

                                    <div class="mt-2 bg-gray-50 p-2 rounded-md text-xs">
                                        <div class="flex items-center text-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Tiết kiệm: <span class="font-semibold">{{ number_format($appointment->service->price - $appointment->final_price, 0, ',', '.') }}đ ({{ round(($appointment->service->price - $appointment->final_price) / $appointment->service->price * 100, 1) }}%)</span></span>
                                        </div>

                                        @if($appointment->direct_discount_percent > 0)
                                        <div class="flex items-center text-gray-700 mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                            <span>Giảm giá trực tiếp: <span class="font-semibold">{{ $appointment->direct_discount_percent }}%</span></span>
                                        </div>
                                        @endif

                                        @if($appointment->promotion_code)
                                        <div class="flex items-center text-gray-700 mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            <span>Mã đã áp dụng: <span class="font-mono font-semibold">{{ $appointment->promotion_code }}</span></span>
                                        </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="font-semibold text-pink-500">{{ number_format($appointment->service->price, 0, ',', '.') }}đ</p>
                                @endif
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

                            <div>
                                <p class="text-gray-600">Nhân viên phụ trách</p>
                                @if($appointment->employee)
                                <div class="flex items-center mt-2">
                                    <div class="flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full object-cover"
                                            src="https://ui-avatars.com/api/?name={{ $appointment->employee->first_name }}&background=0D8ABC&color=fff&size=128"
                                            alt="{{ $appointment->employee->first_name }}">
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-semibold text-gray-900">
                                            {{ $appointment->employee->first_name ?? '' }} {{ $appointment->employee->last_name ?? '' }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Nhân viên kỹ thuật
                                        </p>
                                    </div>
                                </div>
                                @else
                                <p class="italic text-gray-500">
                                    <i class="fas fa-user-clock text-pink-500 mr-2"></i>
                                    Chưa phân công nhân viên
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hành động -->
                <div class="mt-8 border-t pt-6">
                    <h3 class="font-semibold mb-4">Hành động</h3>

                    <div class="flex flex-wrap gap-3">
                        @if(in_array($appointment->status, ['pending', 'confirmed']))
                            <form action="{{ route('customer.appointments.cancel', $appointment->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?');">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                    <i class="fas fa-times-circle mr-2"></i> Hủy lịch hẹn
                                </button>
                            </form>
                        @endif

                        @php
                            $canDelete = in_array($appointment->status, ['completed', 'cancelled']);
                            $isOld = $appointment->created_at->diffInDays(now()) >= 30;
                        @endphp

                        @if($canDelete || $isOld)
                            <form action="{{ route('customer.appointments.destroy', $appointment->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch hẹn này khỏi lịch sử? Hành động này không thể hoàn tác.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                                    <i class="fas fa-trash-alt mr-2"></i> Xóa khỏi lịch sử
                                </button>
                            </form>
                        @endif
                    </div>

                    @if(!in_array($appointment->status, ['pending', 'confirmed']) && !$canDelete && !$isOld)
                        <p class="text-gray-500 italic">
                            <i class="fas fa-info-circle mr-2"></i>
                            Lịch hẹn này chỉ có thể được xóa khỏi lịch sử khi đã hoàn thành, đã hủy, hoặc đã tồn tại lâu hơn 30 ngày.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection