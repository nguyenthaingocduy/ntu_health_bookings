@extends('layouts.staff_new')

@section('title', 'Lịch làm việc - Cán bộ viên chức')
@section('page-title', 'Lịch làm việc')

@push('styles')
<style>
    .fc-event {
        cursor: pointer;
    }
    .fc-day-today {
        background-color: rgba(219, 39, 119, 0.05) !important;
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
    .fc .fc-button-primary {
        background-color: #EC4899;
        border-color: #DB2777;
    }
    .fc .fc-button-primary:hover {
        background-color: #DB2777;
        border-color: #BE185D;
    }
    .fc .fc-button-primary:not(:disabled).fc-button-active, 
    .fc .fc-button-primary:not(:disabled):active {
        background-color: #BE185D;
        border-color: #9D174D;
    }
    .fc-col-header-cell {
        background-color: #FCE7F3;
    }
    .fc-daygrid-day-number, .fc-col-header-cell-cushion {
        color: #4B5563;
    }
</style>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="bg-white overflow-hidden shadow-xl rounded-2xl mb-8 border border-gray-100">
        <div class="p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row items-center justify-between">
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-r from-pink-500 to-purple-600 flex items-center justify-center mr-4 shadow-lg">
                        <i class="fas fa-calendar-week text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Lịch làm việc</h2>
                        <p class="text-gray-600">Quản lý lịch hẹn và lịch làm việc của bạn</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('staff.appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-medium rounded-lg hover:from-pink-600 hover:to-purple-700 transition shadow-sm">
                        <i class="fas fa-plus mr-2"></i>
                        Tạo lịch hẹn mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100 mb-8">
        <div class="p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div class="flex flex-wrap items-center gap-4 mb-4 md:mb-0">
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
                <div class="flex flex-wrap gap-2">
                    <button id="today-btn" class="px-3 py-1.5 bg-pink-100 hover:bg-pink-200 rounded-lg text-sm font-medium text-pink-700 transition">
                        Hôm nay
                    </button>
                    <button id="prev-btn" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium text-gray-700 transition">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button id="next-btn" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium text-gray-700 transition">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" id="view-dropdown" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium text-gray-700 flex items-center transition">
                            <span id="current-view">Tháng</span>
                            <i class="fas fa-chevron-down ml-1"></i>
                        </button>
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute right-0 mt-1 w-32 bg-white rounded-lg shadow-lg z-10 border border-gray-100"
                             style="display: none;">
                            <div class="py-1">
                                <button data-view="dayGridMonth" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-700">Tháng</button>
                                <button data-view="timeGridWeek" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-700">Tuần</button>
                                <button data-view="timeGridDay" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-700">Ngày</button>
                                <button data-view="listWeek" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-700">Danh sách</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="calendar" class="fc-theme-standard"></div>
        </div>
    </div>

    <!-- Today's Appointments -->
    <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100 mb-8">
        <div class="p-6 sm:p-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <span class="bg-purple-100 text-purple-600 p-2 rounded-lg mr-3">
                        <i class="fas fa-calendar-day"></i>
                    </span>
                    Lịch hẹn hôm nay
                </h2>
            </div>
            
            @if(isset($todayAppointments) && count($todayAppointments) > 0)
                <div class="overflow-x-auto bg-white rounded-xl shadow-inner">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 rounded-tl-xl">
                                    Thời gian
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50">
                                    Khách hàng
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50">
                                    Dịch vụ
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50">
                                    Trạng thái
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 rounded-tr-xl">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($todayAppointments as $appointment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-gray-700 flex items-center">
                                        <span class="bg-blue-50 text-blue-600 p-1 rounded-lg mr-2">
                                            <i class="fas fa-clock text-xs"></i>
                                        </span>
                                        {{ $appointment->timeAppointment ? $appointment->timeAppointment->started_time : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 font-medium">{{ substr($appointment->customer->first_name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $appointment->customer->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $appointment->service->name }}</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="px-3 py-1.5 inline-flex items-center text-xs leading-5 font-semibold rounded-full 
                                        {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $appointment->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        @if($appointment->status === 'pending')
                                            <i class="fas fa-clock mr-1"></i> Chờ xác nhận
                                        @elseif($appointment->status === 'confirmed')
                                            <i class="fas fa-check-circle mr-1"></i> Đã xác nhận
                                        @elseif($appointment->status === 'completed')
                                            <i class="fas fa-check-double mr-1"></i> Hoàn thành
                                        @elseif($appointment->status === 'cancelled')
                                            <i class="fas fa-times-circle mr-1"></i> Đã hủy
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('staff.appointments.show', $appointment->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(in_array($appointment->status, ['pending', 'confirmed']))
                                        <a href="{{ route('staff.appointments.complete', $appointment->id) }}" class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    @endif
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
                    <h3 class="text-gray-700 font-semibold text-xl mb-2">Không có lịch hẹn hôm nay</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Bạn không có lịch hẹn nào được đặt cho ngày hôm nay.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Appointment Detail Modal -->
<div id="appointment-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 flex items-center" id="modal-title">
                            <span class="bg-pink-100 text-pink-600 p-2 rounded-lg mr-3">
                                <i class="fas fa-calendar-check"></i>
                            </span>
                            Chi tiết lịch hẹn
                        </h3>
                        <div class="mt-6 border-t border-gray-200 pt-4">
                            <div class="grid grid-cols-1 gap-y-4">
                                <div class="flex items-center">
                                    <div class="w-1/3 text-sm font-medium text-gray-500">Khách hàng:</div>
                                    <div class="w-2/3 text-sm text-gray-900 font-semibold" id="modal-customer"></div>
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
                                <div class="flex items-start">
                                    <div class="w-1/3 text-sm font-medium text-gray-500 pt-1">Ghi chú:</div>
                                    <div class="w-2/3 text-sm text-gray-900" id="modal-notes"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <a href="#" id="modal-view-link" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-base font-medium text-white hover:from-pink-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 sm:ml-3 sm:w-auto sm:text-sm transition">
                    Xem chi tiết
                </a>
                <button type="button" id="modal-close" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
            events: {!! json_encode($events ?? []) !!},
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
        const viewButtons = document.querySelectorAll('[data-view]');
        const currentView = document.getElementById('current-view');
        
        viewButtons.forEach(button => {
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
            });
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
                    statusHtml = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1"></i> Chờ xác nhận</span>';
                    break;
                case 'confirmed':
                    statusHtml = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"><i class="fas fa-check-circle mr-1"></i> Đã xác nhận</span>';
                    break;
                case 'completed':
                    statusHtml = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"><i class="fas fa-check-double mr-1"></i> Hoàn thành</span>';
                    break;
                case 'cancelled':
                    statusHtml = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i> Đã hủy</span>';
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
@endpush
