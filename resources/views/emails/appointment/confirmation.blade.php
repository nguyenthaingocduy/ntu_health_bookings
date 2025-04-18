<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận đặt lịch tại Beauty Salon</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #f8f9fa;
        }
        .content {
            padding: 20px;
            background-color: #ffffff;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            font-size: 12px;
            color: #6c757d;
            background-color: #f8f9fa;
        }
        h1 {
            color: #ec4899;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ec4899;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .appointment-details {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .appointment-details h3 {
            margin-top: 0;
            color: #ec4899;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Beauty Salon</h1>
        </div>

        <div class="content">
            <h2>Xin chào {{ $appointment->customer->first_name }} {{ $appointment->customer->last_name }},</h2>

            <p>Cảm ơn bạn đã đặt lịch dịch vụ tại Beauty Salon. Chúng tôi xác nhận lịch hẹn của bạn đã được đặt thành công!</p>

            <div class="appointment-details">
                <h3>Thông tin lịch hẹn:</h3>
                <p><strong>Mã lịch hẹn:</strong> #{{ substr($appointment->id, 0, 8) }}</p>
                <p><strong>Dịch vụ:</strong> {{ $appointment->service->name }}</p>
                <p><strong>Ngày hẹn:</strong> {{ \Carbon\Carbon::parse($appointment->date_appointments)->format('d/m/Y') }}</p>
                <p><strong>Giờ hẹn:</strong> {{ $appointment->timeAppointment->started_time }}</p>
                <p><strong>Trạng thái:</strong>
                    @if($appointment->status == 'pending')
                        Chờ xác nhận
                    @elseif($appointment->status == 'confirmed')
                        Đã xác nhận
                    @else
                        {{ $appointment->status }}
                    @endif
                </p>
                @if($appointment->notes)
                <p><strong>Ghi chú:</strong> {{ $appointment->notes }}</p>
                @endif
            </div>

            <p>Bạn có thể xem chi tiết lịch hẹn và quản lý lịch hẹn của mình bằng cách nhấp vào nút bên dưới:</p>

            <div style="text-align: center;">
                <a href="{{ route('customer.appointments.show', $appointment->id) }}" class="button">Xem lịch hẹn</a>
            </div>

            <p><strong>Lưu ý quan trọng:</strong></p>
            <ul>
                <li>Vui lòng đến trước giờ hẹn 15 phút để làm thủ tục.</li>
                <li>Nếu bạn muốn hủy hoặc thay đổi lịch hẹn, vui lòng thông báo cho chúng tôi ít nhất 24 giờ trước giờ hẹn.</li>
                <li>Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua số điện thoại hoặc email.</li>
            </ul>

            <p>Trân trọng,<br>
            Đội ngũ Beauty Salon</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Beauty Salon. Tất cả các quyền được bảo lưu.</p>
            <p>Đây là email tự động, vui lòng không trả lời email này.</p>
        </div>
    </div>
</body>
</html>