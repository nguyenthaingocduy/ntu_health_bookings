@extends('layouts.staff_new')

@section('title', 'Quản lý lịch hẹn - Cán bộ viên chức')
@section('page-title', 'Quản lý lịch hẹn')

@section('content')
<div class="container mx-auto px-6">
    <!-- Statistics Cards -->
    <div class="grid gap-8 mb-10 md:grid-cols-2 xl:grid-cols-4">
        <!-- Total Appointments Card -->
        <div class="flex items-center p-6 bg-white rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition-shadow duration-300">
            <div class="p-4 mr-5 text-blue-600 bg-blue-100 rounded-xl shadow-sm">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-500 uppercase tracking-wide">Tổng số lịch hẹn</p>
                <p class="text-2xl font-bold text-gray-800">{{ $statistics['total'] }}</p>
            </div>
        </div>

        <!-- Pending Appointments Card -->
        <div class="flex items-center p-6 bg-white rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition-shadow duration-300">
            <div class="p-4 mr-5 text-yellow-600 bg-yellow-100 rounded-xl shadow-sm">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-500 uppercase tracking-wide">Chờ xác nhận</p>
                <p class="text-2xl font-bold text-gray-800">{{ $statistics['pending'] }}</p>
            </div>
        </div>

        <!-- Confirmed Appointments Card -->
        <div class="flex items-center p-6 bg-white rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition-shadow duration-300">
            <div class="p-4 mr-5 text-green-600 bg-green-100 rounded-xl shadow-sm">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-500 uppercase tracking-wide">Đã xác nhận</p>
                <p class="text-2xl font-bold text-gray-800">{{ $statistics['confirmed'] }}</p>
            </div>
        </div>

        <!-- Completed Appointments Card -->
        <div class="flex items-center p-6 bg-white rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition-shadow duration-300">
            <div class="p-4 mr-5 text-pink-600 bg-pink-100 rounded-xl shadow-sm">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-500 uppercase tracking-wide">Hoàn thành</p>
                <p class="text-2xl font-bold text-gray-800">{{ $statistics['completed'] }}</p>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Danh sách lịch hẹn</h3>
            <p class="text-base text-gray-500">Quản lý tất cả các lịch hẹn trong hệ thống</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('staff.appointments.create-customer-style') }}" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors duration-150 flex items-center shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Đặt lịch kiểu khách hàng
            </a>
            <a href="{{ route('staff.appointments.create') }}" class="px-6 py-3 bg-pink-600 text-white rounded-xl hover:bg-pink-700 transition-colors duration-150 flex items-center shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tạo lịch hẹn mới
            </a>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="w-full overflow-hidden rounded-xl shadow-md mb-10 border border-gray-200">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wider text-left text-gray-500 uppercase border-b bg-gradient-to-r from-gray-50 to-gray-100">
                        <th class="px-6 py-4">Mã lịch hẹn</th>
                        <th class="px-6 py-4">Khách hàng</th>
                        <th class="px-6 py-4">Dịch vụ</th>
                        <th class="px-6 py-4">Ngày hẹn</th>
                        <th class="px-6 py-4">Giờ hẹn</th>
                        <th class="px-6 py-4">Trạng thái</th>
                        <th class="px-6 py-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($appointments as $appointment)
                    <tr class="text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ substr($appointment->id, 0, 8) }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($appointment->date_register)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="relative hidden w-10 h-10 mr-3 rounded-full md:block overflow-hidden border-2 border-pink-100">
                                    <img class="object-cover w-full h-full rounded-full" src="https://ui-avatars.com/api/?name={{ $appointment->customer->first_name }}+{{ $appointment->customer->last_name }}&background=f9a8d4&color=ffffff" alt="" loading="lazy" />
                                    <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $appointment->customer->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-700">
                            {{ $appointment->service->name }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-700">
                            {{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-700">
                            {{ $appointment->timeAppointment ? $appointment->timeAppointment->started_time : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($appointment->status == 'pending')
                                <span class="px-3 py-1.5 font-medium text-yellow-700 bg-yellow-100 rounded-full inline-flex items-center">
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mr-1.5"></span>
                                    Chờ xác nhận
                                </span>
                            @elseif($appointment->status == 'confirmed')
                                <span class="px-3 py-1.5 font-medium text-green-700 bg-green-100 rounded-full inline-flex items-center">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                                    Đã xác nhận
                                </span>
                            @elseif($appointment->status == 'completed')
                                <span class="px-3 py-1.5 font-medium text-blue-700 bg-blue-100 rounded-full inline-flex items-center">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-1.5"></span>
                                    Hoàn thành
                                </span>
                            @elseif($appointment->status == 'cancelled')
                                <span class="px-3 py-1.5 font-medium text-red-700 bg-red-100 rounded-full inline-flex items-center">
                                    <span class="w-2 h-2 bg-red-400 rounded-full mr-1.5"></span>
                                    Đã hủy
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('staff.appointments.show', $appointment->id) }}" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-150" title="Xem chi tiết">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('staff.appointments.edit', $appointment->id) }}" class="p-2 text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50 rounded-lg transition-colors duration-150" title="Chỉnh sửa">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                @if(in_array($appointment->status, ['pending', 'confirmed']))
                                <button type="button" onclick="openCancelModal('{{ $appointment->id }}')" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-150" title="Hủy lịch hẹn">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">Không có lịch hẹn nào</p>
                                <p class="text-gray-400 text-sm mt-1">Tạo lịch hẹn mới để bắt đầu</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $appointments->links() }}
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
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200">
            <form id="cancelForm" method="POST" action="">
                @csrf
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100 mr-3">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">
                            Hủy lịch hẹn
                        </h3>
                    </div>
                </div>
                <div class="bg-white px-6 py-5">
                    <p class="text-gray-600 mb-5">
                        Bạn có chắc chắn muốn hủy lịch hẹn này? Hành động này không thể hoàn tác.
                    </p>
                    <div class="mb-4">
                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">Lý do hủy</label>
                        <textarea name="cancellation_reason" id="cancellation_reason" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50"></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeCancelModal()" class="px-5 py-2.5 bg-white text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 border border-gray-300 shadow-sm font-medium">
                        Đóng
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-md font-medium">
                        Xác nhận hủy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openCancelModal(appointmentId) {
        document.getElementById('cancelForm').action = `/staff/appointments/${appointmentId}/cancel`;
        document.getElementById('cancelModal').classList.remove('hidden');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
    }
</script>
@endsection
