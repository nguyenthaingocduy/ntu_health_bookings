@extends('layouts.admin')

@section('title', 'Chi tiết nhân viên')

@section('header', 'Chi tiết nhân viên')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Employee Information -->
    <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center">
                    <img src="{{ asset($employee->avatar_url ?? 'images/employees/default-avatar.svg') }}" alt="{{ $employee->name }}"
                        class="w-20 h-20 rounded-full object-cover mr-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $employee->name }}</h2>
                        <p class="text-gray-500">{{ $employee->clinic->name }}</p>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-sm
                    {{ $employee->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $employee->status == 'active' ? 'Đang làm việc' : 'Đã nghỉ việc' }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Thông tin liên hệ</h3>
                    <div class="space-y-2">
                        <p class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 w-6"></i>
                            <span class="text-gray-600">{{ $employee->email }}</span>
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-phone text-gray-400 w-6"></i>
                            <span class="text-gray-600">{{ $employee->phone }}</span>
                        </p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-2">Dịch vụ có thể thực hiện</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($employee->services as $service)
                        <span class="px-3 py-1 bg-pink-100 text-pink-500 rounded-full text-sm">
                            {{ $service->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.employees.edit', $employee) }}"
                    class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                    <i class="fas fa-edit mr-2"></i>Chỉnh sửa
                </a>
                <button type="button"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition"
                    onclick="document.getElementById('resetPasswordModal').classList.remove('hidden')">
                    <i class="fas fa-key mr-2"></i>Đặt lại mật khẩu
                </button>
                <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition"
                        onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">
                        <i class="fas fa-trash mr-2"></i>Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Thống kê</h3>

            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Tổng số lịch hẹn</p>
                    <p class="text-2xl font-bold">{{ $statistics['total_appointments'] }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Lịch hẹn trong tháng</p>
                    <p class="text-2xl font-bold">{{ $statistics['monthly_appointments'] }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Tỷ lệ hoàn thành</p>
                    <p class="text-2xl font-bold">{{ number_format($statistics['completion_rate'], 1) }}%</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Đánh giá trung bình</p>
                    <div class="flex items-center">
                        <p class="text-2xl font-bold mr-2">{{ number_format($statistics['average_rating'], 1) }}</p>
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $statistics['average_rating'] ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Lịch hẹn gần đây</h3>

            <div class="space-y-4">
                @forelse($recentAppointments as $appointment)
                <div class="border-b pb-4 last:border-0 last:pb-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold">{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</p>
                            <p class="text-sm text-gray-500">{{ $appointment->service->name }}</p>
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $appointment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                               ($appointment->status == 'confirmed' ? 'bg-blue-100 text-blue-800' :
                               ($appointment->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                            {{ $appointment->status == 'pending' ? 'Chờ xác nhận' :
                               ($appointment->status == 'confirmed' ? 'Đã xác nhận' :
                               ($appointment->status == 'completed' ? 'Hoàn thành' : 'Đã hủy')) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y H:i') }}
                    </p>
                </div>
                @empty
                <p class="text-gray-500 text-center">Chưa có lịch hẹn nào</p>
                @endforelse
            </div>

            @if($appointments->count() > 5)
            <div class="mt-4 text-center">
                <a href="{{ route('admin.appointments.index', ['employee' => $employee->id]) }}"
                    class="text-pink-500 hover:text-pink-600">
                    Xem tất cả
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Đặt lại mật khẩu cho {{ $employee->first_name }} {{ $employee->last_name }}</h3>
            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="document.getElementById('resetPasswordModal').classList.add('hidden')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('admin.employees.reset-password', $employee->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu mới <span class="text-red-500">*</span></label>
                <input type="password" id="new_password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Tối thiểu 8 ký tự</p>
            </div>

            <div class="mb-4">
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Xác nhận mật khẩu mới <span class="text-red-500">*</span></label>
                <input type="password" id="new_password_confirmation" name="password_confirmation" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
                    onclick="document.getElementById('resetPasswordModal').classList.add('hidden')">
                    Hủy bỏ
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    <i class="fas fa-save mr-2"></i>Lưu mật khẩu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection