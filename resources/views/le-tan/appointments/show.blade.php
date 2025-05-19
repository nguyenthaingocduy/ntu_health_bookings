@extends('layouts.le-tan')

@section('title', 'Chi tiết lịch hẹn')

@section('header', 'Chi tiết lịch hẹn')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chi tiết lịch hẹn</h1>
            <p class="text-sm text-gray-500 mt-1">Xem thông tin chi tiết về lịch hẹn</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('le-tan.appointments.index') }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
            @if($appointment->status == 'pending' || $appointment->status == 'confirmed')
            <a href="{{ route('le-tan.appointments.edit', $appointment->id) }}" class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Chỉnh sửa
            </a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="border-b border-gray-200 pb-6 mb-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Lịch hẹn #{{ $appointment->appointment_code ?? 'N/A' }}</h2>
                        <p class="text-sm text-gray-500 mt-1">Ngày tạo: {{ $appointment->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        @if($appointment->status == 'pending')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Chờ xác nhận
                            </span>
                        @elseif($appointment->status == 'confirmed')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Đã xác nhận
                            </span>
                        @elseif($appointment->status == 'completed')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Đã hoàn thành
                            </span>
                        @elseif($appointment->status == 'cancelled')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Đã hủy
                            </span>
                        @elseif($appointment->status == 'no-show')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Không đến
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ $appointment->status }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Thông tin khách hàng</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-900">{{ $appointment->customer->full_name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Email: {{ $appointment->customer->email ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Điện thoại: {{ $appointment->customer->phone ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Địa chỉ: {{ $appointment->customer->address ?? 'N/A' }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Thông tin lịch hẹn</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-2">
                            <p class="text-sm font-medium text-gray-600">Ngày hẹn:</p>
                            <p class="text-sm text-gray-900">
                                @php
                                    $dateAppointments = $appointment->date_appointments;
                                    $formattedDate = 'N/A';

                                    if ($dateAppointments) {
                                        if ($dateAppointments instanceof \Carbon\Carbon) {
                                            $formattedDate = $dateAppointments->format('d/m/Y');
                                        } elseif (is_string($dateAppointments)) {
                                            try {
                                                $formattedDate = \Carbon\Carbon::parse($dateAppointments)->format('d/m/Y');
                                            } catch (\Exception $e) {
                                                $formattedDate = $dateAppointments;
                                            }
                                        }
                                    }
                                @endphp
                                {{ $formattedDate }}
                            </p>

                            <p class="text-sm font-medium text-gray-600">Giờ hẹn:</p>
                            <p class="text-sm text-gray-900">
                                @if($appointment->timeSlot)
                                    @php
                                        $startTime = $appointment->timeSlot->start_time;
                                        $endTime = $appointment->timeSlot->end_time;

                                        $startTimeFormatted = is_string($startTime) ? substr($startTime, 0, 5) : $startTime->format('H:i');
                                        $endTimeFormatted = is_string($endTime) ? substr($endTime, 0, 5) : $endTime->format('H:i');
                                    @endphp
                                    {{ $startTimeFormatted }} - {{ $endTimeFormatted }}
                                @else
                                    N/A
                                @endif
                            </p>

                            <p class="text-sm font-medium text-gray-600">Tên dịch vụ</p>
                            <p class="text-sm font-bold text-gray-900">{{ $appointment->service->name ?? 'N/A' }}</p>

                            <p class="text-sm font-medium text-gray-600 mt-3">Giá</p>
                            @php
                                // Lấy giá gốc và giá sau khuyến mãi
                                $originalPrice = $appointment->service->price ?? 0;
                                $finalPrice = $appointment->final_price ?? $originalPrice;

                                // Đảm bảo giá cuối cùng được tính đúng
                                if ($appointment->discount_amount > 0) {
                                    $finalPrice = $originalPrice - $appointment->discount_amount;
                                } elseif ($appointment->direct_discount_percent > 0) {
                                    $finalPrice = $originalPrice * (1 - $appointment->direct_discount_percent / 100);
                                }

                                // Tính phần trăm giảm giá
                                $discountPercent = 0;
                                if ($appointment->direct_discount_percent > 0) {
                                    $discountPercent = $appointment->direct_discount_percent;
                                } elseif ($finalPrice < $originalPrice) {
                                    $discountPercent = round(($originalPrice - $finalPrice) / $originalPrice * 100);
                                }

                                // Tính tiết kiệm
                                $totalSavings = $originalPrice - $finalPrice;
                                $savingsPercent = $originalPrice > 0 ? round(($totalSavings / $originalPrice) * 100, 1) : 0;

                                // Log để debug
                                \Illuminate\Support\Facades\Log::info('Chi tiết giá trong trang chi tiết lịch hẹn', [
                                    'appointment_id' => $appointment->id,
                                    'original_price' => $originalPrice,
                                    'final_price' => $finalPrice,
                                    'discount_amount' => $appointment->discount_amount,
                                    'direct_discount_percent' => $appointment->direct_discount_percent,
                                    'discount_percent' => $discountPercent,
                                    'total_savings' => $totalSavings,
                                    'savings_percent' => $savingsPercent
                                ]);
                            @endphp

                            @if($finalPrice < $originalPrice)
                                <p class="text-sm">
                                    <span class="text-pink-500 font-bold text-lg">{{ number_format($finalPrice, 0, ',', '.') }}đ</span>
                                    <span class="text-gray-500 line-through ml-2">{{ number_format($originalPrice, 0, ',', '.') }}đ</span>
                                </p>
                                <p class="mt-1">
                                    <span class="bg-pink-100 text-pink-800 text-xs font-semibold px-2 py-1 rounded-full">
                                        Giảm {{ $discountPercent }}%
                                    </span>
                                </p>

                                <div class="mt-2 bg-gray-50 p-2 rounded-md text-xs">
                                    <div class="flex items-center text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Tiết kiệm: <span class="font-semibold">{{ number_format($totalSavings, 0, ',', '.') }}đ ({{ $savingsPercent }}%)</span></span>
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
                                <p class="text-sm font-semibold text-pink-500">{{ number_format($originalPrice, 0, ',', '.') }}đ</p>
                            @endif

                            <p class="text-sm font-medium text-gray-600">Nhân viên phụ trách:</p>
                            <p class="text-sm text-gray-900">
                                @if($appointment->employee)
                                    {{ $appointment->employee->full_name }}
                                    @if($appointment->employee->phone)
                                        <span class="text-xs text-gray-500">({{ $appointment->employee->phone }})</span>
                                    @endif
                                @else
                                    <span class="text-yellow-500 font-medium">Chưa phân công</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($appointment->notes)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Ghi chú</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-900">{{ $appointment->notes }}</p>
                </div>
            </div>
            @endif

            @if($appointment->status == 'pending')
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('le-tan.appointments.assign-staff', $appointment->id) }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Xác nhận và phân công nhân viên
                </a>

                <button onclick="confirmCancel('{{ $appointment->id }}')" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Hủy lịch hẹn
                </button>
                <form id="cancel-form-{{ $appointment->id }}" action="{{ route('le-tan.appointments.cancel', $appointment->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('PUT')
                </form>
            </div>
            @endif

            @if($appointment->status == 'confirmed')
            <div class="flex justify-end space-x-4 mt-6">
                <button onclick="confirmComplete('{{ $appointment->id }}')" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Hoàn thành lịch hẹn
                </button>
                <form id="complete-form-{{ $appointment->id }}" action="{{ route('le-tan.appointments.complete', $appointment->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('PUT')
                </form>

                <button onclick="confirmCancel('{{ $appointment->id }}')" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Hủy lịch hẹn
                </button>
                <form id="cancel-form-{{ $appointment->id }}" action="{{ route('le-tan.appointments.cancel', $appointment->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('PUT')
                </form>
            </div>
            @endif

            @if($appointment->status == 'completed' && !$appointment->payment)
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('le-tan.payments.create') }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Tạo thanh toán
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script>
    function confirmCancel(id) {
        if (confirm('Bạn có chắc chắn muốn hủy lịch hẹn này không?')) {
            document.getElementById('cancel-form-' + id).submit();
        }
    }

    function confirmComplete(id) {
        if (confirm('Bạn có chắc chắn muốn hoàn thành lịch hẹn này không?')) {
            document.getElementById('complete-form-' + id).submit();
        }
    }
</script>
@endsection
@endsection
