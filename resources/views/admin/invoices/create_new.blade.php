@extends('layouts.admin')

@section('title', 'Tạo hóa đơn mới')

@section('header', 'Tạo hóa đơn mới')

@push('styles')
<style>
    /* Đảm bảo rằng các phần tử trong bảng được hiển thị đúng cách */
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th, td {
        padding: 0.75rem;
        vertical-align: middle;
        border: 1px solid #e5e7eb;
    }
    
    thead {
        background-color: #f9fafb;
    }
    
    /* Đảm bảo rằng các input và select có chiều rộng phù hợp */
    input, select {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        background-color: white;
    }
    
    /* Đảm bảo rằng nút thêm mục hoạt động đúng */
    .btn-add-item {
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background-color: #6366f1;
        color: white;
        font-weight: bold;
        border-radius: 0.5rem;
    }
    
    .btn-add-item:hover {
        background-color: #4f46e5;
    }
    
    /* Đảm bảo rằng nút xóa hoạt động đúng */
    .btn-remove-item {
        cursor: pointer;
        color: #dc2626;
    }
    
    .btn-remove-item:hover {
        color: #b91c1c;
    }
</style>
@endpush

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-900">Tạo hóa đơn mới</h2>
    <p class="mt-1 text-sm text-gray-600">Điền thông tin để tạo hóa đơn mới</p>
</div>

