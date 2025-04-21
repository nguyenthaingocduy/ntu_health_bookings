<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nhắc nhở lịch hẹn - {{ $app_name }}</title>
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
        .reminder-box {
            background-color: #fff3cd;
            border-left: 3px solid #ffc107;
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
        <h2>Nhắc nhở lịch hẹn</h2>
        
        <p>Xin chào {{ $user_name }},</p>
        
        <div class="reminder-box">
            <p><strong>Đây là lời nhắc nhở về lịch hẹn sắp tới của bạn tại {{ $app_name }}.</strong></p>
        </div>
        
        <div class="appointment-details">
            <h3>Thông tin lịch hẹn:</h3>
            <p><strong>Mã lịch hẹn:</strong> #{{ substr($appointment->id, 0, 8) }}</p>
            <p><strong>Dịch vụ:</strong> {{ $service_name }}</p>
            <p><strong>Ngày hẹn:</strong> {{ \Carbon\Carbon::parse($appointment_date)->format('d/m/Y') }}</p>
            <p><strong>Giờ hẹn:</strong> {{ $appointment_time }}</p>
        </div>

        <p>Bạn có thể xem chi tiết lịch hẹn và quản lý lịch hẹn của mình bằng cách nhấp vào nút bên dưới:</p>

        <div style="text-align: center;">
            <a href="{{ $appointment_url }}" class="button">Xem lịch hẹn</a>
        </div>

        <p><strong>Lưu ý quan trọng:</strong></p>
        <ul>
            <li>Vui lòng đến trước giờ hẹn 15 phút để làm thủ tục.</li>
            <li>Nếu bạn cần hủy hoặc thay đổi lịch hẹn, vui lòng thông báo cho chúng tôi càng sớm càng tốt.</li>
            <li>Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua số điện thoại hoặc email.</li>
        </ul>

        <p>Chúng tôi rất mong được gặp bạn!</p>

        <p>Trân trọng,<br>
        Đội ngũ {{ $app_name }}</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ $current_year }} {{ $app_name }}. Tất cả các quyền được bảo lưu.</p>
        <p>Đây là email tự động, vui lòng không trả lời email này.</p>
    </div>
</body>
</html>
