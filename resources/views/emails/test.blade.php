<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test Email from {{ $app_name }}</title>
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
        .test-box {
            background-color: #d1e7dd;
            border-left: 3px solid #198754;
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
        <h2>Test Email</h2>
        
        <div class="test-box">
            <p><strong>Đây là email kiểm tra từ {{ $app_name }}.</strong></p>
            <p>Nếu bạn nhận được email này, điều đó có nghĩa là hệ thống email đang hoạt động bình thường.</p>
            <p>Thời gian gửi: {{ $timestamp }}</p>
        </div>
        
        <p>Email này được gửi tự động để kiểm tra cấu hình email của hệ thống.</p>
        
        <p>Trân trọng,<br>
        Đội ngũ {{ $app_name }}</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ $current_year }} {{ $app_name }}. Tất cả các quyền được bảo lưu.</p>
        <p>Đây là email tự động, vui lòng không trả lời email này.</p>
    </div>
</body>
</html>