<form id="invoice-form" action="{{ route('admin.invoices.store') }}" method="POST" class="space-y-8">
    @csrf

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin cơ bản</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Khách hàng <span class="text-red-500">*</span>
                    </label>
                    <select id="user_id" name="user_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50">
                        <option value="">-- Chọn khách hàng --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->full_name }} ({{ $customer->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="appointment_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Lịch hẹn (tùy chọn)
                    </label>
                    <select id="appointment_id" name="appointment_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50">
                        <option value="">-- Chọn lịch hẹn --</option>
                        @foreach($appointments as $appointment)
                            <option value="{{ $appointment->id }}" data-customer-id="{{ $appointment->customer_id }}">
                                {{ $appointment->date_appointments->format('d/m/Y') }} -
                                {{ optional($appointment->timeSlot)->formatted_time ?? 'N/A' }} -
                                {{ optional($appointment->service)->name ?? 'N/A' }} -
                                {{ optional($appointment->customer)->full_name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">
                        Phương thức thanh toán <span class="text-red-500">*</span>
                    </label>
                    <select id="payment_method" name="payment_method" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50">
                        <option value="cash">Tiền mặt</option>
                        <option value="bank_transfer">Chuyển khoản</option>
                        <option value="credit_card">Thẻ tín dụng</option>
                    </select>
                </div>

                <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">
                        Trạng thái thanh toán <span class="text-red-500">*</span>
                    </label>
                    <select id="payment_status" name="payment_status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50">
                        <option value="pending">Chờ thanh toán</option>
                        <option value="paid">Đã thanh toán</option>
                        <option value="cancelled">Đã hủy</option>
                        <option value="refunded">Đã hoàn tiền</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                        Ghi chú
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Các mục trong hóa đơn</h3>
                <button type="button" class="btn-add-item" id="add-item-btn">
                    <i class="fas fa-plus mr-2"></i>
                    Thêm mục
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="items-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dịch vụ
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tên mục
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mô tả
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Số lượng
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Đơn giá
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Giảm giá
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thành tiền
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="items-container">
                        <!-- Items will be added here -->
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                Tổng cộng:
                            </td>
                            <td class="px-6 py-4 text-left text-sm font-medium text-gray-900" id="total-amount">
                                0 VNĐ
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-4">
        <a href="{{ route('admin.invoices.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
            Hủy
        </a>
        <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded-lg">
            Tạo hóa đơn
        </button>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let itemIndex = 0;
        const itemsContainer = document.getElementById('items-container');
        const addItemBtn = document.getElementById('add-item-btn');
        const services = @json($services);
        
        // Thêm mục đầu tiên
        addItem();
        
        // Thêm sự kiện click cho nút thêm mục
        addItemBtn.addEventListener('click', function() {
            addItem();
        });
        
        // Sự kiện thay đổi lịch hẹn
        document.getElementById('appointment_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const customerId = selectedOption.getAttribute('data-customer-id');
                document.getElementById('user_id').value = customerId;
            }
        });
        
        // Hàm thêm mục mới
        function addItem() {
            const tr = document.createElement('tr');
            tr.className = 'item-row';
            
            // Tạo ô dịch vụ
            const tdService = document.createElement('td');
            tdService.className = 'px-6 py-4 whitespace-nowrap';
            
            const serviceSelect = document.createElement('select');
            serviceSelect.name = `items[${itemIndex}][service_id]`;
            serviceSelect.className = 'service-select';
            
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = '-- Chọn dịch vụ --';
            serviceSelect.appendChild(defaultOption);
            
            services.forEach(service => {
                const option = document.createElement('option');
                option.value = service.id;
                option.textContent = service.name;
                option.setAttribute('data-price', service.price);
                option.setAttribute('data-name', service.name);
                serviceSelect.appendChild(option);
            });
            
            tdService.appendChild(serviceSelect);
            tr.appendChild(tdService);
            
            // Tạo ô tên mục
            const tdName = document.createElement('td');
            tdName.className = 'px-6 py-4 whitespace-nowrap';
            
            const nameInput = document.createElement('input');
            nameInput.type = 'text';
            nameInput.name = `items[${itemIndex}][item_name]`;
            nameInput.required = true;
            nameInput.className = 'item-name';
            
            tdName.appendChild(nameInput);
            tr.appendChild(tdName);
            
            // Tạo ô mô tả
            const tdDesc = document.createElement('td');
            tdDesc.className = 'px-6 py-4 whitespace-nowrap';
            
            const descInput = document.createElement('input');
            descInput.type = 'text';
            descInput.name = `items[${itemIndex}][item_description]`;
            
            tdDesc.appendChild(descInput);
            tr.appendChild(tdDesc);
            
            // Tạo ô số lượng
            const tdQuantity = document.createElement('td');
            tdQuantity.className = 'px-6 py-4 whitespace-nowrap';
            
            const quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.name = `items[${itemIndex}][quantity]`;
            quantityInput.required = true;
            quantityInput.min = '1';
            quantityInput.value = '1';
            quantityInput.className = 'quantity';
            
            tdQuantity.appendChild(quantityInput);
            tr.appendChild(tdQuantity);
            
            // Tạo ô đơn giá
            const tdPrice = document.createElement('td');
            tdPrice.className = 'px-6 py-4 whitespace-nowrap';
            
            const priceInput = document.createElement('input');
            priceInput.type = 'number';
            priceInput.name = `items[${itemIndex}][unit_price]`;
            priceInput.required = true;
            priceInput.min = '0';
            priceInput.value = '0';
            priceInput.className = 'unit-price';
            
            tdPrice.appendChild(priceInput);
            tr.appendChild(tdPrice);
            
            // Tạo ô giảm giá
            const tdDiscount = document.createElement('td');
            tdDiscount.className = 'px-6 py-4 whitespace-nowrap';
            
            const discountInput = document.createElement('input');
            discountInput.type = 'number';
            discountInput.name = `items[${itemIndex}][discount]`;
            discountInput.min = '0';
            discountInput.value = '0';
            discountInput.className = 'discount';
            
            tdDiscount.appendChild(discountInput);
            tr.appendChild(tdDiscount);
            
            // Tạo ô thành tiền
            const tdTotal = document.createElement('td');
            tdTotal.className = 'px-6 py-4 whitespace-nowrap';
            
            const totalInput = document.createElement('input');
            totalInput.type = 'number';
            totalInput.name = `items[${itemIndex}][total]`;
            totalInput.required = true;
            totalInput.readOnly = true;
            totalInput.className = 'total';
            totalInput.value = '0';
            
            tdTotal.appendChild(totalInput);
            tr.appendChild(tdTotal);
            
            // Tạo ô nút xóa
            const tdAction = document.createElement('td');
            tdAction.className = 'px-6 py-4 whitespace-nowrap text-right text-sm font-medium';
            
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn-remove-item';
            
            const trashIcon = document.createElement('i');
            trashIcon.className = 'fas fa-trash';
            
            removeButton.appendChild(trashIcon);
            tdAction.appendChild(removeButton);
            tr.appendChild(tdAction);
            
            // Thêm hàng vào bảng
            itemsContainer.appendChild(tr);
            
            // Thiết lập các sự kiện
            setupItemEvents(tr);
            
            // Tăng chỉ số
            itemIndex++;
        }
        
        // Thiết lập các sự kiện cho hàng
        function setupItemEvents(row) {
            const serviceSelect = row.querySelector('.service-select');
            const nameInput = row.querySelector('.item-name');
            const quantityInput = row.querySelector('.quantity');
            const priceInput = row.querySelector('.unit-price');
            const discountInput = row.querySelector('.discount');
            const totalInput = row.querySelector('.total');
            const removeButton = row.querySelector('.btn-remove-item');
            
            // Sự kiện thay đổi dịch vụ
            serviceSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    const price = selectedOption.getAttribute('data-price');
                    const name = selectedOption.getAttribute('data-name');
                    
                    priceInput.value = price;
                    nameInput.value = name;
                    
                    calculateItemTotal();
                }
            });
            
            // Sự kiện thay đổi số lượng, đơn giá, giảm giá
            [quantityInput, priceInput, discountInput].forEach(input => {
                input.addEventListener('input', calculateItemTotal);
            });
            
            // Sự kiện xóa mục
            removeButton.addEventListener('click', function() {
                row.remove();
                calculateInvoiceTotal();
            });
            
            // Hàm tính tổng tiền của mục
            function calculateItemTotal() {
                const quantity = parseFloat(quantityInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const discount = parseFloat(discountInput.value) || 0;
                
                const total = (quantity * price) - discount;
                totalInput.value = Math.max(0, total);
                
                calculateInvoiceTotal();
            }
        }
        
        // Hàm tính tổng tiền của hóa đơn
        function calculateInvoiceTotal() {
            const totalInputs = document.querySelectorAll('.total');
            let invoiceTotal = 0;
            
            totalInputs.forEach(input => {
                invoiceTotal += parseFloat(input.value) || 0;
            });
            
            document.getElementById('total-amount').textContent = formatCurrency(invoiceTotal);
        }
        
        // Hàm định dạng tiền tệ
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
        }
    });
</script>
@endpush
@endsection
