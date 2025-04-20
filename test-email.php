<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Message;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Attempting to send test email...\n";
    
    Mail::raw('This is a test email from the Beauty Salon application.', function (Message $message) {
        $message->to('ntuhealthbooking@gmail.com')
            ->subject('Test Email');
    });
    
    echo "Email sent successfully!\n";
} catch (\Exception $e) {
    echo "Error sending email: " . $e->getMessage() . "\n";
    Log::error('Test email error: ' . $e->getMessage());
}
