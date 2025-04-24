@extends('layouts.admin')

@section('title', 'Lịch làm việc')

@section('styles')
<style>
    .fc-event {
        cursor: pointer;
    }
    .fc-event:hover {
        opacity: 0.9;
    }
    .fc-daygrid-day.fc-day-today {
        background-color: rgba(219, 39, 119, 0.1) !important;
    }
    .fc-button-primary {
        background-color: #db2777 !important;
        border-color: #db2777 !important;
    }
    .fc-button-primary:hover {
        background-color: #be185d !important;
        border-color: #be185d !important;
    }
    .fc-button-primary:disabled {
        background-color: #f472b6 !important;
        border-color: #f472b6 !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Lịch làm việc</h1>
            <p class="text-sm text-gray-500 mt-1">Xem lịch làm việc và các buổi chăm sóc khách hàng</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('technician.dashboard') }}" class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-150 shadow-sm border border-gray-200">
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
            <li class="text-indigo-500 font-medium">Lịch làm việc</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="md:col-span-3">
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        Lịch làm việc
                    </h3>
                </div>
                <div class="p-6">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 sticky top-6">
                <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Thông tin
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Chú thích</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-500 rounded-full mr-2"></div>
                                <span class="text-sm text-gray-700">Đã xác nhận</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-sm text-gray-700">Hoàn thành</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-yellow-500 rounded-full mr-2"></div>
                                <span class="text-sm text-gray-700">Chờ xác nhận</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-500 rounded-full mr-2"></div>
                                <span class="text-sm text-gray-700">Đã hủy</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Thống kê</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Hôm nay</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $todayAppointmentsCount ?? 0 }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Tuần này</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $weekAppointmentsCount ?? 0 }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Đã hoàn thành</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $completedAppointmentsCount ?? 0 }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Sắp tới</p>
                                    <p class="text-lg font-medium text-gray-800">{{ $upcomingAppointmentsCount ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 mt-4">
                        <p class="font-medium">Lưu ý:</p>
                        <ul class="list-disc list-inside mt-2 text-sm">
                            <li>Nhấp vào một sự kiện để xem chi tiết.</li>
                            <li>Cập nhật tiến trình và ghi chú sau mỗi buổi chăm sóc.</li>
                            <li>Đảm bảo cập nhật trạng thái hoàn thành sau khi kết thúc buổi chăm sóc.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal chi tiết lịch hẹn -->
<div id="appointmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-4">
        <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-4 border-b border-gray-200 rounded-t-xl">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold text-gray-800 flex items-center" id="modalTitle">
                    Chi tiết lịch hẹn
                </h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4" id="modalContent">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-500">Khách hàng</p>
                        <p class="text-base font-medium text-gray-800" id="customerName"></p>
                    </div>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-500">Ngày & Giờ</p>
                        <p class="text-base font-medium text-gray-800" id="appointmentDateTime"></p>
                    </div>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-500">Dịch vụ</p>
                        <p class="text-base font-medium text-gray-800" id="serviceName"></p>
                    </div>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-500">Trạng thái</p>
                        <p class="text-base font-medium" id="appointmentStatus"></p>
                    </div>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-500">Ghi chú</p>
                        <p class="text-base text-gray-800" id="appointmentNotes"></p>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-150">
                    Đóng
                </button>
                <a href="#" id="viewDetailLink" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors duration-150">
                    Xem chi tiết
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'vi',
            buttonText: {
                today: 'Hôm nay',
                month: 'Tháng',
                week: 'Tuần',
                day: 'Ngày'
            },
            events: @json($events ?? []),
            eventClick: function(info) {
                showAppointmentDetails(info.event);
            }
        });
        calendar.render();
    });
    
    function showAppointmentDetails(event) {
        document.getElementById('customerName').textContent = event.extendedProps.customer_name;
        document.getElementById('appointmentDateTime').textContent = event.extendedProps.formatted_date + ' ' + event.extendedProps.time_slot;
        document.getElementById('serviceName').textContent = event.extendedProps.service_name;
        
        var statusElement = document.getElementById('appointmentStatus');
        statusElement.textContent = event.extendedProps.status_text;
        
        // Đặt màu cho trạng thái
        statusElement.className = 'text-base font-medium';
        if (event.extendedProps.status === 'pending') {
            statusElement.classList.add('text-yellow-600');
        } else if (event.extendedProps.status === 'confirmed') {
            statusElement.classList.add('text-blue-600');
        } else if (event.extendedProps.status === 'completed') {
            statusElement.classList.add('text-green-600');
        } else if (event.extendedProps.status === 'cancelled') {
            statusElement.classList.add('text-red-600');
        }
        
        document.getElementById('appointmentNotes').textContent = event.extendedProps.notes || 'Không có ghi chú';
        document.getElementById('viewDetailLink').href = '/technician/sessions/' + event.extendedProps.id;
        
        document.getElementById('appointmentModal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('appointmentModal').classList.add('hidden');
    }
    
    // Đóng modal khi click bên ngoài
    window.onclick = function(event) {
        var modal = document.getElementById('appointmentModal');
        if (event.target === modal) {
            closeModal();
        }
    }
</script>
@endsection
