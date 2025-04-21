<?php

/**
 * Direct email test script
 * This script sends an email directly to duynguyen.11032003@gmail.com
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Target email address
$email = 'duynguyen.11032003@gmail.com';

echo "Testing direct email sending to: $email\n";
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
        <title>Test Email from NTU Health Booking</title>
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
                border-bottom: 3px solid #7c4dff;
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
                color: #7c4dff;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>NTU Health Booking</h1>
        </div>
        <div class="content">
            <h2>Test Email</h2>
            <p>Hello,</p>
            <p>This is a test email from the NTU Health Booking system.</p>
            <p>If you\'re receiving this email, it means the email system is working correctly.</p>
            <p>Time sent: ' . date('Y-m-d H:i:s') . '</p>
            <p>Thank you for your attention.</p>
        </div>
        <div class="footer">
            <p>&copy; ' . date('Y') . ' NTU Health Booking. All rights reserved.</p>
        </div>
    </body>
    </html>
    ';
    
    // Send the email
    \Illuminate\Support\Facades\Mail::html($html, function ($message) use ($email) {
        $message->to($email)
            ->subject('Test Email from NTU Health Booking System');
    });
    
    echo "Email sent successfully!\n";
    \Illuminate\Support\Facades\Log::info('Direct test email sent successfully to: ' . $email);
} catch (\Exception $e) {
    echo "Error sending email: " . $e->getMessage() . "\n";
    \Illuminate\Support\Facades\Log::error('Direct test email error: ' . $e->getMessage());
}
