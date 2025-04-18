<!DOCTYPE html>
<html>
<head>
    <title>Chào mừng đến với NTU Health Booking</title>
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
            color: #0d6efd;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0d6efd;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Beauty Salon</h1>
        </div>
        
        <div class="content">
            <h2>Xin chào {{ $user->first_name }} {{ $user->last_name }},</h2>
            
            <p>Chúng tôi rất vui mừng thông báo rằng bạn đã đăng ký tài khoản thành công tại NTU Health Booking!</p>
            
            <p>Thông tin tài khoản của bạn:</p>
            <ul>
                <li><strong>Họ và tên:</strong> {{ $user->first_name }} {{ $user->last_name }}</li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Số điện thoại:</strong> {{ $user->phone }}</li>
            </ul>
            
            <p>Bạn có thể đăng nhập vào tài khoản của mình bằng cách nhấp vào nút bên dưới:</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="button">Đăng nhập ngay</a>
            </div>
            
            <p>Nếu bạn có bất kỳ câu hỏi hoặc cần hỗ trợ, vui lòng liên hệ với chúng tôi qua email hoặc hotline.</p>
            
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