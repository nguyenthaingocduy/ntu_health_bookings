@extends('layouts.admin')

@section('title', 'Quản lý khách hàng')
@section('header', 'Quản lý khách hàng')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        {{-- <h1 class="text-2xl font-semibold text-gray-800">Quản lý khách hàng</h1> --}}
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                        <span class="sr-only">Dismiss</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                        <span class="sr-only">Dismiss</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-pink-500 from-purple-500 to-indigo-600 px-6 py-4 flex justify-between items-center">
            <h2 class="text-white text-lg font-semibold">Danh sách khách hàng</h2>
            <form action="{{ route('admin.customers.index') }}" method="GET" class="relative">
                <div class="flex">
                    <input type="text" name="search" class="w-64 px-4 py-2 rounded-l-lg border-0 focus:ring-2 focus:ring-purple-300 focus:outline-none"
                        placeholder="Tìm kiếm khách hàng..." value="{{ request('search') }}">
                    <button type="submit" class="bg-white text-purple-500 px-4 rounded-r-lg hover:text-purple-700 transition duration-200">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Họ tên</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số điện thoại</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đăng ký</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lịch hẹn</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $customer->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $appointmentCount = $customer->appointments()->count();
                                    $completedCount = $customer->appointments()->where('status', 'completed')->count();
                                @endphp
                                <div class="flex items-center">
                                    <span class="mr-2 text-sm font-medium">{{ $appointmentCount }} lịch hẹn</span>
                                    @if($appointmentCount > 0)
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($completedCount / $appointmentCount) * 100 }}%"></div>
                                        </div>
                                        <span class="ml-2 text-xs text-gray-500">{{ $completedCount }} hoàn thành</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.customers.show', $customer->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1 rounded-lg transition duration-200">
                                        <i class="fas fa-eye mr-1"></i> Chi tiết
                                    </a>
                                    <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                        class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 px-3 py-1 rounded-lg transition duration-200">
                                        <i class="fas fa-edit mr-1"></i> Sửa
                                    </a>
                                    <button onclick="confirmDelete('{{ $customer->id }}', {{ json_encode($customer->first_name . ' ' . $customer->last_name) }})"
                                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-lg transition duration-200">
                                        <i class="fas fa-trash mr-1"></i> Xóa
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                                    <p>Không có khách hàng nào</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Xác nhận xóa khách hàng</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Bạn có chắc chắn muốn xóa khách hàng <span id="customerName" class="font-semibold"></span>?
                </p>
                <p class="text-sm text-red-600 mt-2">
                    Hành động này không thể hoàn tác!
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <div class="flex space-x-3">
                    <button id="cancelDelete"
                        class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Hủy
                    </button>
                    <button id="confirmDelete"
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Xóa
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentCustomerId = null;

function confirmDelete(customerId, customerName) {
    console.log('confirmDelete called with:', customerId, customerName);

    currentCustomerId = customerId;
    document.getElementById('customerName').textContent = customerName;
    document.getElementById('deleteModal').classList.remove('hidden');

    // Set up the delete form action
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/admin/customers/${customerId}`;

    console.log('Form action set to:', deleteForm.action);
}

// Handle confirm delete - only set once
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up event handlers');

    const confirmBtn = document.getElementById('confirmDelete');
    const cancelBtn = document.getElementById('cancelDelete');
    const modal = document.getElementById('deleteModal');

    console.log('Elements found:', {
        confirmBtn: !!confirmBtn,
        cancelBtn: !!cancelBtn,
        modal: !!modal
    });

    if (confirmBtn) {
        confirmBtn.onclick = function() {
            console.log('Confirm delete clicked');
            if (currentCustomerId) {
                const deleteForm = document.getElementById('deleteForm');
                console.log('Submitting form to:', deleteForm.action);
                deleteForm.submit();
            } else {
                console.log('No customer ID set');
            }
        };
    }

    // Handle cancel delete
    if (cancelBtn) {
        cancelBtn.onclick = function() {
            console.log('Cancel delete clicked');
            if (modal) {
                modal.classList.add('hidden');
            }
            currentCustomerId = null;
        };
    }

    // Close modal when clicking outside
    if (modal) {
        modal.onclick = function(e) {
            if (e.target === this) {
                console.log('Modal background clicked');
                this.classList.add('hidden');
                currentCustomerId = null;
            }
        };
    }
});
</script>
@endsection
