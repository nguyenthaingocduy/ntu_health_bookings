@extends('layouts.admin')

@section('title', 'Chi tiết lịch hẹn')

@section('header', 'Chi tiết lịch hẹn')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Appointment Details -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold">Lịch hẹn #{{ $appointment->code }}</h2>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @switch($appointment->status)
                            @case('pending')
                                bg-yellow-100 text-yellow-800
                                @break
                            @case('confirmed')
                                bg-blue-100 text-blue-800
                                @break
                            @case('completed')
                                bg-green-100 text-green-800
                                @break
                            @case('cancelled')
                                bg-red-100 text-red-800
                                @break
                        @endswitch">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer Information -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Thông tin khách hàng</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-gray-600">Họ và tên</p>
                                <p class="font-semibold">{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Số điện thoại</p>
                                <p class="font-semibold">{{ $appointment->user->phone }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Email</p>
                                <p class="font-semibold">{{ $appointment->user->email }}</p>
                            </div>
                            @if($appointment->notes)
                            <div>
                                <p class="text-gray-600">Ghi chú</p>
                                <p class="font-semibold">{{ $appointment->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Service Information -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Thông tin dịch vụ</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-gray-600">Dịch vụ</p>
                                <p class="font-semibold">{{ $appointment->service->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Giá</p>
                                <p class="font-semibold">{{ number_format($appointment->service->price) }}đ</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Thời gian</p>
                                <p class="font-semibold">{{ $appointment->service->formattedDuration }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Danh mục</p>
                                <p class="font-semibold">{{ optional($appointment->service->category)->name ?? 'Không có danh mục' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointment Time -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-4">Thời gian hẹn</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <i class="fas fa-calendar text-gray-500 mr-2"></i>
                            <span class="font-semibold">{{ $appointment->date_appointments->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-gray-500 mr-2"></i>
                            <span class="font-semibold">{{ optional($appointment->timeAppointment)->started_time }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex space-x-4">
                    @if($appointment->status == 'pending')
                    <form action="{{ route('admin.appointments.confirm', $appointment) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition">
                            <i class="fas fa-check mr-2"></i>Xác nhận
                        </button>
                    </form>
                    @endif

                    @if(in_array($appointment->status, ['pending', 'confirmed']))
                    <form action="{{ route('admin.appointments.cancel', $appointment) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition"
                            onclick="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?')">
                            <i class="fas fa-times mr-2"></i>Hủy lịch hẹn
                        </button>
                    </form>
                    @endif

                    @if($appointment->status == 'confirmed')
                    <form action="{{ route('admin.appointments.complete', $appointment) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                            <i class="fas fa-check-double mr-2"></i>Hoàn thành
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment History -->
    <div>
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Lịch sử thay đổi</h3>

                <div class="space-y-4">
                    @if(isset($appointment->history) && count($appointment->history) > 0)
                        @foreach($appointment->history as $history)
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-4">
                                <i class="fas fa-history text-gray-500"></i>
                            </div>
                            <div>
                                <p class="font-semibold">{{ $history->description }}</p>
                                <p class="text-sm text-gray-500">{{ $history->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">Không có lịch sử thay đổi</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Customer History -->
        <div class="bg-white rounded-lg shadow-sm mt-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Lịch sử khách hàng</h3>

                <div class="space-y-4">
                    @forelse($customerHistory as $history)
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-check text-gray-500"></i>
                        </div>
                        <div>
                            <p class="font-semibold">{{ $history->service->name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $history->date_appointments->format('d/m/Y') }} - {{ optional($history->timeAppointment)->started_time }}
                            </p>
                            <span class="px-2 py-1 rounded-full text-xs
                                @switch($history->status)
                                    @case('completed')
                                        bg-green-100 text-green-800
                                        @break
                                    @case('cancelled')
                                        bg-red-100 text-red-800
                                        @break
                                @endswitch">
                                {{ ucfirst($history->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500">Không có lịch sử</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection