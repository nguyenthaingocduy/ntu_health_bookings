<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Xác nhận đặt lịch - {{ $app_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 3px solid #ec4899;
        }
        .content {
            padding: 20px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        h1 {
            color: #ec4899;
        }
        .button {
            display: inline-block;
            background-color: #ec4899;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .appointment-details {
            background-color: #f9f9f9;
            border-left: 3px solid #ec4899;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $app_name }}</h1>
    </div>

    <div class="content">
        <h2>Xác nhận đặt lịch</h2>

        <p>Xin chào {{ $user_name }},</p>

        <p>Cảm ơn bạn đã đặt lịch tại {{ $app_name }}. Dưới đây là thông tin chi tiết về lịch hẹn của bạn:</p>

        <div class="appointment-details">
            <h3>Thông tin lịch hẹn:</h3>
            <p><strong>Mã lịch hẹn:</strong> #{{ substr($appointment->id, 0, 8) }}</p>
            <p><strong>Dịch vụ:</strong> {{ $service_name }}</p>
            <p><strong>Ngày hẹn:</strong> {{ \Carbon\Carbon::parse($appointment_date)->format('d/m/Y') }}</p>
            <p><strong>Giờ hẹn:</strong> {{ $appointment_time }}</p>
            <p><strong>Nhân viên phục vụ:</strong>
                @if($appointment->employee)
                    {{ $appointment->employee->first_name }} {{ $appointment->employee->last_name }}
                @else
                    Chưa phân công
                @endif
            </p>
            <p><strong>Trạng thái:</strong>
                @if($appointment->status == 'pending')
                    Chờ xác nhận
                @elseif($appointment->status == 'confirmed')
                    Đã xác nhận
                @elseif($appointment->status == 'completed')
                    Đã hoàn thành
                @elseif($appointment->status == 'cancelled')
                    Đã hủy
                @endif
            </p>
        </div>

        <p>Bạn có thể xem chi tiết lịch hẹn và quản lý lịch hẹn của mình bằng cách nhấp vào nút bên dưới:</p>

        <div style="text-align: center;">
            <a href="{{ $appointment_url }}" class="button">Xem lịch hẹn</a>
        </div>

        <p style="font-size: 12px; color: #666; margin-top: 10px; text-align: center;">
            Nếu nút trên không hoạt động, vui lòng <a href="{{ $dashboard_url }}" style="color: #ec4899;">nhấp vào đây</a> để truy cập trang quản lý lịch hẹn của bạn.
        </p>

        <p><strong>Lưu ý quan trọng:</strong></p>
        <ul>
            <li>Vui lòng đến trước giờ hẹn 15 phút để làm thủ tục.</li>
            <li>Nếu bạn muốn hủy hoặc thay đổi lịch hẹn, vui lòng thông báo cho chúng tôi ít nhất 24 giờ trước giờ hẹn.</li>
            <li>Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua số điện thoại hoặc email.</li>
        </ul>

        <p>Trân trọng,<br>
        Đội ngũ {{ $app_name }}</p>
    </div>

    <div class="footer">
        <p>&copy; {{ $current_year }} {{ $app_name }}.</p>
        <p>Đây là email tự động, vui lòng không trả lời email này.</p>
    </div>
</body>
</html>
