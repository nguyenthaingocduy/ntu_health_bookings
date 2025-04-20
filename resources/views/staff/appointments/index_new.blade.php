@extends('layouts.staff_new')

@section('title', 'Quản lý lịch hẹn - Cán bộ viên chức')
@section('page-title', 'Quản lý lịch hẹn')

@section('content')
<div class="container mx-auto">
    <!-- Statistics Cards -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
        <!-- Total Appointments Card -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Tổng số lịch hẹn</p>
                <p class="text-lg font-semibold text-gray-700">{{ $statistics['total'] }}</p>
            </div>
        </div>

        <!-- Pending Appointments Card -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
            <div class="p-3 mr-4 text-yellow-500 bg-yellow-100 rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Chờ xác nhận</p>
                <p class="text-lg font-semibold text-gray-700">{{ $statistics['pending'] }}</p>
            </div>
        </div>

        <!-- Confirmed Appointments Card -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
            <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Đã xác nhận</p>
                <p class="text-lg font-semibold text-gray-700">{{ $statistics['confirmed'] }}</p>
            </div>
        </div>

        <!-- Completed Appointments Card -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-md">
            <div class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Hoàn thành</p>
                <p class="text-lg font-semibold text-gray-700">{{ $statistics['completed'] }}</p>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-700">Danh sách lịch hẹn</h3>
            <p class="text-sm text-gray-500">Quản lý tất cả các lịch hẹn trong hệ thống</p>
        </div>
        <a href="{{ route('staff.appointments.create') }}" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors duration-150 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tạo lịch hẹn mới
        </a>
    </div>

    <!-- Appointments Table -->
    <div class="w-full overflow-hidden rounded-lg shadow-md mb-8">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Mã lịch hẹn</th>
                        <th class="px-4 py-3">Khách hàng</th>
                        <th class="px-4 py-3">Dịch vụ</th>
                        <th class="px-4 py-3">Ngày hẹn</th>
                        <th class="px-4 py-3">Giờ hẹn</th>
                        <th class="px-4 py-3">Trạng thái</th>
                        <th class="px-4 py-3">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($appointments as $appointment)
                    <tr class="text-gray-700 hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div>
                                    <p class="font-semibold">{{ substr($appointment->id, 0, 8) }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($appointment->date_register)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                    <img class="object-cover w-full h-full rounded-full" src="https://ui-avatars.com/api/?name={{ $appointment->customer->first_name }}+{{ $appointment->customer->last_name }}&background=random" alt="" loading="lazy" />
                                    <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->customer->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $appointment->service->name }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $appointment->timeAppointment ? $appointment->timeAppointment->started_time : 'N/A' }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($appointment->status == 'pending')
                                <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full">
                                    Chờ xác nhận
                                </span>
                            @elseif($appointment->status == 'confirmed')
                                <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
                                    Đã xác nhận
                                </span>
                            @elseif($appointment->status == 'completed')
                                <span class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full">
                                    Hoàn thành
                                </span>
                            @elseif($appointment->status == 'cancelled')
                                <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full">
                                    Đã hủy
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('staff.appointments.show', $appointment->id) }}" class="text-blue-500 hover:text-blue-700" title="Xem chi tiết">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('staff.appointments.edit', $appointment->id) }}" class="text-yellow-500 hover:text-yellow-700" title="Chỉnh sửa">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                @if(in_array($appointment->status, ['pending', 'confirmed']))
                                <button type="button" onclick="openCancelModal('{{ $appointment->id }}')" class="text-red-500 hover:text-red-700" title="Hủy lịch hẹn">
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
                        <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                            Không có lịch hẹn nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">
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
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="cancelForm" method="POST" action="">
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
    function openCancelModal(appointmentId) {
        document.getElementById('cancelForm').action = `/staff/appointments/${appointmentId}/cancel`;
        document.getElementById('cancelModal').classList.remove('hidden');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
    }
</script>
@endsection
