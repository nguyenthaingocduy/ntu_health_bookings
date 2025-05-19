@extends('layouts.admin')

@section('title', 'Tạo hóa đơn mới')

@section('header', 'Tạo hóa đơn mới')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tạo hóa đơn mới</h1>
            <p class="text-sm text-gray-500 mt-1">Tạo hóa đơn cho khách hàng và dịch vụ</p>
        </div>
        <a href="{{ route('admin.invoices.index') }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Quay lại
        </a>
    </div>

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <form id="invoice-form" action="{{ route('admin.invoices.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin cơ bản</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Khách hàng <span class="text-red-500">*</span>
                            </label>
                            <select id="user_id" name="user_id" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('user_id') border-red-500 @enderror">
                                <option value="">-- Chọn khách hàng --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->full_name }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="appointment_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Lịch hẹn (tùy chọn)
                            </label>
                            <select id="appointment_id" name="appointment_id"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('appointment_id') border-red-500 @enderror">
                                <option value="">-- Chọn lịch hẹn --</option>
                                @foreach($appointments as $appointment)
                                    <option value="{{ $appointment->id }}" data-customer-id="{{ $appointment->customer_id }}" {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                        {{ $appointment->date_appointments->format('d/m/Y') }} -
                                        {{ optional($appointment->timeAppointment)->formatted_time ?? 'N/A' }} -
                                        {{ optional($appointment->service)->name ?? 'N/A' }} -
                                        {{ optional($appointment->customer)->full_name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('appointment_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                Phương thức thanh toán <span class="text-red-500">*</span>
                            </label>
                            <select id="payment_method" name="payment_method" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('payment_method') border-red-500 @enderror">
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tiền mặt</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản</option>
                                <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Thẻ tín dụng</option>
                            </select>
                            @error('payment_method')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">
                                Trạng thái thanh toán <span class="text-red-500">*</span>
                            </label>
                            <select id="payment_status" name="payment_status" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('payment_status') border-red-500 @enderror">
                                <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                                <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                <option value="cancelled" {{ old('payment_status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                <option value="refunded" {{ old('payment_status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                            </select>
                            @error('payment_status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Ghi chú
                            </label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Các mục trong hóa đơn</h3>
                        <button type="button" id="add-item-btn" class="flex items-center px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Thêm mục
                        </button>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="items-table">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Dịch vụ
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tên mục
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Mô tả
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Số lượng
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Đơn giá
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Giảm giá
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Thành tiền
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="items-container">
                                    <!-- Items will be added here -->
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="6" class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                            Tổng cộng:
                                        </td>
                                        <td class="px-4 py-3 text-left text-sm font-medium text-gray-900" id="total-amount">
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
                    <a href="{{ route('admin.invoices.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-150">
                        Hủy
                    </a>
                    <button type="submit" class="px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors duration-150">
                        Tạo hóa đơn
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
            tdService.className = 'px-4 py-3 whitespace-nowrap';

            const serviceSelect = document.createElement('select');
            serviceSelect.name = `items[${itemIndex}][service_id]`;
            serviceSelect.className = 'service-select w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent';

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
            tdName.className = 'px-4 py-3 whitespace-nowrap';

            const nameInput = document.createElement('input');
            nameInput.type = 'text';
            nameInput.name = `items[${itemIndex}][item_name]`;
            nameInput.required = true;
            nameInput.className = 'item-name w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent';

            tdName.appendChild(nameInput);
            tr.appendChild(tdName);

            // Tạo ô mô tả
            const tdDesc = document.createElement('td');
            tdDesc.className = 'px-4 py-3 whitespace-nowrap';

            const descInput = document.createElement('input');
            descInput.type = 'text';
            descInput.name = `items[${itemIndex}][item_description]`;
            descInput.className = 'w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent';

            tdDesc.appendChild(descInput);
            tr.appendChild(tdDesc);

            // Tạo ô số lượng
            const tdQuantity = document.createElement('td');
            tdQuantity.className = 'px-4 py-3 whitespace-nowrap';

            const quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.name = `items[${itemIndex}][quantity]`;
            quantityInput.required = true;
            quantityInput.min = '1';
            quantityInput.value = '1';
            quantityInput.className = 'quantity w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent';

            tdQuantity.appendChild(quantityInput);
            tr.appendChild(tdQuantity);

            // Tạo ô đơn giá
            const tdPrice = document.createElement('td');
            tdPrice.className = 'px-4 py-3 whitespace-nowrap';

            const priceInput = document.createElement('input');
            priceInput.type = 'number';
            priceInput.name = `items[${itemIndex}][unit_price]`;
            priceInput.required = true;
            priceInput.min = '0';
            priceInput.value = '0';
            priceInput.className = 'unit-price w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent';

            tdPrice.appendChild(priceInput);
            tr.appendChild(tdPrice);

            // Tạo ô giảm giá
            const tdDiscount = document.createElement('td');
            tdDiscount.className = 'px-4 py-3 whitespace-nowrap';

            const discountInput = document.createElement('input');
            discountInput.type = 'number';
            discountInput.name = `items[${itemIndex}][discount]`;
            discountInput.min = '0';
            discountInput.value = '0';
            discountInput.className = 'discount w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent';

            tdDiscount.appendChild(discountInput);
            tr.appendChild(tdDiscount);

            // Tạo ô thành tiền
            const tdTotal = document.createElement('td');
            tdTotal.className = 'px-4 py-3 whitespace-nowrap';

            const totalInput = document.createElement('input');
            totalInput.type = 'number';
            totalInput.name = `items[${itemIndex}][total]`;
            totalInput.required = true;
            totalInput.readOnly = true;
            totalInput.className = 'total w-full px-3 py-2 border rounded-lg bg-gray-100';
            totalInput.value = '0';

            tdTotal.appendChild(totalInput);
            tr.appendChild(tdTotal);

            // Tạo ô nút xóa
            const tdAction = document.createElement('td');
            tdAction.className = 'px-4 py-3 whitespace-nowrap text-right text-sm font-medium';

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'text-red-500 hover:text-red-700';

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
            const removeButton = row.querySelector('.text-red-500');

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
