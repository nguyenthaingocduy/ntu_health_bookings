@extends('layouts.app')

@section('content')
<div class="py-12 bg-gradient-to-br from-pink-50 via-purple-50 to-blue-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(isset($error))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-md mb-6 animate-bounce" role="alert">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                </div>
                <div>
                    <p class="font-semibold">Lỗi!</p>
                    <p>{{ $error }}</p>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Welcome Section -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl mb-8 border border-gray-100">
            <div class="p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row items-center">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-r from-pink-500 to-purple-600 flex items-center justify-center mr-6 mb-4 sm:mb-0 shadow-lg">
                        <i class="fas fa-spa text-black text-3xl"></i>
                    </div>
                    <div class="text-center sm:text-left">
                        <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-purple-600 mb-2">Xin chào, {{ Auth::user()->first_name }}!</h2>
                        <p class="text-gray-600 text-lg">Chúng tôi cung cấp các dịch vụ chăm sóc sắc đẹp chuyên nghiệp, giúp bạn tỏa sáng và tự tin hơn.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Profile Summary Card -->
        <div class="bg-gradient-to-r from-purple-100 to-pink-100 overflow-hidden shadow-lg rounded-2xl mb-8 border border-purple-200">
            <div class="p-6 sm:p-8">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="relative mr-5">
                            <img 
                                class="h-16 w-16 rounded-full object-cover border-4 border-white shadow-md" 
                                src="https://ui-avatars.com/api/?name={{ Auth::user()->first_name }}&background=f9a8d4&color=ffffff" 
                                alt="{{ Auth::user()->first_name }}">
                            <div class="absolute bottom-0 right-0 h-4 w-4 rounded-full bg-green-400 border-2 border-white"></div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>
                            <p class="text-gray-600">{{ Auth::user()->email }}</p>
                            <div class="flex items-center mt-1">
                                <span class="flex items-center text-yellow-600 font-medium text-sm mr-4">
                                    <i class="fas fa-star mr-2"></i> {{ Auth::user()->type->type_name ?? 'Regular' }}
                                </span>
                                <span class="flex items-center text-gray-600 text-sm">
                                    <i class="fas fa-map-marker-alt mr-2"></i> {{ Auth::user()->address ?? 'Chưa cập nhật' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('customer.profile.show') }}" class="inline-flex items-center px-4 py-2 bg-white rounded-lg border border-gray-200 shadow-sm text-pink-600 hover:bg-gray-50 transition">
                            <i class="fas fa-user-edit mr-2"></i> Cập nhật hồ sơ
                        </a>
                        <a href="{{ route('customer.appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 rounded-lg shadow-sm text-black hover:from-pink-600 hover:to-purple-700 transition">
                            <i class="fas fa-calendar-plus mr-2"></i> Đặt lịch
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Appointments Stats -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl transform transition duration-300 hover:scale-105 border border-gray-100">
                <div class="p-6 relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-pink-100 opacity-40"></div>
                    <div class="flex items-center justify-between relative">
                        <div>
                            <div class="text-4xl font-extrabold text-gray-800 mb-1">
                                {{ $appointmentsCount ?? 0 }}
                            </div>
                            <div class="text-gray-600 font-medium text-lg">Lịch hẹn</div>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center shadow-md">
                            <i class="fas fa-calendar-alt text-black text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                    <a href="{{ route('customer.appointments.index') }}" class="text-pink-600 hover:text-pink-700 font-medium text-sm flex items-center">
                        Xem tất cả lịch hẹn
                        <i class="fas fa-arrow-right ml-1 text-xs transition transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>

            <!-- Services Used Stats -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl transform transition duration-300 hover:scale-105 border border-gray-100">
                <div class="p-6 relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-purple-100 opacity-40"></div>
                    <div class="flex items-center justify-between relative">
                        <div>
                            <div class="text-4xl font-extrabold text-gray-800 mb-1">
                                {{ $servicesUsedCount ?? 0 }}
                            </div>
                            <div class="text-gray-600 font-medium text-lg">Dịch vụ đã dùng</div>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-md">
                            <i class="fas fa-spa text-black text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                    <a href="{{ route('customer.services.index') }}" class="text-purple-600 hover:text-purple-700 font-medium text-sm flex items-center">
                        Khám phá dịch vụ
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Points Stats -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl transform transition duration-300 hover:scale-105 border border-gray-100">
                <div class="p-6 relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-yellow-100 opacity-40"></div>
                    <div class="flex items-center justify-between relative">
                        <div>
                            <div class="text-4xl font-extrabold text-gray-800 mb-1">
                                {{ $points ?? 0 }}
                            </div>
                            <div class="text-gray-600 font-medium text-lg">Điểm tích lũy</div>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center shadow-md">
                            <i class="fas fa-star text-black text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                    <span class="text-amber-600 font-medium text-sm flex items-center">
                        Sử dụng dịch vụ để tích điểm
                        <i class="fas fa-gift ml-1 text-xs"></i>
                    </span>
                </div>
            </div>
        </div>

        <!-- Recent Appointments & Tips Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Recent Appointments - 2/3 width on larger screens -->
            <div class="lg:col-span-2 bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
                <div class="p-6 sm:p-8">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                            <span class="bg-pink-100 text-pink-600 p-2 rounded-lg mr-3">
                                <i class="fas fa-calendar-check"></i>
                            </span>
                            Lịch hẹn sắp tới
                        </h2>
                        <a href="{{ route('customer.appointments.index') }}" class="text-pink-600 hover:text-pink-700 font-semibold flex items-center group">
                            Xem tất cả
                            <i class="fas fa-arrow-right ml-2 text-sm transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                    
                    @if(isset($upcomingAppointments) && count($upcomingAppointments) > 0)
                        <div class="overflow-x-auto bg-white rounded-xl shadow-inner">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 rounded-tl-xl">
                                            Dịch vụ
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50">
                                            Ngày
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50">
                                            Trạng thái
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 rounded-tr-xl">
                                            Nhân viên
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($upcomingAppointments as $appointment)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="font-medium text-gray-900">
                                                    {{ $appointment->service ? $appointment->service->name : 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="text-gray-700 flex items-center">
                                                    <span class="bg-blue-50 text-blue-600 p-1 rounded-lg mr-2">
                                                        <i class="fas fa-calendar-day text-xs"></i>
                                                    </span>
                                                    {{ $appointment->date_appointments ? date('d/m/Y', strtotime($appointment->date_appointments)) : 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span class="px-3 py-1.5 inline-flex items-center text-xs leading-5 font-semibold rounded-full 
                                                    {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                                    @if($appointment->status === 'pending')
                                                        <i class="fas fa-clock mr-1"></i>
                                                    @elseif($appointment->status === 'confirmed')
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                    @elseif($appointment->status === 'cancelled')
                                                        <i class="fas fa-times-circle mr-1"></i>
                                                    @endif
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="text-gray-700 flex items-center">
                                                    <span class="bg-purple-50 text-purple-600 p-1 rounded-lg mr-2">
                                                        <i class="fas fa-user-md text-xs"></i>
                                                    </span>
                                                    {{ $appointment->employee ? $appointment->employee->first_name . ' ' . $appointment->employee->last_name : 'Chưa phân công' }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-16 bg-gray-50 rounded-xl">
                            <div class="w-20 h-20 bg-gradient-to-r from-pink-200 to-purple-200 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner">
                                <i class="fas fa-calendar-times text-pink-500 text-2xl"></i>
                            </div>
                            <h3 class="text-gray-700 font-semibold text-xl mb-2">Không có lịch hẹn sắp tới</h3>
                            <p class="text-gray-500 mb-6 max-w-md mx-auto">Bạn chưa có lịch hẹn nào. Hãy đặt lịch để trải nghiệm dịch vụ của chúng tôi.</p>
                            <a href="{{ route('customer.appointments.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-black font-medium rounded-full hover:from-pink-600 hover:to-purple-700 transition shadow-md">
                                <i class="fas fa-calendar-plus mr-2"></i>
                                Đặt lịch ngay
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Tips and Reminders - 1/3 width on larger screens -->
            <div class="bg-gradient-to-b from-blue-50 to-purple-50 overflow-hidden shadow-xl rounded-2xl border border-blue-100">
                <div class="p-6 sm:p-8">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center mb-6">
                        <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                            <i class="fas fa-lightbulb"></i>
                        </span>
                        Mẹo chăm sóc
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-blue-50">
                            <h3 class="font-semibold text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-tint text-blue-500 mr-2"></i>
                                Uống nhiều nước
                            </h3>
                            <p class="text-gray-600 text-sm">Uống ít nhất 2 lít nước mỗi ngày để giữ cho làn da luôn đủ ẩm và khỏe mạnh.</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-blue-50">
                            <h3 class="font-semibold text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-sun text-yellow-500 mr-2"></i>
                                Sử dụng kem chống nắng
                            </h3>
                            <p class="text-gray-600 text-sm">Thoa kem chống nắng hàng ngày, ngay cả khi trời không nắng, để bảo vệ da khỏi tia UV.</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-blue-50">
                            <h3 class="font-semibold text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-bed text-indigo-500 mr-2"></i>
                                Ngủ đủ giấc
                            </h3>
                            <p class="text-gray-600 text-sm">Đảm bảo ngủ 7-8 tiếng mỗi đêm để cơ thể có thời gian tái tạo và làn da trở nên tươi sáng.</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <a href="{{ route('customer.services.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition">
                            <i class="fas fa-info-circle mr-2"></i> Tư vấn thêm
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="bg-gradient-to-br from-pink-50 to-pink-100 overflow-hidden shadow-lg rounded-2xl transform transition duration-300 hover:shadow-xl border border-pink-100 relative">
                <div class="absolute top-0 right-0 w-40 h-40 bg-pink-200 rounded-full opacity-20 transform translate-x-10 -translate-y-20"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-pink-200 rounded-full opacity-20 transform -translate-x-10 translate-y-10"></div>
                <div class="p-8 relative">
                    <div class="mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-r from-pink-500 to-pink-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-calendar-plus text-white text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Đặt lịch hẹn mới</h3>
                    <p class="text-gray-600 mb-8">
                        Chọn dịch vụ và thời gian phù hợp để đặt lịch hẹn dễ dàng và nhanh chóng. Đội ngũ chuyên gia của chúng tôi luôn sẵn sàng phục vụ bạn.
                    </p>
                    <a href="{{ route('customer.appointments.create') }}" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-500 to-pink-600 text-black font-medium rounded-full hover:from-pink-600 hover:to-pink-700 transition shadow-md group">
                        Đặt lịch ngay
                        <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 overflow-hidden shadow-lg rounded-2xl transform transition duration-300 hover:shadow-xl border border-purple-100 relative">
                <div class="absolute top-0 right-0 w-40 h-40 bg-purple-200 rounded-full opacity-20 transform translate-x-10 -translate-y-20"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-purple-200 rounded-full opacity-20 transform -translate-x-10 translate-y-10"></div>
                <div class="p-8 relative">
                    <div class="mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-spa text-white text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Khám phá dịch vụ</h3>
                    <p class="text-gray-600 mb-8">
                        Tìm hiểu về các dịch vụ chăm sóc sức khỏe và làm đẹp cao cấp của chúng tôi. Đa dạng lựa chọn với chất lượng đảm bảo.
                    </p>
                    <a href="{{ route('customer.services.index') }}" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-black font-medium rounded-full hover:from-purple-600 hover:to-purple-700 transition shadow-md group">
                        Xem dịch vụ
                        <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 