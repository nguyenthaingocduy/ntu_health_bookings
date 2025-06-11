@extends('layouts.admin')

@section('title', 'Phân công nhân viên kỹ thuật')

@section('header', 'Phân công nhân viên kỹ thuật')

@section('content')
<!-- Appointment Info -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">Thông tin lịch hẹn</h3>
        <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Vui lòng chọn nhân viên kỹ thuật có kinh nghiệm phù hợp với dịch vụ được yêu cầu.
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Mã lịch hẹn</h3>
            </div>
            <div class="p-4">
                <div class="flex items-center">
                    <i class="fas fa-hashtag text-gray-400 mr-3"></i>
                    <span>{{ $appointment->appointment_code ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Khách hàng</h3>
            </div>
            <div class="p-4">
                <div class="flex items-center">
                    <i class="fas fa-user text-gray-400 mr-3"></i>
                    <span>{{ $appointment->customer->first_name ?? '' }} {{ $appointment->customer->last_name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Dịch vụ</h3>
            </div>
            <div class="p-4">
                <div class="flex items-center">
                    <i class="fas fa-spa text-gray-400 mr-3"></i>
                    <span>{{ $appointment->service->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Ngày hẹn</h3>
            </div>
            <div class="p-4">
                <div class="flex items-center">
                    <i class="fas fa-calendar-alt text-gray-400 mr-3"></i>
                    <span>{{ $appointment->date_appointments->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Giờ hẹn</h3>
            </div>
            <div class="p-4">
                <div class="flex items-center">
                    <i class="fas fa-clock text-gray-400 mr-3"></i>
                    <span>
                        @if($appointment->timeSlot)
                            {{ $appointment->timeSlot->start_time->format('H:i') }} - {{ $appointment->timeSlot->end_time->format('H:i') }}
                        @elseif($appointment->timeAppointment)
                            {{ $appointment->timeAppointment->started_time }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Trạng thái</h3>
            </div>
            <div class="p-4">
                <div class="flex items-center">
                    <i class="fas fa-tag text-gray-400 mr-3"></i>
                    @if($appointment->status == 'pending')
                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Chờ xác nhận</span>
                    @elseif($appointment->status == 'confirmed')
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Đã xác nhận</span>
                    @elseif($appointment->status == 'completed')
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Đã hoàn thành</span>
                    @elseif($appointment->status == 'cancelled')
                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Đã hủy</span>
                    @else
                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">{{ $appointment->status }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Staff Selection -->
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">Chọn nhân viên kỹ thuật</h3>
    </div>

    @if(count($availableTechnicians) > 0)
        <form action="{{ route('admin.appointments.confirm', $appointment->id) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($availableTechnicians as $technician)
                    <label for="technician-{{ $technician->id }}" class="cursor-pointer">
                        <div class="bg-white rounded-lg border-2 border-gray-200 p-4 hover:border-pink-500 hover:shadow-md transition-all duration-200 technician-card">
                            <div class="flex items-center mb-4">
                                <img class="w-16 h-16 rounded-full object-cover border-2 border-gray-200"
                                    src="{{ $technician->avatar_url ? asset('storage/' . $technician->avatar_url) : 'https://ui-avatars.com/api/?name=' . urlencode($technician->first_name) . '&background=4e73df&color=ffffff' }}"
                                    alt="{{ $technician->first_name }}">
                                <div class="ml-4">
                                    <h4 class="font-semibold text-gray-800">{{ $technician->first_name }} {{ $technician->last_name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $technician->email }}</p>
                                </div>
                            </div>

                            <div class="flex justify-between text-xs text-gray-500 mb-4">
                                <span class="flex items-center">
                                    <i class="fas fa-calendar-check mr-1"></i> 24 lịch hẹn
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i> 4.8/5
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Khả dụng
                                </span>

                                <div class="relative">
                                    <input type="radio" id="technician-{{ $technician->id }}" name="employee_id" value="{{ $technician->id }}" class="peer hidden">
                                    <div class="h-8 px-3 flex items-center justify-center rounded-lg border border-gray-300 text-gray-700 peer-checked:border-pink-500 peer-checked:bg-pink-50 peer-checked:text-pink-600 transition-all duration-200">
                                        <span class="text-sm">Chọn</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            @error('employee_id')
                <div class="mt-4 bg-red-50 text-red-700 p-4 rounded-lg border-l-4 border-red-500">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ $message }}</p>
                        </div>
                    </div>
                </div>
            @enderror

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded-lg hover:bg-pink-600 transition flex items-center">
                    <i class="fas fa-check mr-2"></i>
                    Xác nhận và phân công
                </button>
            </div>
        </form>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-yellow-800">Không có nhân viên khả dụng</h3>
                    <div class="mt-2 text-yellow-700">
                        <p>Không có nhân viên kỹ thuật nào khả dụng vào thời gian này. Vui lòng thử lại sau hoặc chọn thời gian khác.</p>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.appointments.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Quay lại danh sách lịch hẹn
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add click event to cards
        const radioInputs = document.querySelectorAll('input[name="employee_id"]');
        radioInputs.forEach(input => {
            const card = input.closest('.technician-card');

            card.addEventListener('click', function() {
                // Uncheck all other inputs
                radioInputs.forEach(radio => {
                    radio.checked = false;
                });

                // Check this input
                input.checked = true;
            });
        });
    });
</script>
@endpush
