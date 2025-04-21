<?php

/**
 * Simple script to test email sending in production
 * 
 * Usage: php test-email-production.php [email]
 * Example: php test-email-production.php test@example.com
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the email address from the command line argument or use a default
$email = $argv[1] ?? 'ntuhealthbooking@gmail.com';

echo "Testing email sending to: $email\n";
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
    // Send a test email
    \Illuminate\Support\Facades\Mail::raw('This is a test email from the Beauty Salon application at ' . date('Y-m-d H:i:s'), function ($message) use ($email) {
        $message->to($email)
            ->subject('Production Test Email from Beauty Salon');
    });
    
    echo "Email sent successfully!\n";
    \Illuminate\Support\Facades\Log::info('Production test email sent successfully to: ' . $email);
} catch (\Exception $e) {
    echo "Error sending email: " . $e->getMessage() . "\n";
    \Illuminate\Support\Facades\Log::error('Production test email error: ' . $e->getMessage());
}
