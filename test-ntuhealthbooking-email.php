<?php

/**
 * Test script for ntuhealthbooking@gmail.com email configuration
 * 
 * Usage: php test-ntuhealthbooking-email.php [recipient_email]
 * Example: php test-ntuhealthbooking-email.php test@example.com
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the recipient email from command line argument or use default
$recipientEmail = $argv[1] ?? 'ntuhealthbooking@gmail.com';

echo "Testing email configuration for ntuhealthbooking@gmail.com\n";
echo "Sending test email to: $recipientEmail\n\n";

// Display mail configuration
echo "Mail Configuration:\n";
echo "MAIL_MAILER: " . config('mail.default') . "\n";
echo "MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
echo "MAIL_PASSWORD: " . (config('mail.mailers.smtp.password') ? '[SET]' : '[NOT SET]') . "\n";
echo "MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption') . "\n";
echo "MAIL_FROM_ADDRESS: " . config('mail.from.address') . "\n";
echo "MAIL_FROM_NAME: " . config('mail.from.name') . "\n\n";

try {
    // Create a more detailed HTML email
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Test Email from Beauty Spa Booking</title>
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
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Beauty Spa Booking</h1>
        </div>
        <div class="content">
            <h2>Test Email</h2>
            <p>Xin chào,</p>
            <p>Đây là email kiểm tra từ hệ thống Beauty Spa Booking.</p>
            <p>Nếu bạn nhận được email này, điều đó có nghĩa là cấu hình email đang hoạt động bình thường.</p>
            <p>Thời gian gửi: ' . date('Y-m-d H:i:s') . '</p>
            <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.</p>
        </div>
        <div class="footer">
            <p>&copy; ' . date('Y') . ' Beauty Spa Booking. Tất cả các quyền được bảo lưu.</p>
        </div>
    </body>
    </html>
    ';
    
    // Send the email
    \Illuminate\Support\Facades\Mail::html($html, function ($message) use ($recipientEmail) {
        $message->to($recipientEmail)
            ->subject('Test Email from Beauty Spa Booking');
    });
    
    echo "Test email sent successfully!\n";
    \Illuminate\Support\Facades\Log::info('Test email sent successfully to: ' . $recipientEmail);
} catch (\Exception $e) {
    echo "Error sending email: " . $e->getMessage() . "\n";
    \Illuminate\Support\Facades\Log::error('Test email error: ' . $e->getMessage());
}
