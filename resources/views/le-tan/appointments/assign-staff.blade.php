@extends('layouts.le-tan')

@section('title', 'Phân công nhân viên kỹ thuật')

@section('header', 'Phân công nhân viên kỹ thuật')

@section('styles')
<style>
    .technician-card {
        transition: all 0.3s ease;
        border-width: 2px;
    }

    .technician-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    .technician-card.selected {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }

    .technician-card.selected .radio-custom {
        border-color: #3b82f6;
        background-color: #3b82f6;
    }

    .technician-card.selected .radio-custom:after {
        content: '';
        width: 0.75rem;
        height: 0.75rem;
        background: white;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border-radius: 50%;
    }

    .radio-custom {
        border-radius: 50%;
        display: inline-block;
        position: relative;
        transition: all 0.2s ease;
    }

    .radio-custom.checked {
        border-color: #3b82f6;
        background-color: #3b82f6;
    }

    .radio-custom.checked:after {
        content: '';
        width: 0.75rem;
        height: 0.75rem;
        background: white;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border-radius: 50%;
    }

    /* Animated background for the page */
    .bg-gradient-animated {
        background: linear-gradient(-45deg, #EFF6FF, #F9FAFB, #EFF6FF, #F3F4F6);
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
    }

    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    /* Pulsing effect for the online indicator */
    .online-indicator {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }

        70% {
            transform: scale(1);
            box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
        }

        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8 bg-gradient-animated rounded-2xl">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Phân công nhân viên kỹ thuật</h1>
            <p class="text-sm text-gray-500 mt-1">Chọn nhân viên kỹ thuật để phục vụ lịch hẹn này</p>
        </div>
        <div>
            <a href="{{ route('le-tan.appointments.show', $appointment->id) }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 transition-all duration-300 hover:shadow-xl">
        <div class="p-6">
            <div class="border-b border-gray-200 pb-6 mb-6">
                <div class="flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-xl font-bold text-gray-800">Thông tin lịch hẹn</h2>
                </div>

                <div class="bg-blue-50 rounded-lg p-4 mb-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-blue-700">Vui lòng chọn nhân viên kỹ thuật có kinh nghiệm phù hợp với dịch vụ được yêu cầu.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="bg-gray-50 rounded-lg p-4 transition-all duration-300 hover:bg-gray-100">
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <p class="text-sm font-medium text-gray-600">Khách hàng:</p>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 ml-7">{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 transition-all duration-300 hover:bg-gray-100">
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                            <p class="text-sm font-medium text-gray-600">Dịch vụ:</p>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 ml-7">{{ $appointment->service->name }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 transition-all duration-300 hover:bg-gray-100">
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm font-medium text-gray-600">Ngày hẹn:</p>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 ml-7">
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
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 transition-all duration-300 hover:bg-gray-100">
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm font-medium text-gray-600">Giờ hẹn:</p>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 ml-7">
                            @if($appointment->timeSlot)
                                @php
                                    $startTime = $appointment->timeSlot->start_time;
                                    $endTime = $appointment->timeSlot->end_time;

                                    $startTimeFormatted = is_string($startTime) ? substr($startTime, 0, 5) : $startTime->format('H:i');
                                    $endTimeFormatted = is_string($endTime) ? substr($endTime, 0, 5) : $endTime->format('H:i');
                                @endphp
                                {{ $startTimeFormatted }} - {{ $endTimeFormatted }}
                            @elseif($appointment->timeAppointment)
                                {{ $appointment->timeAppointment->started_time }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Chọn nhân viên kỹ thuật</h2>
            </div>

            @if(count($availableTechnicians) > 0)
                <form action="{{ route('le-tan.appointments.confirm', $appointment->id) }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($availableTechnicians as $technician)
                            <div class="border-2 border-gray-200 rounded-xl p-4 hover:bg-gray-50 cursor-pointer technician-card shadow-sm transition-all duration-300 ease-in-out hover:shadow-md" data-technician-id="{{ $technician->id }}">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 relative">
                                        <img class="technician-avatar rounded-full h-14 w-14 object-cover ring-2 ring-gray-200" src="{{ $technician->avatar_url ? secure_asset('storage/' . $technician->avatar_url) : 'https://ui-avatars.com/api/?name=' . urlencode($technician->first_name) . '&background=0D8ABC&color=fff' }}" alt="{{ $technician->first_name }}">
                                        <div class="absolute -bottom-1 -right-1 bg-green-500 rounded-full w-4 h-4 border-2 border-white online-indicator"></div>
                                    </div>
                                    <div class="ml-4 flex-grow">
                                        <div class="text-sm font-semibold text-gray-900">{{ $technician->first_name }} {{ $technician->last_name }}</div>
                                        <div class="text-xs text-gray-500 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $technician->email }}
                                        </div>
                                    </div>
                                    <div class="ml-auto">
                                        <div class="radio-custom w-6 h-6 border-2 border-blue-400" id="radio-{{ $technician->id }}"></div>
                                        <input type="radio" name="employee_id" value="{{ $technician->id }}" class="hidden" id="input-{{ $technician->id }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('employee_id')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                    @enderror

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex justify-center py-3 px-8 border border-transparent shadow-lg text-sm font-medium rounded-full text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 confirm-button transform transition-all duration-300 hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Xác nhận và phân công
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-6 shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-yellow-800">Không có nhân viên khả dụng</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>
                                    Không có nhân viên kỹ thuật nào khả dụng vào thời gian này. Vui lòng thử lại sau hoặc chọn thời gian khác.
                                </p>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('le-tan.appointments.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Quay lại danh sách lịch hẹn
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const technicianCards = document.querySelectorAll('.technician-card');

        technicianCards.forEach(card => {
            card.addEventListener('click', function() {
                const technicianId = this.dataset.technicianId;
                const radioInput = document.getElementById('input-' + technicianId);
                const radioCustom = document.getElementById('radio-' + technicianId);

                // Uncheck all other radio buttons
                document.querySelectorAll('input[name="employee_id"]').forEach(input => {
                    input.checked = false;
                });

                // Remove checked class from all custom radio buttons
                document.querySelectorAll('.radio-custom').forEach(radio => {
                    radio.classList.remove('checked');
                });

                // Check the clicked radio button
                radioInput.checked = true;
                radioCustom.classList.add('checked');

                // Remove selected class from all cards
                technicianCards.forEach(c => {
                    c.classList.remove('selected');
                });

                // Add selected class to clicked card
                this.classList.add('selected');
            });
        });
    });
</script>
@endsection
