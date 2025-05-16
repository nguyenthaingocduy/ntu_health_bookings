<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #e91e63;
        }
        .header p {
            margin: 5px 0;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-info-box {
            width: 48%;
        }
        .invoice-info-box h3 {
            font-size: 14px;
            margin: 0 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        .invoice-info-box p {
            margin: 5px 0;
        }
        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .invoice-items th, .invoice-items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .invoice-items th {
            background-color: #f5f5f5;
        }
        .invoice-total {
            text-align: right;
            margin-bottom: 30px;
        }
        .invoice-total table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .invoice-total table td {
            padding: 5px;
        }
        .invoice-total table tr.total td {
            font-weight: bold;
            border-top: 1px solid #ddd;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-refunded {
            background-color: #e2e3e5;
            color: #383d41;
        }
        .notes {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $companyInfo['name'] }}</h1>
            <p>{{ $companyInfo['address'] }}</p>
            <p>Điện thoại: {{ $companyInfo['phone'] }} | Email: {{ $companyInfo['email'] }}</p>
            @if($companyInfo['tax_id'])
            <p>Mã số thuế: {{ $companyInfo['tax_id'] }}</p>
            @endif
        </div>

        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="font-size: 18px; margin: 0;">HÓA ĐƠN</h2>
            <p style="margin: 5px 0;">Số: {{ $invoice->invoice_number }}</p>
            <p style="margin: 5px 0;">Ngày: {{ $invoice->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="invoice-info">
            <div class="invoice-info-box">
                <h3>Thông tin khách hàng</h3>
                <p><strong>Tên:</strong> {{ $invoice->user->full_name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $invoice->user->email ?? 'N/A' }}</p>
                <p><strong>Điện thoại:</strong> {{ $invoice->user->phone ?? 'N/A' }}</p>
                <p><strong>Địa chỉ:</strong> {{ $invoice->user->address ?? 'N/A' }}</p>
            </div>

            <div class="invoice-info-box">
                <h3>Thông tin hóa đơn</h3>
                <p><strong>Mã hóa đơn:</strong> {{ $invoice->invoice_number }}</p>
                <p>
                    <strong>Trạng thái:</strong>
                    @if($invoice->payment_status == 'pending')
                        <span class="status status-pending">Chờ thanh toán</span>
                    @elseif($invoice->payment_status == 'paid')
                        <span class="status status-paid">Đã thanh toán</span>
                    @elseif($invoice->payment_status == 'cancelled')
                        <span class="status status-cancelled">Đã hủy</span>
                    @elseif($invoice->payment_status == 'refunded')
                        <span class="status status-refunded">Đã hoàn tiền</span>
                    @endif
                </p>
                <p>
                    <strong>Phương thức thanh toán:</strong>
                    @if($invoice->payment_method == 'cash')
                        Tiền mặt
                    @elseif($invoice->payment_method == 'bank_transfer')
                        Chuyển khoản
                    @elseif($invoice->payment_method == 'credit_card')
                        Thẻ tín dụng
                    @else
                        {{ $invoice->payment_method }}
                    @endif
                </p>
                <p><strong>Người tạo:</strong> {{ $invoice->creator->full_name ?? 'N/A' }}</p>
            </div>
        </div>

        @if($invoice->appointment)
        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 14px; margin: 0 0 10px 0; padding-bottom: 5px; border-bottom: 1px solid #ddd;">
                Thông tin lịch hẹn
            </h3>
            <p><strong>Ngày hẹn:</strong> {{ $invoice->appointment->date_appointments->format('d/m/Y') }}</p>
            <p><strong>Giờ hẹn:</strong> {{ optional($invoice->appointment->timeSlot)->formatted_time ?? 'N/A' }}</p>
            <p><strong>Dịch vụ:</strong> {{ optional($invoice->appointment->service)->name ?? 'N/A' }}</p>
        </div>
        @endif

        <table class="invoice-items">
            <thead>
                <tr>
                    <th style="width: 5%;">STT</th>
                    <th style="width: 35%;">Tên mục</th>
                    <th style="width: 10%;">Số lượng</th>
                    <th style="width: 15%;">Đơn giá</th>
                    <th style="width: 15%;">Giảm giá</th>
                    <th style="width: 20%;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $item->item_name }}
                        @if($item->item_description)
                        <br><small>{{ $item->item_description }}</small>
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 0, ',', '.') }} VNĐ</td>
                    <td>{{ number_format($item->discount, 0, ',', '.') }} VNĐ</td>
                    <td>{{ number_format($item->total, 0, ',', '.') }} VNĐ</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="invoice-total">
            <table>
                <tr>
                    <td>Tổng tiền hàng:</td>
                    <td>{{ number_format($invoice->subtotal, 0, ',', '.') }} VNĐ</td>
                </tr>
                @if($invoice->discount > 0)
                <tr>
                    <td>Giảm giá:</td>
                    <td>{{ number_format($invoice->discount, 0, ',', '.') }} VNĐ</td>
                </tr>
                @endif
                {{-- Không hiển thị thuế vì giá đã bao gồm tất cả --}}
                <tr class="total">
                    <td>Tổng cộng:</td>
                    <td>{{ number_format($invoice->total, 0, ',', '.') }} VNĐ</td>
                </tr>
            </table>
        </div>

        @if($invoice->notes)
        <div class="notes">
            <h3 style="font-size: 14px; margin: 0 0 10px 0;">Ghi chú</h3>
            <p>{{ $invoice->notes }}</p>
        </div>
        @endif

        <div style="margin-top: 50px; display: flex; justify-content: space-between;">
            <div style="width: 30%; text-align: center;">
                <p style="margin-bottom: 50px;">Người mua hàng</p>
                <p>(Ký, ghi rõ họ tên)</p>
            </div>

            <div style="width: 30%; text-align: center;">
                <p style="margin-bottom: 50px;">Người bán hàng</p>
                <p>(Ký, ghi rõ họ tên)</p>
            </div>
        </div>

        <div class="footer">
            <p>Hóa đơn được tạo bởi hệ thống {{ $companyInfo['name'] }}</p>
            <p>Ngày in: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
