@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('content')
<div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white py-12">
    <div class="container mx-auto px-6">
        <h1 class="text-3xl font-bold">Thông tin cá nhân</h1>
        <p class="mt-2">Quản lý thông tin cá nhân của bạn</p>
    </div>
</div>

<div class="container mx-auto px-6 py-10">
    <div class="max-w-4xl mx-auto">
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="md:flex">
                <!-- Sidebar -->
                <div class="bg-gray-50 md:w-1/3 p-6 border-r">
                    <div class="flex flex-col items-center text-center mb-6">
                        <img class="h-32 w-32 rounded-full object-cover border-4 border-pink-200 mb-4" 
                             src="https://ui-avatars.com/api/?name={{ Auth::user()->first_name }} {{ Auth::user()->last_name }}&background=f9a8d4&color=ffffff&size=256" 
                             alt="{{ Auth::user()->first_name }}">
                        <h2 class="text-xl font-semibold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
                        <p class="text-gray-600 mt-1">{{ Auth::user()->email }}</p>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex items-center py-2">
                            <i class="fas fa-phone text-pink-500 w-6"></i>
                            <span class="ml-3 text-gray-700">{{ Auth::user()->phone ?: 'Chưa cập nhật' }}</span>
                        </div>
                        <div class="flex items-center py-2">
                            <i class="fas fa-venus-mars text-pink-500 w-6"></i>
                            <span class="ml-3 text-gray-700">
                                @if(Auth::user()->gender == 'male')
                                    Nam
                                @elseif(Auth::user()->gender == 'female')
                                    Nữ
                                @else
                                    Khác
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center py-2">
                            <i class="fas fa-birthday-cake text-pink-500 w-6"></i>
                            <span class="ml-3 text-gray-700">{{ Auth::user()->birthday ? date('d/m/Y', strtotime(Auth::user()->birthday)) : 'Chưa cập nhật' }}</span>
                        </div>
                        <div class="flex items-center py-2">
                            <i class="fas fa-map-marker-alt text-pink-500 w-6"></i>
                            <span class="ml-3 text-gray-700">{{ Auth::user()->address ?: 'Chưa cập nhật' }}</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('customer.profile.edit') }}" class="block w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white py-2 px-4 rounded-lg text-center font-medium hover:shadow-lg hover:shadow-pink-200 transition-all">
                            <i class="fas fa-edit mr-2"></i> Chỉnh sửa thông tin
                        </a>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="md:w-2/3 p-6">
                    <h3 class="text-xl font-semibold mb-4">Lịch sử đặt lịch</h3>
                    
                    @if($appointments->isEmpty())
                        <div class="bg-gray-50 rounded-lg p-8 text-center">
                            <img src="https://cdn.iconscout.com/icon/free/png-256/free-calendar-empty-3099718-2588872.png" alt="Empty calendar" class="w-20 h-20 mx-auto mb-4 opacity-50">
                            <p class="text-gray-600">Bạn chưa có lịch hẹn nào.</p>
                            <a href="{{ route('customer.appointments.create') }}" class="mt-4 inline-block bg-pink-500 text-white py-2 px-4 rounded-lg hover:bg-pink-600 transition">
                                <i class="fas fa-calendar-plus mr-2"></i> Đặt lịch ngay
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($appointments->take(3) as $appointment)
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $appointment->service->name ?? 'Dịch vụ không xác định' }}</h4>
                                            <div class="flex items-center mt-1 text-gray-600">
                                                <i class="far fa-calendar mr-2"></i>
                                                <span>{{ date('d/m/Y', strtotime($appointment->date_appointments)) }}</span>
                                                <span class="mx-2">|</span>
                                                <i class="far fa-clock mr-2"></i>
                                                <span>{{ isset($appointment->timeAppointment) ? date('H:i', strtotime($appointment->timeAppointment->started_time)) : '--:--' }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            @if($appointment->status == 'pending')
                                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-medium">Chờ xác nhận</span>
                                            @elseif($appointment->status == 'confirmed')
                                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">Đã xác nhận</span>
                                            @elseif($appointment->status == 'cancelled')
                                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-medium">Đã hủy</span>
                                            @elseif($appointment->status == 'completed')
                                                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">Hoàn thành</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-2 flex justify-end">
                                        <a href="{{ route('customer.appointments.show', $appointment->id) }}" class="text-pink-600 hover:text-pink-700 text-sm">
                                            <i class="fas fa-eye mr-1"></i> Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($appointments->count() > 3)
                                <div class="text-center mt-4">
                                    <a href="{{ route('customer.appointments.index') }}" class="text-pink-600 hover:text-pink-700">
                                        Xem tất cả lịch hẹn <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    <hr class="my-6">
                    
                    <h3 class="text-xl font-semibold mb-4">Thông tin tài khoản</h3>
                    <div class="space-y-4">
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900">Đổi mật khẩu</h4>
                                    <p class="text-gray-600 text-sm mt-1">Cập nhật mật khẩu để bảo mật tài khoản của bạn</p>
                                </div>
                                <a href="#" class="text-pink-600 hover:text-pink-700">
                                    <i class="fas fa-key mr-1"></i> Đổi mật khẩu
                                </a>
                            </div>
                        </div>
                        
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900">Thông báo</h4>
                                    <p class="text-gray-600 text-sm mt-1">Quản lý cài đặt thông báo của bạn</p>
                                </div>
                                <a href="#" class="text-pink-600 hover:text-pink-700">
                                    <i class="fas fa-bell mr-1"></i> Cài đặt
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 