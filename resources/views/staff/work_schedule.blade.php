@extends('layouts.staff_tailwind')

@section('title', 'Lịch làm việc - Cán bộ viên chức')

@section('styles')
<style>
    .fc-event {
        cursor: pointer;
    }
    .fc-day-today {
        background-color: rgba(59, 130, 246, 0.05) !important;
    }
    .fc-event-pending {
        background-color: #FCD34D !important;
        border-color: #F59E0B !important;
    }
    .fc-event-confirmed {
        background-color: #60A5FA !important;
        border-color: #3B82F6 !important;
    }
    .fc-event-completed {
        background-color: #34D399 !important;
        border-color: #10B981 !important;
    }
    .fc-event-cancelled {
        background-color: #F87171 !important;
        border-color: #EF4444 !important;
    }
</style>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
@endsection

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Lịch làm việc</h1>
        <p class="text-gray-600">Quản lý lịch hẹn và lịch làm việc của bạn</p>
    </div>
    <div class="mt-4 md:mt-0 flex space-x-3">
        <a href="{{ route('staff.appointments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-plus mr-2"></i>
            Tạo lịch hẹn mới
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div class="flex items-center space-x-4 mb-4 md:mb-0">
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                    <span class="text-sm text-gray-600">Chờ xác nhận</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                    <span class="text-sm text-gray-600">Đã xác nhận</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                    <span class="text-sm text-gray-600">Hoàn thành</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                    <span class="text-sm text-gray-600">Đã hủy</span>
                </div>
            </div>
            <div class="flex space-x-2">
                <button id="today-btn" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded text-sm font-medium text-gray-700">
                    Hôm nay
                </button>
                <button id="prev-btn" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded text-sm font-medium text-gray-700">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="next-btn" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded text-sm font-medium text-gray-700">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div class="relative">
                    <button id="view-dropdown" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded text-sm font-medium text-gray-700 flex items-center">
                        <span id="current-view">Tháng</span>
                        <i class="fas fa-chevron-down ml-1"></i>
                    </button>
                    <div id="view-options" class="hidden absolute right-0 mt-1 w-32 bg-white rounded-md shadow-lg z-10">
                        <div class="py-1">
                            <button data-view="dayGridMonth" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tháng</button>
                            <button data-view="timeGridWeek" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tuần</button>
                            <button data-view="timeGridDay" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ngày</button>
                            <button data-view="listWeek" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Danh sách</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="calendar" class="fc-theme-standard"></div>
    </div>
</div>

<!-- Appointment Detail Modal -->
<div id="appointment-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Chi tiết lịch hẹn
                        </h3>
                        <div class="mt-4 border-t border-gray-200 pt-4">
                            <div class="grid grid-cols-1 gap-y-4">
                                <div class="flex items-center">
                                    <div class="w-1/3 text-sm font-medium text-gray-500">Khách hàng:</div>
                                    <div class="w-2/3 text-sm text-gray-900" id="modal-customer"></div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-1/3 text-sm font-medium text-gray-500">Dịch vụ:</div>
                                    <div class="w-2/3 text-sm text-gray-900" id="modal-service"></div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-1/3 text-sm font-medium text-gray-500">Ngày giờ:</div>
                                    <div class="w-2/3 text-sm text-gray-900" id="modal-datetime"></div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-1/3 text-sm font-medium text-gray-500">Trạng thái:</div>
                                    <div class="w-2/3" id="modal-status"></div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-1/3 text-sm font-medium text-gray-500">Ghi chú:</div>
                                    <div class="w-2/3 text-sm text-gray-900" id="modal-notes"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <a href="#" id="modal-view-link" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Xem chi tiết
                </a>
                <button type="button" id="modal-close" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/vi.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize calendar
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: false,
            locale: 'vi',
            firstDay: 1, // Monday
            height: 'auto',
            dayMaxEvents: true,
            events: {!! json_encode($events) !!},
            eventClick: function(info) {
                showAppointmentDetails(info.event);
            },
            eventClassNames: function(arg) {
                return ['fc-event-' + arg.event.extendedProps.status];
            }
        });
        calendar.render();
        
        // Calendar navigation
        document.getElementById('today-btn').addEventListener('click', function() {
            calendar.today();
        });
        
        document.getElementById('prev-btn').addEventListener('click', function() {
            calendar.prev();
        });
        
        document.getElementById('next-btn').addEventListener('click', function() {
            calendar.next();
        });
        
        // View dropdown
        const viewDropdown = document.getElementById('view-dropdown');
        const viewOptions = document.getElementById('view-options');
        const currentView = document.getElementById('current-view');
        
        viewDropdown.addEventListener('click', function() {
            viewOptions.classList.toggle('hidden');
        });
        
        document.querySelectorAll('#view-options button').forEach(button => {
            button.addEventListener('click', function() {
                const view = this.getAttribute('data-view');
                calendar.changeView(view);
                
                // Update current view text
                switch(view) {
                    case 'dayGridMonth':
                        currentView.textContent = 'Tháng';
                        break;
                    case 'timeGridWeek':
                        currentView.textContent = 'Tuần';
                        break;
                    case 'timeGridDay':
                        currentView.textContent = 'Ngày';
                        break;
                    case 'listWeek':
                        currentView.textContent = 'Danh sách';
                        break;
                }
                
                viewOptions.classList.add('hidden');
            });
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!viewDropdown.contains(event.target)) {
                viewOptions.classList.add('hidden');
            }
        });
        
        // Modal handling
        const modal = document.getElementById('appointment-modal');
        const modalClose = document.getElementById('modal-close');
        
        modalClose.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
        
        // Show appointment details in modal
        function showAppointmentDetails(event) {
            const props = event.extendedProps;
            
            document.getElementById('modal-customer').textContent = props.customerName;
            document.getElementById('modal-service').textContent = props.serviceName;
            document.getElementById('modal-datetime').textContent = props.formattedDate + ' ' + props.formattedTime;
            document.getElementById('modal-notes').textContent = props.notes || 'Không có ghi chú';
            
            // Set status with appropriate badge
            const statusEl = document.getElementById('modal-status');
            let statusHtml = '';
            
            switch(props.status) {
                case 'pending':
                    statusHtml = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Chờ xác nhận</span>';
                    break;
                case 'confirmed':
                    statusHtml = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Đã xác nhận</span>';
                    break;
                case 'completed':
                    statusHtml = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hoàn thành</span>';
                    break;
                case 'cancelled':
                    statusHtml = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Đã hủy</span>';
                    break;
            }
            
            statusEl.innerHTML = statusHtml;
            
            // Set view link
            document.getElementById('modal-view-link').href = props.viewUrl;
            
            // Show modal
            modal.classList.remove('hidden');
        }
    });
</script>
@endsection
