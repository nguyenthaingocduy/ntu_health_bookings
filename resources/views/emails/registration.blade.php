<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Chào mừng bạn đến với {{ $app_name }}</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $app_name }}</h1>
    </div>
    
    <div class="content">
        <h2>Chào mừng bạn đến với {{ $app_name }}!</h2>
        
        <p>Xin chào {{ $user->first_name }} {{ $user->last_name }},</p>
        
        <p>Cảm ơn bạn đã đăng ký tài khoản tại {{ $app_name }}. Chúng tôi rất vui mừng được chào đón bạn!</p>
        
        <p>Thông tin tài khoản của bạn:</p>
        <ul>
            <li><strong>Email:</strong> {{ $user->email }}</li>
            <li><strong>Họ và tên:</strong> {{ $user->first_name }} {{ $user->last_name }}</li>
        </ul>
        
        <p>Bạn có thể đăng nhập vào tài khoản của mình bằng cách nhấp vào nút bên dưới:</p>
        
        <div style="text-align: center;">
            <a href="{{ $login_url }}" class="button">Đăng nhập</a>
        </div>
        
        <p>Tại {{ $app_name }}, chúng tôi cung cấp nhiều dịch vụ chăm sóc sức khỏe và làm đẹp chất lượng cao. Hãy khám phá các dịch vụ của chúng tôi và đặt lịch ngay hôm nay!</p>
        
        <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email hoặc số điện thoại được cung cấp trên trang web của chúng tôi.</p>
        
        <p>Trân trọng,<br>
        Đội ngũ {{ $app_name }}</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ $current_year }} {{ $app_name }}.</p>
        <p>Đây là email tự động, vui lòng không trả lời email này.</p>
    </div>
</body>
</html>
