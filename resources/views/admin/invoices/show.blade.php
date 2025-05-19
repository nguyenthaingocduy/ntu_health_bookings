@extends('layouts.admin')

@section('title', 'Chi tiết hóa đơn')

@section('header', 'Chi tiết hóa đơn')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chi tiết hóa đơn</h1>
            <p class="text-sm text-gray-500 mt-1">Xem thông tin chi tiết về hóa đơn</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.invoices.index') }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
            <a href="{{ route('admin.invoices.pdf', $invoice->id) }}" class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                In hóa đơn
            </a>
            @if($invoice->payment_status == 'pending')
            <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Chỉnh sửa
            </a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="border-b border-gray-200 pb-6 mb-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Hóa đơn #{{ $invoice->invoice_number }}</h2>
                        <p class="text-sm text-gray-500 mt-1">Ngày tạo: {{ $invoice->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        @if($invoice->payment_status == 'paid')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Đã thanh toán
                            </span>
                        @elseif($invoice->payment_status == 'pending')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Chờ thanh toán
                            </span>
                        @elseif($invoice->payment_status == 'cancelled')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Đã hủy
                            </span>
                        @elseif($invoice->payment_status == 'refunded')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Đã hoàn tiền
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ $invoice->payment_status }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Thông tin khách hàng</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-900">{{ $invoice->user->full_name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Email: {{ $invoice->user->email ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Điện thoại: {{ $invoice->user->phone ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Địa chỉ: {{ $invoice->user->address ?? 'N/A' }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Thông tin hóa đơn</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-2">
                            <p class="text-sm font-medium text-gray-600">Mã hóa đơn:</p>
                            <p class="text-sm text-gray-900">{{ $invoice->invoice_number }}</p>

                            <p class="text-sm font-medium text-gray-600">Ngày hóa đơn:</p>
                            <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</p>

                            <p class="text-sm font-medium text-gray-600">Phương thức thanh toán:</p>
                            <p class="text-sm text-gray-900">
                                @if($invoice->payment_method == 'cash')
                                    Tiền mặt
                                @elseif($invoice->payment_method == 'credit_card')
                                    Thẻ tín dụng
                                @elseif($invoice->payment_method == 'bank_transfer')
                                    Chuyển khoản
                                @else
                                    {{ $invoice->payment_method }}
                                @endif
                            </p>

                            <p class="text-sm font-medium text-gray-600">Người tạo:</p>
                            <p class="text-sm text-gray-900">{{ $invoice->creator->full_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($invoice->appointment)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Thông tin lịch hẹn</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Mã lịch hẹn:</p>
                            <p class="text-sm text-gray-900">
                                <a href="{{ route('admin.appointments.show', $invoice->appointment->id) }}" class="text-blue-600 hover:text-blue-900">
                                    {{ $invoice->appointment->appointment_code ?? 'N/A' }}
                                </a>
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600">Dịch vụ:</p>
                            <p class="text-sm text-gray-900">
                                {{ $invoice->appointment->service->name ?? 'N/A' }}
                                @if($invoice->appointment->promotion_code)
                                    <span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                        Mã KM: {{ $invoice->appointment->promotion_code }}
                                    </span>
                                @endif
                            </p>
                            @if($invoice->discount > 0)
                                @php
                                    $discountPercent = round(($invoice->discount / $invoice->subtotal) * 100);
                                @endphp
                                <p class="text-sm text-gray-600 mt-1">
                                    Giá gốc: <span class="line-through">{{ number_format($invoice->subtotal, 0, ',', '.') }} VNĐ</span>
                                </p>
                                <p class="text-sm text-red-600">
                                    Giảm giá: <span class="font-medium">{{ number_format($invoice->discount, 0, ',', '.') }} VNĐ</span>
                                    <span class="ml-1 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">-{{ $discountPercent }}%</span>
                                </p>
                                <p class="text-sm text-gray-700 mt-1">
                                    Giá sau giảm: <span class="font-medium">{{ number_format($invoice->subtotal - $invoice->discount, 0, ',', '.') }} VNĐ</span>
                                </p>
                                <p class="text-sm text-gray-900 font-medium mt-1">
                                    Phải thanh toán: {{ number_format($invoice->total, 0, ',', '.') }} VNĐ
                                </p>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600">Ngày hẹn:</p>
                            <p class="text-sm text-gray-900">{{ $invoice->appointment->appointment_date ? $invoice->appointment->appointment_date->format('d/m/Y H:i') : ($invoice->appointment->date_appointments ? $invoice->appointment->date_appointments->format('d/m/Y') . ' ' . (optional($invoice->appointment->timeSlot)->formatted_time ?? '') : 'N/A') }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600">Trạng thái:</p>
                            <p class="text-sm text-gray-900">
                                @if($invoice->appointment->status == 'completed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Hoàn thành
                                    </span>
                                @elseif($invoice->appointment->status == 'confirmed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Đã xác nhận
                                    </span>
                                @elseif($invoice->appointment->status == 'cancelled')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Đã hủy
                                    </span>
                                @elseif($invoice->appointment->status == 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Chờ xác nhận
                                    </span>
                                @elseif($invoice->appointment->status == 'in_progress')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                        Đang thực hiện
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $invoice->appointment->status }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Chi tiết thanh toán</h3>
                <div class="bg-white border rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dịch vụ
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Đơn giá
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Số lượng
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Thành tiền
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($invoice->appointment && $invoice->appointment->service)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $invoice->appointment->service->name }}
                                    @if($invoice->appointment->promotion_code)
                                        <span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                            Mã KM: {{ $invoice->appointment->promotion_code }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                    @if($invoice->discount > 0)
                                        <span class="line-through text-gray-500">{{ number_format($invoice->subtotal, 0, ',', '.') }} VNĐ</span>
                                        <br>
                                        <span class="text-red-600">{{ number_format($invoice->subtotal - $invoice->discount, 0, ',', '.') }} VNĐ</span>
                                        @php
                                            $discountPercent = round(($invoice->discount / $invoice->subtotal) * 100);
                                        @endphp
                                        <span class="ml-1 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">-{{ $discountPercent }}%</span>
                                    @else
                                        {{ number_format($invoice->subtotal, 0, ',', '.') }} VNĐ
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                    1
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                    {{ number_format($invoice->total, 0, ',', '.') }} VNĐ
                                </td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">
                                    Tổng giá gốc:
                                </td>
                                <td class="px-6 py-3 text-right text-sm font-medium text-gray-500">
                                    {{ number_format($invoice->subtotal, 0, ',', '.') }} VNĐ
                                </td>
                            </tr>
                            @if($invoice->discount > 0)
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-red-600">
                                        Giảm giá:
                                    </td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-red-600">
                                        @php
                                            $discountPercent = round(($invoice->discount / $invoice->subtotal) * 100);
                                        @endphp
                                        -{{ number_format($invoice->discount, 0, ',', '.') }} VNĐ ({{ $discountPercent }}%)
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-700">
                                        Giá sau giảm:
                                    </td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-gray-700">
                                        {{ number_format($invoice->subtotal - $invoice->discount, 0, ',', '.') }} VNĐ
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                                    Phải thanh toán:
                                </td>
                                <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                                    {{ number_format($invoice->total, 0, ',', '.') }} VNĐ
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if($invoice->notes)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Ghi chú</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-900">{{ $invoice->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="mt-6 flex justify-between">
    <a href="{{ route('admin.invoices.index') }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Quay lại
    </a>

    @if($invoice->payment_status == 'pending')
    <form action="{{ route('admin.invoices.update-status', $invoice->id) }}" method="POST" class="inline">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="paid">
        <button type="submit" class="flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Đánh dấu đã thanh toán
        </button>
    </form>
    @endif
</div>
@endsection
