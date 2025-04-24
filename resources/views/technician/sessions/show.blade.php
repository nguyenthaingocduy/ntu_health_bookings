@extends('layouts.admin')

@section('title', 'Chi tiết buổi chăm sóc')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chi tiết buổi chăm sóc</h1>
            <p class="text-sm text-gray-500 mt-1">Cập nhật tiến trình và ghi chú chuyên môn</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('technician.schedule') }}" class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-150 shadow-sm border border-gray-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    <nav class="mb-8">
        <ol class="flex text-sm text-gray-500">
            <li class="flex items-center">
                <a href="{{ route('technician.dashboard') }}" class="hover:text-indigo-500">Trang chủ</a>
                <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </li>
            <li class="flex items-center">
                <a href="{{ route('technician.schedule') }}" class="hover:text-indigo-500">Lịch làm việc</a>
                <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </li>
            <li class="text-indigo-500 font-medium">Chi tiết buổi chăm sóc</li>
        </ol>
    </nav>

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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 mb-6">
                <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Cập nhật trạng thái buổi chăm sóc
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('technician.sessions.update', $appointment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái buổi chăm sóc</label>
                            <select id="status" name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="in_progress" {{ $appointment->status == 'in_progress' ? 'selected' : '' }}>Đang thực hiện</option>
                                <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label for="progress" class="block text-sm font-medium text-gray-700 mb-2">Tiến trình thực hiện</label>
                            <select id="progress" name="progress" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="0" {{ $appointment->progress == 0 ? 'selected' : '' }}>Chưa bắt đầu (0%)</option>
                                <option value="25" {{ $appointment->progress == 25 ? 'selected' : '' }}>Buổi 1 (25%)</option>
                                <option value="50" {{ $appointment->progress == 50 ? 'selected' : '' }}>Buổi 2 (50%)</option>
                                <option value="75" {{ $appointment->progress == 75 ? 'selected' : '' }}>Buổi 3 (75%)</option>
                                <option value="100" {{ $appointment->progress == 100 ? 'selected' : '' }}>Hoàn thành (100%)</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label for="session_notes" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú buổi chăm sóc</label>
                            <textarea id="session_notes" name="session_notes" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Nhập ghi chú về buổi chăm sóc này...">{{ $appointment->session_notes }}</textarea>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors duration-150 shadow-md flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Cập nhật trạng thái
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
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
                    <form action="{{ route('technician.notes.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $appointment->customer_id }}">
                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                        
                        <div class="mb-6">
                            <label for="skin_condition" class="block text-sm font-medium text-gray-700 mb-2">Tình trạng da</label>
                            <select id="skin_condition" name="skin_condition" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <option value="normal">Da thường</option>
                                <option value="dry">Da khô</option>
                                <option value="oily">Da dầu</option>
                                <option value="combination">Da hỗn hợp</option>
                                <option value="sensitive">Da nhạy cảm</option>
                                <option value="acne_prone">Da mụn</option>
                                <option value="aging">Da lão hóa</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú chuyên môn</label>
                            <textarea id="content" name="content" rows="6" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50" placeholder="Nhập ghi chú chuyên môn về khách hàng...">{{ $professionalNote->content ?? '' }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Ghi chú về tình trạng da, lưu ý cho lần sau, sản phẩm đề xuất, v.v.</p>
                        </div>
                        
                        <div class="mb-6">
                            <label for="recommendations" class="block text-sm font-medium text-gray-700 mb-2">Đề xuất</label>
                            <textarea id="recommendations" name="recommendations" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50" placeholder="Đề xuất sản phẩm, dịch vụ hoặc lưu ý cho khách hàng...">{{ $professionalNote->recommendations ?? '' }}</textarea>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors duration-150 shadow-md flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Lưu ghi chú
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div>
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 sticky top-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Thông tin buổi chăm sóc
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <div class="flex items-center mb-4">
                            <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900">{{ $appointment->customer->full_name }}</h4>
                                <p class="text-sm text-gray-500">{{ $appointment->customer->phone }}</p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Dịch vụ</p>
                                <p class="text-base font-medium text-gray-800">{{ $appointment->service->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Ngày & Giờ</p>
                                <p class="text-base font-medium text-gray-800">{{ $appointment->appointment_date->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-600">{{ $appointment->time_slot->start_time }} - {{ $appointment->time_slot->end_time }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Trạng thái</p>
                                <p class="text-base font-medium 
                                    @if($appointment->status == 'pending') text-yellow-600
                                    @elseif($appointment->status == 'confirmed') text-blue-600
                                    @elseif($appointment->status == 'in_progress') text-indigo-600
                                    @elseif($appointment->status == 'completed') text-green-600
                                    @elseif($appointment->status == 'cancelled') text-red-600
                                    @endif">
                                    @if($appointment->status == 'pending') Chờ xác nhận
                                    @elseif($appointment->status == 'confirmed') Đã xác nhận
                                    @elseif($appointment->status == 'in_progress') Đang thực hiện
                                    @elseif($appointment->status == 'completed') Hoàn thành
                                    @elseif($appointment->status == 'cancelled') Đã hủy
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tiến trình</p>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                                    <div class="bg-indigo-500 h-2.5 rounded-full" style="width: {{ $appointment->progress }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $appointment->progress }}% hoàn thành</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h5 class="text-sm font-medium text-gray-500 uppercase mb-2">Thông tin khách hàng</h5>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="text-base font-medium text-gray-800">{{ $appointment->customer->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Ngày sinh</p>
                                <p class="text-base font-medium text-gray-800">{{ $appointment->customer->date_of_birth ? $appointment->customer->date_of_birth->format('d/m/Y') : 'Không có' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Địa chỉ</p>
                                <p class="text-base font-medium text-gray-800">{{ $appointment->customer->address ?: 'Không có' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 mt-4">
                        <p class="font-medium">Lưu ý:</p>
                        <ul class="list-disc list-inside mt-2 text-sm">
                            <li>Cập nhật tiến trình sau mỗi buổi chăm sóc.</li>
                            <li>Ghi chú chuyên môn giúp theo dõi tình trạng khách hàng.</li>
                            <li>Đảm bảo cập nhật trạng thái hoàn thành khi kết thúc.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
