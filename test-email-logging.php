<?php

/**
 * Test script to verify that emails are being logged correctly
 * 
 * Usage: php test-email-logging.php [email]
 * Example: php test-email-logging.php test@example.com
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the email address from the command line argument or use a default
$email = $argv[1] ?? 'ntuhealthbooking@gmail.com';

echo "Testing email logging to: $email\n";
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
    // Create a simple HTML email
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
            <p>Hello,</p>
            <p>This is a test email from the Beauty Spa Booking system.</p>
            <p>If you\'re receiving this email, it means the email system is working correctly.</p>
            <p>Time sent: ' . date('Y-m-d H:i:s') . '</p>
            <p>Thank you for your attention.</p>
        </div>
        <div class="footer">
            <p>&copy; ' . date('Y') . ' Beauty Spa Booking. All rights reserved.</p>
        </div>
    </body>
    </html>
    ';
    
    // Send the email using the Mail facade
    \Illuminate\Support\Facades\Mail::html($html, function ($message) use ($email) {
        $message->to($email)
            ->subject('Test Email from Beauty Spa Booking');
    });
    
    echo "Email sent successfully! Check the logs at storage/logs/laravel.log\n";
    
    // Log the email in the database
    try {
        \App\Models\EmailLog::create([
            'to' => $email,
            'subject' => 'Test Email from Beauty Spa Booking',
            'template' => 'custom-html',
            'data' => json_encode(['html' => $html]),
            'status' => 'sent',
            'attempts' => 1,
            'sent_at' => now(),
        ]);
        
        echo "Email logged successfully in the database!\n";
    } catch (\Exception $e) {
        echo "Error logging email in database: " . $e->getMessage() . "\n";
    }
    
} catch (\Exception $e) {
    echo "Error sending email: " . $e->getMessage() . "\n";
}
