@extends('layouts.staff_new')

@section('title', 'Chi tiết lịch hẹn - Cán bộ viên chức')
@section('page-title', 'Chi tiết lịch hẹn')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-700">Chi tiết lịch hẹn</h3>
            <p class="text-sm text-gray-500">Mã lịch hẹn: {{ substr($appointment->id, 0, 8) }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('staff.appointments.edit', $appointment->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors duration-150 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Chỉnh sửa
            </a>
            <a href="{{ route('staff.appointments.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid gap-6 mb-8 md:grid-cols-3">
        <!-- Main Content -->
        <div class="col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Appointment Status Header -->
                <div class="p-4 border-b
                    @if($appointment->status == 'pending') bg-yellow-50 border-yellow-200
                    @elseif($appointment->status == 'confirmed') bg-green-50 border-green-200
                    @elseif($appointment->status == 'completed') bg-blue-50 border-blue-200
                    @elseif($appointment->status == 'cancelled') bg-red-50 border-red-200
                    @endif
                ">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            @if($appointment->status == 'pending')
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-semibold text-yellow-700">Chờ xác nhận</span>
                            @elseif($appointment->status == 'confirmed')
                                <svg class="w-6 h-6 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-semibold text-green-700">Đã xác nhận</span>
                            @elseif($appointment->status == 'completed')
                                <svg class="w-6 h-6 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-semibold text-blue-700">Hoàn thành</span>
                            @elseif($appointment->status == 'cancelled')
                                <svg class="w-6 h-6 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-semibold text-red-700">Đã hủy</span>
                            @endif
                        </div>
                        <div>
                            @if(in_array($appointment->status, ['pending', 'confirmed']))
                                <button type="button" onclick="openCancelModal()" class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition-colors duration-150">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Hủy lịch hẹn
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Appointment Details -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Thông tin lịch hẹn</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Mã lịch hẹn:</span>
                                    <span class="font-medium">{{ substr($appointment->id, 0, 8) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Ngày đăng ký:</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($appointment->date_register)->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Ngày hẹn:</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Giờ hẹn:</span>
                                    <span class="font-medium">{{ $appointment->timeAppointment ? $appointment->timeAppointment->started_time : 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dịch vụ:</span>
                                    <span class="font-medium">{{ $appointment->service->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Giá dịch vụ:</span>
                                    <span class="font-medium">{{ number_format($appointment->service->price, 0, ',', '.') }} VNĐ</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Thông tin khách hàng</h4>
                            <div class="flex items-center mb-4">
                                <div class="relative hidden w-10 h-10 mr-3 rounded-full md:block">
                                    <img class="object-cover w-full h-full rounded-full" src="https://ui-avatars.com/api/?name={{ $appointment->customer->first_name }}+{{ $appointment->customer->last_name }}&background=random" alt="" loading="lazy" />
                                    <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-700">{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->customer->email }}</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Số điện thoại:</span>
                                    <span class="font-medium">{{ $appointment->customer->phone }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Địa chỉ:</span>
                                    <span class="font-medium">{{ $appointment->customer->address }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Giới tính:</span>
                                    <span class="font-medium">
                                        @if($appointment->customer->gender == 'male')
                                            Nam
                                        @elseif($appointment->customer->gender == 'female')
                                            Nữ
                                        @else
                                            Khác
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($appointment->notes)
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Ghi chú</h4>
                        <p class="text-gray-700">{{ $appointment->notes }}</p>
                    </div>
                    @endif
                    
                    @if($appointment->cancellation_reason)
                    <div class="mt-6 p-4 bg-red-50 rounded-lg">
                        <h4 class="text-sm font-medium text-red-500 uppercase tracking-wider mb-2">Lý do hủy</h4>
                        <p class="text-gray-700">{{ $appointment->cancellation_reason }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-6 flex flex-wrap gap-3">
                @if($appointment->status == 'pending')
                <form action="{{ route('staff.appointments.update', $appointment->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="service_id" value="{{ $appointment->service_id }}">
                    <input type="hidden" name="date_appointments" value="{{ $appointment->date_appointments }}">
                    <input type="hidden" name="time_appointments_id" value="{{ $appointment->time_appointments_id }}">
                    <input type="hidden" name="notes" value="{{ $appointment->notes }}">
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-150 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Xác nhận lịch hẹn
                    </button>
                </form>
                @endif
                
                @if($appointment->status == 'confirmed')
                <form action="{{ route('staff.appointments.update', $appointment->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="service_id" value="{{ $appointment->service_id }}">
                    <input type="hidden" name="date_appointments" value="{{ $appointment->date_appointments }}">
                    <input type="hidden" name="time_appointments_id" value="{{ $appointment->time_appointments_id }}">
                    <input type="hidden" name="notes" value="{{ $appointment->notes }}">
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-150 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Đánh dấu hoàn thành
                    </button>
                </form>
                @endif
                
                <a href="{{ route('staff.appointments.edit', $appointment->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors duration-150 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Chỉnh sửa
                </a>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h4 class="font-semibold text-gray-800 mb-4">Thông tin dịch vụ</h4>
                <div class="mb-4">
                    <img src="{{ $appointment->service->image_url ?? 'https://via.placeholder.com/300x150?text=Dịch+vụ' }}" alt="{{ $appointment->service->name }}" class="w-full h-32 object-cover rounded-lg mb-3">
                    <h5 class="font-semibold text-gray-700">{{ $appointment->service->name }}</h5>
                    <p class="text-sm text-gray-500 mt-1">{{ number_format($appointment->service->price, 0, ',', '.') }} VNĐ</p>
                </div>
                <div class="text-sm text-gray-600">
                    <p class="mb-2">{{ Str::limit($appointment->service->description, 150) }}</p>
                    <p class="text-pink-600 hover:text-pink-700 cursor-pointer">Xem chi tiết dịch vụ</p>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="font-semibold text-gray-800 mb-4">Hướng dẫn</h4>
                <div class="mb-4">
                    <h5 class="flex items-center text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Quy trình khám
                    </h5>
                    <ol class="list-decimal list-inside text-sm text-gray-600 ml-2">
                        <li class="mb-1">Đến trước giờ hẹn 15 phút</li>
                        <li class="mb-1">Xuất trình giấy tờ tùy thân</li>
                        <li class="mb-1">Làm thủ tục tại quầy tiếp nhận</li>
                        <li class="mb-1">Chờ đến lượt khám theo hướng dẫn</li>
                        <li>Nhận kết quả và thanh toán</li>
                    </ol>
                </div>
                
                <div>
                    <h5 class="flex items-center text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        Lưu ý
                    </h5>
                    <ul class="list-disc list-inside text-sm text-gray-600 ml-2">
                        <li class="mb-1">Mang theo giấy tờ tùy thân</li>
                        <li class="mb-1">Có thể hủy lịch hẹn trước 24 giờ</li>
                        <li>Nếu cần hỗ trợ, vui lòng liên hệ số điện thoại: <strong>(0258) 2471303</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Appointment Modal -->
<div id="cancelModal" class="fixed inset-0 z-30 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('staff.appointments.cancel', $appointment->id) }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Hủy lịch hẹn
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Bạn có chắc chắn muốn hủy lịch hẹn này? Hành động này không thể hoàn tác.
                                </p>
                                <div class="mt-4">
                                    <label for="cancellation_reason" class="block text-sm font-medium text-gray-700">Lý do hủy</label>
                                    <textarea name="cancellation_reason" id="cancellation_reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hủy lịch hẹn
                    </button>
                    <button type="button" onclick="closeCancelModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Đóng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openCancelModal() {
        document.getElementById('cancelModal').classList.remove('hidden');
    }
    
    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
    }
</script>
@endsection
