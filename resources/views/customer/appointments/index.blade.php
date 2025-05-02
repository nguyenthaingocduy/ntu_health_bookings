@extends('layouts.app')

@section('title', 'Lịch hẹn của tôi')

@section('content')
<section class="bg-gray-900 text-white py-16">
    <div class="container mx-auto px-6">
        <h1 class="text-4xl font-bold mb-4">Lịch hẹn của tôi</h1>
        <p class="text-xl text-gray-300">Quản lý tất cả các lịch hẹn dịch vụ của bạn.</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-6">
        <div class="mb-8 flex justify-between items-center">
            <h2 class="text-2xl font-bold">Danh sách lịch hẹn</h2>
            <a href="{{ route('customer.appointments.create') }}" class="bg-pink-500 text-white px-6 py-2 rounded-lg hover:bg-pink-600 transition">
                <i class="fas fa-plus mr-2"></i> Đặt lịch mới
            </a>
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

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            @if($appointments->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-calendar-times text-5xl mb-4"></i>
                    <p class="text-xl">Bạn chưa có lịch hẹn nào.</p>
                    <a href="{{ route('customer.appointments.create') }}" class="text-pink-500 inline-block mt-4 hover:underline">
                        Đặt lịch ngay
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">ID</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Dịch vụ</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Ngày đặt</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Ngày hẹn</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Giờ hẹn</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Nhân viên</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Trạng thái</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ substr($appointment->id, 0, 8) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        @if($appointment->service)
                                            {{ $appointment->service->name }}
                                        @else
                                            <span class="text-gray-400">--</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $appointment->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        @if($appointment->timeAppointment)
                                            {{ \Carbon\Carbon::parse($appointment->timeAppointment->started_time)->format('H:i') }}
                                        @else
                                            <span class="text-gray-400">--</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        @if($appointment->employee)
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-6 w-6">
                                                    <img class="h-6 w-6 rounded-full"
                                                        src="https://ui-avatars.com/api/?name={{ $appointment->employee->first_name }}&background=0D8ABC&color=fff&size=128"
                                                        alt="{{ $appointment->employee->first_name }}">
                                                </div>
                                                <div class="ml-2">
                                                    <div class="text-xs">{{ $appointment->employee->first_name }} {{ $appointment->employee->last_name }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500">Chưa phân công</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($appointment->status == 'pending')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                                Chờ xác nhận
                                            </span>
                                        @elseif($appointment->status == 'confirmed')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                Đã xác nhận
                                            </span>
                                        @elseif($appointment->status == 'cancelled')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                Đã hủy
                                            </span>
                                        @elseif($appointment->status == 'completed')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                                Hoàn thành
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                                {{ $appointment->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <div class="flex gap-2">
                                            <a href="{{ route('customer.appointments.show', $appointment->id) }}"
                                                class="text-blue-600 hover:text-blue-800" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if(in_array($appointment->status, ['pending', 'confirmed']))
                                                <form action="{{ route('customer.appointments.cancel', $appointment->id) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?');">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Hủy lịch hẹn">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @php
                                                $canDelete = in_array($appointment->status, ['completed', 'cancelled']);
                                                $isOld = $appointment->created_at->diffInDays(now()) >= 30;
                                            @endphp

                                            @if($canDelete || $isOld)
                                                <form action="{{ route('customer.appointments.destroy', $appointment->id) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch hẹn này khỏi lịch sử? Hành động này không thể hoàn tác.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-600 hover:text-gray-800" title="Xóa khỏi lịch sử">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection