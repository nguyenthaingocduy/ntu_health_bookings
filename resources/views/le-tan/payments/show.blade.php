@extends('layouts.le-tan')

@section('title', 'Chi tiết thanh toán')

@section('header', 'Chi tiết thanh toán')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chi tiết thanh toán</h1>
            <p class="text-sm text-gray-500 mt-1">Xem thông tin chi tiết về thanh toán</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('le-tan.payments.index') }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
            <button onclick="printPayment()" class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                In biên lai
            </button>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Thông tin thanh toán</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-sm font-medium text-gray-500">ID thanh toán:</div>
                            <div class="text-sm text-gray-900">{{ $payment->id }}</div>

                            <div class="text-sm font-medium text-gray-500">Số tiền:</div>
                            <div class="text-sm text-gray-900 font-semibold">
                                @if($payment->appointment && $payment->appointment->service && $payment->amount < $payment->appointment->service->price)
                                    <span class="line-through text-gray-500">{{ number_format($payment->appointment->service->price, 0, ',', '.') }} VNĐ</span>
                                    <span class="text-red-600 ml-2">{{ number_format($payment->amount, 0, ',', '.') }} VNĐ</span>
                                    @php
                                        $discountPercent = round(($payment->appointment->service->price - $payment->amount) / $payment->appointment->service->price * 100);
                                    @endphp
                                    <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Giảm {{ $discountPercent }}%</span>
                                @else
                                    {{ number_format($payment->amount, 0, ',', '.') }} VNĐ
                                @endif
                            </div>

                            <div class="text-sm font-medium text-gray-500">Phương thức:</div>
                            <div class="text-sm text-gray-900">
                                @if($payment->payment_method == 'cash')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Tiền mặt
                                    </span>
                                @elseif($payment->payment_method == 'credit_card')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Thẻ tín dụng
                                    </span>
                                @elseif($payment->payment_method == 'bank_transfer')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Chuyển khoản
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $payment->payment_method }}
                                    </span>
                                @endif
                            </div>

                            <div class="text-sm font-medium text-gray-500">Trạng thái:</div>
                            <div class="text-sm text-gray-900">
                                @if($payment->payment_status == 'completed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Hoàn thành
                                    </span>
                                @elseif($payment->payment_status == 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Đang xử lý
                                    </span>
                                @elseif($payment->payment_status == 'failed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Thất bại
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $payment->payment_status }}
                                    </span>
                                @endif
                            </div>

                            <div class="text-sm font-medium text-gray-500">Mã giao dịch:</div>
                            <div class="text-sm text-gray-900">{{ $payment->transaction_id ?? 'N/A' }}</div>

                            <div class="text-sm font-medium text-gray-500">Ngày tạo:</div>
                            <div class="text-sm text-gray-900">{{ $payment->created_at->format('d/m/Y H:i') }}</div>

                            <div class="text-sm font-medium text-gray-500">Cập nhật lần cuối:</div>
                            <div class="text-sm text-gray-900">{{ $payment->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Thông tin khách hàng</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-sm font-medium text-gray-500">Họ tên:</div>
                            <div class="text-sm text-gray-900">{{ $payment->customer->full_name ?? 'N/A' }}</div>

                            <div class="text-sm font-medium text-gray-500">Email:</div>
                            <div class="text-sm text-gray-900">{{ $payment->customer->email ?? 'N/A' }}</div>

                            <div class="text-sm font-medium text-gray-500">Số điện thoại:</div>
                            <div class="text-sm text-gray-900">{{ $payment->customer->phone ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-4">Thông tin lịch hẹn</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-sm font-medium text-gray-500">Mã lịch hẹn:</div>
                            <div class="text-sm text-gray-900">
                                @if($payment->appointment)
                                    <a href="{{ route('le-tan.appointments.show', $payment->appointment->id) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $payment->appointment->appointment_code ?? 'N/A' }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </div>

                            <div class="text-sm font-medium text-gray-500">Dịch vụ:</div>
                            <div class="text-sm text-gray-900">
                                {{ $payment->appointment->service->name ?? 'N/A' }}
                                @if($payment->appointment && $payment->appointment->promotion_code)
                                    <span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                        Mã KM: {{ $payment->appointment->promotion_code }}
                                    </span>
                                @endif
                            </div>

                            <div class="text-sm font-medium text-gray-500">Ngày hẹn:</div>
                            <div class="text-sm text-gray-900">{{ $payment->appointment->appointment_date ? $payment->appointment->appointment_date->format('d/m/Y H:i') : 'N/A' }}</div>

                            <div class="text-sm font-medium text-gray-500">Trạng thái:</div>
                            <div class="text-sm text-gray-900">
                                @if($payment->appointment)
                                    @if($payment->appointment->status == 'completed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Hoàn thành
                                        </span>
                                    @elseif($payment->appointment->status == 'confirmed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Đã xác nhận
                                        </span>
                                    @elseif($payment->appointment->status == 'cancelled')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Đã hủy
                                        </span>
                                    @elseif($payment->appointment->status == 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Chờ xác nhận
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $payment->appointment->status }}
                                        </span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($payment->notes)
            <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Ghi chú</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-900">{{ $payment->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script>
    function printPayment() {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>Biên lai thanh toán - {{ $payment->id }}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .title { font-size: 18px; font-weight: bold; margin: 10px 0; }
                    .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    .info-table th { text-align: left; width: 30%; padding: 8px; }
                    .info-table td { padding: 8px; }
                    .footer { margin-top: 50px; text-align: right; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>BIÊN LAI THANH TOÁN</h1>
                    <p>NTU Health Booking</p>
                    <p>Ngày: {{ now()->format('d/m/Y H:i') }}</p>
                </div>

                <div class="title">THÔNG TIN THANH TOÁN</div>
                <table class="info-table">
                    <tr>
                        <th>Mã thanh toán:</th>
                        <td>{{ $payment->id }}</td>
                    </tr>
                    <tr>
                        <th>Số tiền:</th>
                        <td>
                            @if($payment->appointment && $payment->appointment->service && $payment->amount < $payment->appointment->service->price)
                                <span style="text-decoration: line-through; color: #888;">{{ number_format($payment->appointment->service->price, 0, ',', '.') }} VNĐ</span>
                                <span style="color: #e53e3e; margin-left: 8px;">{{ number_format($payment->amount, 0, ',', '.') }} VNĐ</span>
                                @php
                                    $discountPercent = round(($payment->appointment->service->price - $payment->amount) / $payment->appointment->service->price * 100);
                                @endphp
                                <span style="margin-left: 8px; padding: 2px 8px; background-color: #fed7d7; color: #9b2c2c; border-radius: 9999px; font-size: 12px;">Giảm {{ $discountPercent }}%</span>
                            @else
                                {{ number_format($payment->amount, 0, ',', '.') }} VNĐ
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Phương thức:</th>
                        <td>
                            @if($payment->payment_method == 'cash')
                                Tiền mặt
                            @elseif($payment->payment_method == 'credit_card')
                                Thẻ tín dụng
                            @elseif($payment->payment_method == 'bank_transfer')
                                Chuyển khoản
                            @else
                                {{ $payment->payment_method }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Trạng thái:</th>
                        <td>
                            @if($payment->payment_status == 'completed')
                                Hoàn thành
                            @elseif($payment->payment_status == 'pending')
                                Đang xử lý
                            @elseif($payment->payment_status == 'failed')
                                Thất bại
                            @else
                                {{ $payment->payment_status }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày tạo:</th>
                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>

                <div class="title">THÔNG TIN KHÁCH HÀNG</div>
                <table class="info-table">
                    <tr>
                        <th>Họ tên:</th>
                        <td>{{ $payment->customer->full_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $payment->customer->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Số điện thoại:</th>
                        <td>{{ $payment->customer->phone ?? 'N/A' }}</td>
                    </tr>
                </table>

                <div class="title">THÔNG TIN DỊCH VỤ</div>
                <table class="info-table">
                    <tr>
                        <th>Mã lịch hẹn:</th>
                        <td>{{ $payment->appointment->appointment_code ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Dịch vụ:</th>
                        <td>
                            {{ $payment->appointment->service->name ?? 'N/A' }}
                            @if($payment->appointment && $payment->appointment->promotion_code)
                                <span style="margin-left: 8px; padding: 2px 8px; background-color: #e6f0ff; color: #1e40af; border-radius: 9999px; font-size: 12px;">
                                    Mã KM: {{ $payment->appointment->promotion_code }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày hẹn:</th>
                        <td>{{ $payment->appointment->appointment_date ? $payment->appointment->appointment_date->format('d/m/Y H:i') : 'N/A' }}</td>
                    </tr>
                </table>

                @if($payment->notes)
                <div class="title">GHI CHÚ</div>
                <p>{{ $payment->notes }}</p>
                @endif

                <div class="footer">
                    <p>Người thanh toán</p>
                    <br><br><br>
                    <p>{{ $payment->customer->full_name ?? 'N/A' }}</p>
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    }
</script>
@endsection
@endsection
