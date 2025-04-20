@extends('layouts.app')

@section('title', 'Cài đặt thông báo')

@section('content')
<div class="bg-gradient-to-r from-pink-500 to-purple-600 text-black py-12">
    <div class="container mx-auto px-6">
        <h1 class="text-3xl font-bold">Cài đặt thông báo</h1>
        <p class="mt-2">Quản lý cài đặt thông báo của bạn</p>
    </div>
</div>

<div class="container mx-auto px-6 py-10">
    <div class="max-w-2xl mx-auto">
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <form action="{{ route('customer.profile.update-notifications') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Thông báo qua email</h3>
                                <p class="text-sm text-gray-500 mt-1">Nhận thông báo qua email</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="email_notifications_enabled" value="1" class="sr-only peer" {{ Auth::user()->email_notifications_enabled ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-2">
                            <div>
                                <h4 class="font-medium text-gray-900">Xác nhận đặt lịch</h4>
                                <p class="text-sm text-gray-500 mt-1">Nhận thông báo khi lịch hẹn được xác nhận</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_appointment_confirmation" value="1" class="sr-only peer" {{ Auth::user()->notify_appointment_confirmation ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between py-2 border-t border-gray-100">
                            <div>
                                <h4 class="font-medium text-gray-900">Nhắc nhở lịch hẹn</h4>
                                <p class="text-sm text-gray-500 mt-1">Nhận thông báo nhắc nhở trước lịch hẹn</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_appointment_reminder" value="1" class="sr-only peer" {{ Auth::user()->notify_appointment_reminder ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between py-2 border-t border-gray-100">
                            <div>
                                <h4 class="font-medium text-gray-900">Hủy lịch hẹn</h4>
                                <p class="text-sm text-gray-500 mt-1">Nhận thông báo khi lịch hẹn bị hủy</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_appointment_cancellation" value="1" class="sr-only peer" {{ Auth::user()->notify_appointment_cancellation ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between py-2 border-t border-gray-100">
                            <div>
                                <h4 class="font-medium text-gray-900">Khuyến mãi và tin tức</h4>
                                <p class="text-sm text-gray-500 mt-1">Nhận thông báo về khuyến mãi và tin tức mới</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notify_promotions" value="1" class="sr-only peer" {{ Auth::user()->notify_promotions ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mt-8">
                        <a href="{{ route('customer.profile.show') }}" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-arrow-left mr-1"></i> Quay lại
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-medium rounded-lg hover:shadow-lg hover:shadow-pink-200 transition-all">
                            <i class="fas fa-save mr-2"></i> Lưu cài đặt
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
