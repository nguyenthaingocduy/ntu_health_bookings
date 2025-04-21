<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

echo "Testing email sending...\n";

try {
    // Test email address
    $testEmail = 'ntuhealthbooking@gmail.com';
    
    // Log mail configuration
    echo "Mail Configuration:\n";
    echo "MAIL_MAILER: " . config('mail.default') . "\n";
    echo "MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
    echo "MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
    echo "MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
    echo "MAIL_PASSWORD: " . (config('mail.mailers.smtp.password') ? '[SET]' : '[NOT SET]') . "\n";
    echo "MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption') . "\n";
    echo "MAIL_FROM_ADDRESS: " . config('mail.from.address') . "\n";
    echo "MAIL_FROM_NAME: " . config('mail.from.name') . "\n\n";
    
    // Send a test email
    echo "Sending test email to: $testEmail\n";
    
    Mail::raw('This is a test email from the Beauty Salon application at ' . date('Y-m-d H:i:s'), function ($message) use ($testEmail) {
        $message->to($testEmail)
            ->subject('Test Email from Beauty Salon');
    });
    
    echo "Test email sent successfully!\n";
} catch (\Exception $e) {
    echo "Error sending email: " . $e->getMessage() . "\n";
    Log::error('Test email error: ' . $e->getMessage());
}
