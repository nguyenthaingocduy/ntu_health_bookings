@extends('layouts.admin')

@section('title', 'Quản lý lịch hẹn')

@section('header', 'Quản lý lịch hẹn')

@section('content')
<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form action="{{ route('admin.appointments.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Tìm kiếm theo tên khách hàng, email, số điện thoại..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
        </div>

        <div class="w-full md:w-auto">
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                <option value="">Tất cả trạng thái</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>

        <div class="w-full md:w-auto">
            <input type="date" name="date" value="{{ request('date') }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
        </div>

        <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded-lg hover:bg-pink-600 transition">
            <i class="fas fa-search mr-2"></i>Tìm kiếm
        </button>
    </form>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-calendar-check text-blue-500 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-600">Tổng lịch hẹn</p>
                <p class="text-2xl font-bold">{{ $statistics['total'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-clock text-yellow-500 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-600">Chờ xác nhận</p>
                <p class="text-2xl font-bold">{{ $statistics['pending'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-600">Hoàn thành</p>
                <p class="text-2xl font-bold">{{ $statistics['completed'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-times-circle text-red-500 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-600">Đã hủy</p>
                <p class="text-2xl font-bold">{{ $statistics['cancelled'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Appointments Table -->
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b">
                        <th class="pb-4 font-semibold">Mã lịch hẹn</th>
                        <th class="pb-4 font-semibold">Khách hàng</th>
                        <th class="pb-4 font-semibold">Dịch vụ</th>
                        <th class="pb-4 font-semibold">Thời gian</th>
                        <th class="pb-4 font-semibold">Trạng thái</th>
                        <th class="pb-4 font-semibold">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($appointments as $appointment)
                    <tr>
                        <td class="py-4">
                            <span class="font-mono">{{ $appointment->code }}</span>
                        </td>
                        <td class="py-4">
                            <div>
                                <p class="font-semibold">{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</p>
                                <p class="text-sm text-gray-600">{{ $appointment->user->phone }}</p>
                            </div>
                        </td>
                        <td class="py-4">
                            <div>
                                <p class="font-semibold">{{ $appointment->service->name }}</p>
                                <p class="text-sm text-gray-600">{{ number_format($appointment->service->price) }}đ</p>
                            </div>
                        </td>
                        <td class="py-4">
                            <div>
                                <p class="font-semibold">{{ $appointment->date_appointments->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-600">{{ optional($appointment->timeAppointment)->started_time }}</p>
                            </div>
                        </td>
                        <td class="py-4">
                            @switch($appointment->status)
                                @case('pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                        Chờ xác nhận
                                    </span>
                                    @break
                                @case('confirmed')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                        Đã xác nhận
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                        Hoàn thành
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">
                                        Đã hủy
                                    </span>
                                    @break
                            @endswitch
                        </td>
                        <td class="py-4">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.appointments.show', $appointment) }}"
                                    class="text-blue-500 hover:text-blue-700" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($appointment->status == 'pending')
                                <form action="{{ route('admin.appointments.confirm', $appointment) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-500 hover:text-green-700" title="Xác nhận">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif

                                @if(in_array($appointment->status, ['pending', 'confirmed']))
                                <form action="{{ route('admin.appointments.cancel', $appointment) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-500 hover:text-red-700"
                                        onclick="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?')"
                                        title="Hủy lịch hẹn">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif

                                @if($appointment->status == 'confirmed')
                                <form action="{{ route('admin.appointments.complete', $appointment) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-500 hover:text-green-700" title="Hoàn thành">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            Không tìm thấy lịch hẹn nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $appointments->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection