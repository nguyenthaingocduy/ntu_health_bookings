<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0 0 5px;
            color: #333;
        }
        .header p {
            margin: 0 0 5px;
            color: #666;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-info-box {
            width: 48%;
        }
        .invoice-info-box h2 {
            font-size: 16px;
            margin: 0 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        .invoice-info-box p {
            margin: 0 0 5px;
        }
        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .invoice-items th, .invoice-items td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .invoice-items th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .invoice-items .text-right {
            text-align: right;
        }
        .invoice-total {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .invoice-total th, .invoice-total td {
            padding: 10px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }
        .invoice-total th {
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        .signature-box {
            width: 48%;
            text-align: center;
        }
        .signature-box p {
            margin: 0 0 30px;
        }
        .signature-line {
            border-top: 1px solid #ddd;
            padding-top: 5px;
            margin-top: 50px;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-paid {
            background-color: #d1fae5;
            color: #047857;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-cancelled {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .status-refunded {
            background-color: #f3f4f6;
            color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>NTU HEALTH BOOKING</h1>
            <p>02 Nguyễn Đình Chiểu, Nha Trang, Khánh Hòa</p>
            <p>Điện thoại: (0258) 2471303 | Email: ntuhealthbooking@gmail.com</p>
            <h1 style="margin-top: 20px;">HÓA ĐƠN</h1>
            <p>Số: {{ $invoice->invoice_number }}</p>
            <p>Ngày: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</p>
        </div>

        <div class="invoice-info">
            <div class="invoice-info-box">
                <h2>Thông tin khách hàng</h2>
                <p><strong>Họ tên:</strong> {{ $invoice->user->full_name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $invoice->user->email ?? 'N/A' }}</p>
                <p><strong>Điện thoại:</strong> {{ $invoice->user->phone ?? 'N/A' }}</p>
                <p><strong>Địa chỉ:</strong> {{ $invoice->user->address ?? 'N/A' }}</p>
            </div>
            <div class="invoice-info-box">
                <h2>Thông tin hóa đơn</h2>
                <p><strong>Mã hóa đơn:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Ngày tạo:</strong> {{ $invoice->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Phương thức thanh toán:</strong>
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
                <p><strong>Trạng thái:</strong>
                    @if($invoice->payment_status == 'paid')
                        <span class="status status-paid">Đã thanh toán</span>
                    @elseif($invoice->payment_status == 'pending')
                        <span class="status status-pending">Chờ thanh toán</span>
                    @elseif($invoice->payment_status == 'cancelled')
                        <span class="status status-cancelled">Đã hủy</span>
                    @elseif($invoice->payment_status == 'refunded')
                        <span class="status status-refunded">Đã hoàn tiền</span>
                    @else
                        {{ $invoice->payment_status }}
                    @endif
                </p>
            </div>
        </div>

        @if($invoice->appointment)
        <div style="margin-bottom: 30px;">
            <h2 style="font-size: 16px; margin: 0 0 10px; padding-bottom: 5px; border-bottom: 1px solid #eee;">Thông tin lịch hẹn</h2>
            <p><strong>Mã lịch hẹn:</strong> {{ $invoice->appointment->appointment_code ?? 'N/A' }}</p>
            <p><strong>Dịch vụ:</strong> {{ $invoice->appointment->service->name ?? 'N/A' }}</p>
            <p><strong>Ngày hẹn:</strong> {{ $invoice->appointment->appointment_date ? $invoice->appointment->appointment_date->format('d/m/Y H:i') : 'N/A' }}</p>
        </div>
        @endif

        <table class="invoice-items">
            <thead>
                <tr>
                    <th>Dịch vụ</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-right">Số lượng</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @if($invoice->appointment && $invoice->appointment->service)
                <tr>
                    <td>{{ $invoice->appointment->service->name }}</td>
                    <td class="text-right">{{ number_format($invoice->appointment->service->price, 0, ',', '.') }} VNĐ</td>
                    <td class="text-right">1</td>
                    <td class="text-right">{{ number_format($invoice->appointment->service->price, 0, ',', '.') }} VNĐ</td>
                </tr>
                @endif
            </tbody>
        </table>

        <table class="invoice-total">
            <tr>
                <th>Tổng cộng:</th>
                <td>{{ number_format($invoice->total, 0, ',', '.') }} VNĐ</td>
            </tr>
        </table>

        @if($invoice->notes)
        <div style="margin-bottom: 30px;">
            <h2 style="font-size: 16px; margin: 0 0 10px; padding-bottom: 5px; border-bottom: 1px solid #eee;">Ghi chú</h2>
            <p>{{ $invoice->notes }}</p>
        </div>
        @endif

        <div class="signature">
            <div class="signature-box">
                <p>Người lập hóa đơn</p>
                <div class="signature-line">
                    {{ $invoice->creator->full_name ?? 'N/A' }}
                </div>
            </div>
            <div class="signature-box">
                <p>Khách hàng</p>
                <div class="signature-line">
                    {{ $invoice->user->full_name ?? 'N/A' }}
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</p>
            <p>NTU Health Booking - Chăm sóc sức khỏe và sắc đẹp của bạn</p>
        </div>
    </div>
</body>
</html>
