@extends('layouts.admin')

@section('title', 'Quản lý lịch hẹn')

@section('header', 'Quản lý lịch hẹn')

@section('content')
<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form action="{{ route('admin.appointments.index') }}" method="GET" class="space-y-4">
        <!-- Search and Quick Actions Row -->
        <div class="flex flex-wrap gap-4 items-center">
            <div class="flex-1 min-w-64">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Tìm kiếm theo mã lịch hẹn, tên khách hàng, email, số điện thoại..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors">
                    <i class="fas fa-search mr-2"></i>Tìm kiếm
                </button>
                <a href="{{ route('admin.appointments.export', request()->query()) }}"
                   class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-download mr-2"></i>Xuất Excel
                </a>
                <button type="button" id="bulkDeleteBtn"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors disabled:opacity-50"
                        disabled>
                    <i class="fas fa-trash mr-2"></i>Xóa đã chọn
                </button>
            </div>
        </div>

        <!-- Advanced Filters Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Từ ngày</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Đến ngày</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dịch vụ</label>
                <select name="service_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    <option value="">Tất cả dịch vụ</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nhân viên</label>
                <select name="employee_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    <option value="">Tất cả nhân viên</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hiển thị</label>
                <select name="per_page" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 bản ghi</option>
                    <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 bản ghi</option>
                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 bản ghi</option>
                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 bản ghi</option>
                </select>
            </div>
        </div>

        <!-- Filter Actions -->
        <div class="flex gap-2">
            <a href="{{ route('admin.appointments.index') }}"
               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                <i class="fas fa-undo mr-2"></i>Xóa bộ lọc
            </a>
        </div>
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
        <!-- Results Info -->
        <div class="flex justify-between items-center mb-4">
            <div class="text-sm text-gray-600">
                Hiển thị {{ $appointments->firstItem() ?? 0 }} - {{ $appointments->lastItem() ?? 0 }}
                trong tổng số {{ $appointments->total() }} lịch hẹn
            </div>
            <div class="flex gap-2">
                <select name="sort_by" id="sortBy" class="px-3 py-1 border border-gray-300 rounded text-sm">
                    <option value="date_appointments" {{ request('sort_by') == 'date_appointments' ? 'selected' : '' }}>Sắp xếp theo ngày</option>
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Sắp xếp theo ngày tạo</option>
                    <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Sắp xếp theo trạng thái</option>
                </select>
                <select name="sort_order" id="sortOrder" class="px-3 py-1 border border-gray-300 rounded text-sm">
                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                </select>
            </div>
        </div>

        <form id="bulkDeleteForm" action="{{ route('admin.appointments.bulk-delete') }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="pb-4 font-semibold w-12">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                            </th>
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
                        <tr class="hover:bg-gray-50">
                            <td class="py-4">
                                @if(in_array($appointment->status, ['cancelled', 'completed']))
                                <input type="checkbox" name="appointment_ids[]" value="{{ $appointment->id }}"
                                       class="appointment-checkbox rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                                @else
                                <span class="text-gray-400" title="Chỉ có thể xóa lịch hẹn đã hủy hoặc hoàn thành">
                                    <i class="fas fa-lock"></i>
                                </span>
                                @endif
                            </td>
                            <td class="py-4">
                                <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $appointment->code }}</span>
                            </td>
                            <td class="py-4">
                                <div>
                                    <p class="font-semibold">{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $appointment->user->email }}</p>
                                    <p class="text-sm text-gray-600">{{ $appointment->user->phone }}</p>
                                </div>
                            </td>
                            <td class="py-4">
                                <div>
                                    <p class="font-semibold">{{ $appointment->service->name }}</p>
                                    <p class="text-sm text-gray-600">{{ number_format($appointment->service->price) }}đ</p>
                                    @if($appointment->employee)
                                        <p class="text-xs text-blue-600">NV: {{ $appointment->employee->name }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4">
                                <div>
                                    <p class="font-semibold">{{ $appointment->date_appointments->format('d/m/Y') }}</p>
                                    <p class="text-sm text-gray-600">{{ optional($appointment->timeAppointment)->started_time }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </td>
                            <td class="py-4">
                                @switch($appointment->status)
                                    @case('pending')
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                            <i class="fas fa-clock mr-1"></i>Chờ xác nhận
                                        </span>
                                        @break
                                    @case('confirmed')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                            <i class="fas fa-check mr-1"></i>Đã xác nhận
                                        </span>
                                        @break
                                    @case('completed')
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                            <i class="fas fa-check-double mr-1"></i>Hoàn thành
                                        </span>
                                        @break
                                    @case('cancelled')
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                            <i class="fas fa-times mr-1"></i>Đã hủy
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td class="py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.appointments.show', $appointment) }}"
                                        class="text-blue-500 hover:text-blue-700 p-1 rounded hover:bg-blue-100 transition-colors"
                                        title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($appointment->status == 'pending')
                                    <a href="{{ route('admin.appointments.assign-staff', $appointment->id) }}"
                                       class="text-green-500 hover:text-green-700 p-1 rounded hover:bg-green-100 transition-colors"
                                       title="Xác nhận và phân công">
                                        <i class="fas fa-user-plus"></i>
                                    </a>
                                    @endif

                                    @if(in_array($appointment->status, ['pending', 'confirmed']))
                                    <form action="{{ route('admin.appointments.cancel', $appointment) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-100 transition-colors"
                                                onclick="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?')"
                                                title="Hủy lịch hẹn">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if($appointment->status == 'confirmed')
                                    <form action="{{ route('admin.appointments.complete', $appointment) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-green-500 hover:text-green-700 p-1 rounded hover:bg-green-100 transition-colors"
                                                title="Hoàn thành">
                                            <i class="fas fa-check-double"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if(in_array($appointment->status, ['cancelled', 'completed']))
                                    <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-100 transition-colors"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa lịch hẹn này khỏi hệ thống?')"
                                                title="Xóa lịch hẹn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium">Không tìm thấy lịch hẹn nào</p>
                                    <p class="text-sm">Thử thay đổi bộ lọc hoặc tìm kiếm với từ khóa khác</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center">
            <div class="text-sm text-gray-600">
                @if($appointments->total() > 0)
                    Trang {{ $appointments->currentPage() }} / {{ $appointments->lastPage() }}
                @endif
            </div>
            <div>
                {{ $appointments->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div id="bulkDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Xác nhận xóa</h3>
                </div>
                <p class="text-gray-600 mb-6">
                    Bạn có chắc chắn muốn xóa <span id="selectedCount">0</span> lịch hẹn đã chọn?
                    Hành động này không thể hoàn tác.
                </p>
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelBulkDelete"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Hủy
                    </button>
                    <button type="button" id="confirmBulkDelete"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        Xóa
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const appointmentCheckboxes = document.querySelectorAll('.appointment-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const bulkDeleteModal = document.getElementById('bulkDeleteModal');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');
    const selectedCountSpan = document.getElementById('selectedCount');
    const cancelBulkDelete = document.getElementById('cancelBulkDelete');
    const confirmBulkDelete = document.getElementById('confirmBulkDelete');
    const sortBy = document.getElementById('sortBy');
    const sortOrder = document.getElementById('sortOrder');

    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        appointmentCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkDeleteButton();
    });

    // Individual checkbox functionality
    appointmentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllCheckbox();
            updateBulkDeleteButton();
        });
    });

    // Update Select All checkbox state
    function updateSelectAllCheckbox() {
        const checkedCount = document.querySelectorAll('.appointment-checkbox:checked').length;
        const totalCount = appointmentCheckboxes.length;

        selectAllCheckbox.checked = checkedCount === totalCount && totalCount > 0;
        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
    }

    // Update bulk delete button state
    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.appointment-checkbox:checked').length;
        bulkDeleteBtn.disabled = checkedCount === 0;
        selectedCountSpan.textContent = checkedCount;
    }

    // Bulk delete button click
    bulkDeleteBtn.addEventListener('click', function() {
        const checkedCount = document.querySelectorAll('.appointment-checkbox:checked').length;
        if (checkedCount > 0) {
            selectedCountSpan.textContent = checkedCount;
            bulkDeleteModal.classList.remove('hidden');
        }
    });

    // Cancel bulk delete
    cancelBulkDelete.addEventListener('click', function() {
        bulkDeleteModal.classList.add('hidden');
    });

    // Confirm bulk delete
    confirmBulkDelete.addEventListener('click', function() {
        bulkDeleteForm.submit();
    });

    // Close modal when clicking outside
    bulkDeleteModal.addEventListener('click', function(e) {
        if (e.target === bulkDeleteModal) {
            bulkDeleteModal.classList.add('hidden');
        }
    });

    // Sort functionality
    function updateSort() {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('sort_by', sortBy.value);
        currentUrl.searchParams.set('sort_order', sortOrder.value);
        window.location.href = currentUrl.toString();
    }

    sortBy.addEventListener('change', updateSort);
    sortOrder.addEventListener('change', updateSort);

    // Auto-submit form when per_page changes
    const perPageSelect = document.querySelector('select[name="per_page"]');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }

    // Initialize button state
    updateBulkDeleteButton();
});
</script>
@endpush