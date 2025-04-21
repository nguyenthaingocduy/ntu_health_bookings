<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestNtuhealthbookingEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-ntuhealthbooking {email? : The email address to send the test to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email sending from ntuhealthbooking@gmail.com';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?: 'ntuhealthbooking@gmail.com';
        
        $this->info('Testing email configuration for ntuhealthbooking@gmail.com');
        $this->info('Sending test email to: ' . $email);
        
        // Display mail configuration
        $this->info('Mail Configuration:');
        $this->info('MAIL_MAILER: ' . config('mail.default'));
        $this->info('MAIL_HOST: ' . config('mail.mailers.smtp.host'));
        $this->info('MAIL_PORT: ' . config('mail.mailers.smtp.port'));
        $this->info('MAIL_USERNAME: ' . config('mail.mailers.smtp.username'));
        $this->info('MAIL_PASSWORD: ' . (config('mail.mailers.smtp.password') ? '[SET]' : '[NOT SET]'));
        $this->info('MAIL_ENCRYPTION: ' . config('mail.mailers.smtp.encryption'));
        $this->info('MAIL_FROM_ADDRESS: ' . config('mail.from.address'));
        $this->info('MAIL_FROM_NAME: ' . config('mail.from.name'));
        
        // Add a retry mechanism for better reliability
        $maxRetries = 3;
        $retryCount = 0;
        $success = false;
        
        while ($retryCount < $maxRetries && !$success) {
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
                        <p>Thời gian gửi: ' . now()->format('Y-m-d H:i:s') . '</p>
                        <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.</p>
                    </div>
                    <div class="footer">
                        <p>&copy; ' . date('Y') . ' Beauty Spa Booking. Tất cả các quyền được bảo lưu.</p>
                    </div>
                </body>
                </html>
                ';
                
                // Send the email
                Mail::html($html, function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Test Email from Beauty Spa Booking');
                });
                
                $success = true;
                $this->info('Test email sent successfully on attempt ' . ($retryCount + 1) . '!');
                Log::info('Test email sent successfully to: ' . $email);
            } catch (\Exception $e) {
                $retryCount++;
                
                if ($retryCount < $maxRetries) {
                    $this->warn('Email sending failed on attempt ' . $retryCount . ', retrying... Error: ' . $e->getMessage());
                    Log::warning('Test email failed on attempt ' . $retryCount . ': ' . $e->getMessage());
                    sleep(2); // Wait 2 seconds before retrying
                } else {
                    $this->error('Email sending failed after ' . $maxRetries . ' attempts. Error: ' . $e->getMessage());
                    Log::error('Test email failed after ' . $maxRetries . ' attempts: ' . $e->getMessage());
                    return Command::FAILURE;
                }
            }
        }
        
        return Command::SUCCESS;
    }
}
