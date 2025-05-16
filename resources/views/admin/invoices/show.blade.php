@extends('layouts.admin')

@section('title', 'Chi tiết hóa đơn')

@section('header', 'Chi tiết hóa đơn')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-semibold text-gray-900">Chi tiết hóa đơn #{{ $invoice->invoice_number }}</h2>
        <p class="mt-1 text-sm text-gray-600">Ngày tạo: {{ $invoice->created_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('admin.invoices.pdf', $invoice->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
            <i class="fas fa-file-pdf mr-2"></i>
            Xuất PDF
        </a>
        @if($invoice->payment_status == 'pending')
        <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
            <i class="fas fa-edit mr-2"></i>
            Chỉnh sửa
        </a>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Thông tin khách hàng -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin khách hàng</h3>
        <div class="space-y-3">
            <div>
                <p class="text-sm font-medium text-gray-500">Tên khách hàng:</p>
                <p class="text-base text-gray-900">{{ $invoice->user->full_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Email:</p>
                <p class="text-base text-gray-900">{{ $invoice->user->email ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Số điện thoại:</p>
                <p class="text-base text-gray-900">{{ $invoice->user->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Địa chỉ:</p>
                <p class="text-base text-gray-900">{{ $invoice->user->address ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    
    <!-- Thông tin hóa đơn -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin hóa đơn</h3>
        <div class="space-y-3">
            <div>
                <p class="text-sm font-medium text-gray-500">Mã hóa đơn:</p>
                <p class="text-base text-gray-900">{{ $invoice->invoice_number }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Trạng thái thanh toán:</p>
                <p class="text-base">{!! $invoice->status_badge !!}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Phương thức thanh toán:</p>
                <p class="text-base text-gray-900">
                    @if($invoice->payment_method == 'cash')
                        <span class="text-green-600"><i class="fas fa-money-bill-wave mr-1"></i> Tiền mặt</span>
                    @elseif($invoice->payment_method == 'bank_transfer')
                        <span class="text-blue-600"><i class="fas fa-university mr-1"></i> Chuyển khoản</span>
                    @elseif($invoice->payment_method == 'credit_card')
                        <span class="text-purple-600"><i class="fas fa-credit-card mr-1"></i> Thẻ tín dụng</span>
                    @else
                        {{ $invoice->payment_method }}
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Người tạo:</p>
                <p class="text-base text-gray-900">{{ $invoice->creator->full_name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    
    <!-- Thông tin lịch hẹn -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin lịch hẹn</h3>
        @if($invoice->appointment)
        <div class="space-y-3">
            <div>
                <p class="text-sm font-medium text-gray-500">Ngày hẹn:</p>
                <p class="text-base text-gray-900">{{ $invoice->appointment->date_appointments->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Giờ hẹn:</p>
                <p class="text-base text-gray-900">{{ optional($invoice->appointment->timeSlot)->formatted_time ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Dịch vụ:</p>
                <p class="text-base text-gray-900">{{ optional($invoice->appointment->service)->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Trạng thái lịch hẹn:</p>
                <p class="text-base text-gray-900">
                    @if($invoice->appointment->status == 'pending')
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Chờ xác nhận</span>
                    @elseif($invoice->appointment->status == 'confirmed')
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Đã xác nhận</span>
                    @elseif($invoice->appointment->status == 'completed')
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Hoàn thành</span>
                    @elseif($invoice->appointment->status == 'cancelled')
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Đã hủy</span>
                    @elseif($invoice->appointment->status == 'in_progress')
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Đang thực hiện</span>
                    @else
                        {{ $invoice->appointment->status }}
                    @endif
                </p>
            </div>
        </div>
        @else
        <p class="text-gray-500 italic">Không có thông tin lịch hẹn</p>
        @endif
    </div>
</div>

<!-- Chi tiết các mục trong hóa đơn -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Chi tiết các mục</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
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
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($invoice->items as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $item->item_name }}</div>
                        @if($item->service)
                        <div class="text-xs text-gray-500">Dịch vụ: {{ $item->service->name }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $item->item_description ?: 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ number_format($item->unit_price, 0, ',', '.') }} VNĐ</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ number_format($item->discount, 0, ',', '.') }} VNĐ</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ number_format($item->total, 0, ',', '.') }} VNĐ</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="5" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                        Tổng cộng:
                    </td>
                    <td class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                        {{ number_format($invoice->total, 0, ',', '.') }} VNĐ
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Ghi chú -->
@if($invoice->notes)
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-2">Ghi chú</h3>
    <p class="text-gray-700">{{ $invoice->notes }}</p>
</div>
@endif

<!-- Thông tin công ty -->
<div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin công ty</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm font-medium text-gray-500">Tên công ty:</p>
            <p class="text-base text-gray-900">{{ $companyInfo['name'] }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Địa chỉ:</p>
            <p class="text-base text-gray-900">{{ $companyInfo['address'] }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Số điện thoại:</p>
            <p class="text-base text-gray-900">{{ $companyInfo['phone'] }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Email:</p>
            <p class="text-base text-gray-900">{{ $companyInfo['email'] }}</p>
        </div>
        @if($companyInfo['tax_id'])
        <div>
            <p class="text-sm font-medium text-gray-500">Mã số thuế:</p>
            <p class="text-base text-gray-900">{{ $companyInfo['tax_id'] }}</p>
        </div>
        @endif
    </div>
</div>

<div class="mt-6 flex justify-between">
    <a href="{{ route('admin.invoices.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
        <i class="fas fa-arrow-left mr-2"></i>
        Quay lại
    </a>
    
    @if($invoice->payment_status == 'pending')
    <form action="{{ route('admin.invoices.update-status', $invoice->id) }}" method="POST" class="inline">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="paid">
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            Đánh dấu đã thanh toán
        </button>
    </form>
    @endif
</div>
@endsection
