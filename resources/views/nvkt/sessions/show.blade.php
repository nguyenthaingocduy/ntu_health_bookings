@extends('layouts.nvkt-new')

@section('title', 'Chi tiết phiên làm việc')

@section('header', 'Chi tiết phiên làm việc')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chi tiết phiên làm việc</h1>
            <p class="text-sm text-gray-500 mt-1">Thông tin chi tiết về buổi chăm sóc</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ url()->previous() }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
            @if($appointment->status == 'pending' || $appointment->status == 'confirmed')
            <form action="{{ route('nvkt.work-status.update', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="in_progress">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors duration-150 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Bắt đầu
                </button>
            </form>
            @elseif($appointment->status == 'in_progress')
            <form action="{{ route('nvkt.work-status.update', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-150 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Hoàn thành
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Thông tin phiên làm việc
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Thông tin cơ bản</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Mã phiên:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ substr($appointment->id, 0, 8) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Ngày:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $appointment->date_appointments->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Thời gian:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $appointment->timeAppointment ? $appointment->timeAppointment->started_time : 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Trạng thái:</span>
                                    <span class="text-sm font-medium">
                                        @if($appointment->status == 'completed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Đã hoàn thành
                                        </span>
                                        @elseif($appointment->status == 'confirmed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Đã xác nhận
                                        </span>
                                        @elseif($appointment->status == 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Chờ xác nhận
                                        </span>
                                        @elseif($appointment->status == 'cancelled')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Đã hủy
                                        </span>
                                        @elseif($appointment->status == 'in_progress')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Đang thực hiện
                                        </span>
                                        @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $appointment->status }}
                                        </span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Thời gian thực hiện</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Bắt đầu:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $appointment->check_in_time ? $appointment->check_in_time->format('H:i:s d/m/Y') : 'Chưa bắt đầu' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Kết thúc:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $appointment->check_out_time ? $appointment->check_out_time->format('H:i:s d/m/Y') : 'Chưa kết thúc' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Thời gian thực hiện:</span>
                                    <span class="text-sm font-medium text-gray-900">
                                        @if($appointment->check_in_time && $appointment->check_out_time)
                                        {{ $appointment->check_in_time->diffInMinutes($appointment->check_out_time) }} phút
                                        @else
                                        Chưa có dữ liệu
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Ghi chú</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700">{{ $appointment->notes ?: 'Không có ghi chú' }}</p>
                        </div>
                    </div>

                    @if($appointment->status == 'in_progress')
                    <div class="mt-6">
                        <form action="{{ route('nvkt.work-status.update', $appointment->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Ghi chú kết thúc</label>
                                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $appointment->notes }}</textarea>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-150">
                                    Hoàn thành phiên
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden mt-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Thông tin dịch vụ
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center">
                        @if($appointment->service->image)
                        <div class="flex-shrink-0 h-16 w-16">
                            <img class="h-16 w-16 rounded-lg object-cover" src="{{ secure_asset('storage/' . $appointment->service->image) }}" alt="{{ $appointment->service->name }}">
                        </div>
                        @else
                        <div class="flex-shrink-0 h-16 w-16 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="h-8 w-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        @endif
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-900">{{ $appointment->service->name }}</h4>
                            <div class="flex items-center mt-1">
                                <span class="text-sm text-gray-500 mr-2">Thời gian dự kiến:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $appointment->service->duration }} phút</span>
                            </div>
                            <div class="flex items-center mt-1">
                                <span class="text-sm text-gray-500 mr-2">Giá:</span>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($appointment->final_price, 0, ',', '.') }} VNĐ</span>
                                @if($appointment->service->discount_percent > 0)
                                <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Giảm {{ $appointment->service->discount_percent }}%</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Mô tả dịch vụ</h4>
                            <div class="bg-gray-50 rounded-lg p-4 h-full">
                                <p class="text-sm text-gray-700">{{ $appointment->service->description }}</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Thông tin chi tiết</h4>
                            <div class="bg-gray-50 rounded-lg p-4 h-full">
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Loại dịch vụ:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $appointment->service->category ? $appointment->service->category->name : 'Không phân loại' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Thời gian thực hiện:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $appointment->service->duration }} phút</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Giá gốc:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ number_format($appointment->service->price, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                    @if($appointment->promotion_code)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Mã khuyến mãi:</span>
                                        <span class="text-sm font-medium text-green-600">{{ $appointment->promotion_code }}</span>
                                    </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Giá cuối cùng:</span>
                                        <span class="text-sm font-medium text-pink-600">{{ number_format($appointment->final_price, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Hướng dẫn thực hiện</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700">{{ $appointment->service->instructions ?? 'Không có hướng dẫn cụ thể cho dịch vụ này.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Thông tin khách hàng
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <div class="flex-shrink-0 h-24 w-24">
                            <img class="h-24 w-24 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ $appointment->customer->first_name }}&background=0D8ABC&color=fff&size=128" alt="{{ $appointment->customer->first_name }}">
                        </div>
                        <div class="mt-4 text-center">
                            <h4 class="text-lg font-medium text-gray-900">{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}</h4>
                            <p class="text-sm text-gray-500">{{ $appointment->customer->gender == 'male' ? 'Nam' : 'Nữ' }}</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Thông tin liên hệ</h4>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $appointment->customer->phone ?: 'Chưa cập nhật' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $appointment->customer->email }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Thông tin sức khỏe</h4>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                <div>
                                    <span class="block text-sm font-medium text-gray-700">Tiền sử bệnh</span>
                                    <span class="block mt-1 text-sm text-gray-700">{{ $appointment->customer->medical_history ?: 'Không có' }}</span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-700">Dị ứng</span>
                                    <span class="block mt-1 text-sm text-gray-700">{{ $appointment->customer->allergies ?: 'Không có' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('nvkt.customers.show', $appointment->customer->id) }}" class="block w-full bg-indigo-50 text-indigo-600 text-center px-4 py-2 rounded-lg hover:bg-indigo-100 transition-colors duration-150">
                            Xem chi tiết khách hàng
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden mt-6">
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                        </svg>
                        Ghi chú chuyên môn
                    </h3>
                </div>
                <div class="p-6">
                    @if(isset($appointment->professionalNotes) && count($appointment->professionalNotes) > 0)
                        <div class="space-y-4">
                            @foreach($appointment->professionalNotes as $note)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $note->title }}</h4>
                                        <span class="text-xs text-gray-500">{{ $note->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-700">{{ $note->content }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500">Chưa có ghi chú chuyên môn nào</p>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('nvkt.notes.create', ['customer_id' => $appointment->customer_id, 'appointment_id' => $appointment->id]) }}" class="block w-full bg-purple-50 text-purple-600 text-center px-4 py-2 rounded-lg hover:bg-purple-100 transition-colors duration-150">
                            Thêm ghi chú chuyên môn
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden mt-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        Lịch sử dịch vụ
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $pastAppointments = \App\Models\Appointment::where('customer_id', $appointment->customer_id)
                            ->where('id', '!=', $appointment->id)
                            ->where('status', 'completed')
                            ->orderBy('date_appointments', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp

                    @if(count($pastAppointments) > 0)
                        <div class="space-y-4">
                            @foreach($pastAppointments as $pastAppointment)
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $pastAppointment->service->name }}</h4>
                                            <p class="text-xs text-gray-500">{{ $pastAppointment->date_appointments->format('d/m/Y') }} - {{ $pastAppointment->timeAppointment ? $pastAppointment->timeAppointment->started_time : 'N/A' }}</p>
                                        </div>
                                        <a href="{{ route('nvkt.sessions.show', $pastAppointment->id) }}" class="text-xs text-blue-600 hover:text-blue-800">Xem chi tiết</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('nvkt.customers.service-history', $appointment->customer_id) }}" class="block w-full bg-blue-50 text-blue-600 text-center px-4 py-2 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                                Xem toàn bộ lịch sử
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500">Khách hàng chưa có lịch sử dịch vụ nào</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($appointment->status == 'completed' && $appointment->rating)
            <div class="bg-white rounded-xl shadow-md overflow-hidden mt-6">
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        Đánh giá từ khách hàng
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $appointment->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            @endfor
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-900">{{ $appointment->rating }}/5</span>
                    </div>
                    @if($appointment->review)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-700">{{ $appointment->review }}</p>
                    </div>
                    @else
                    <p class="text-sm text-gray-500">Khách hàng không để lại nhận xét</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
