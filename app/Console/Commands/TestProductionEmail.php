<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestProductionEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-production {email? : The email address to send the test to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration in production by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?: 'ntuhealthbooking@gmail.com';
        
        $this->info('Testing production email configuration...');
        $this->info('Mail Configuration:');
        $this->info('MAIL_MAILER: ' . config('mail.default'));
        $this->info('MAIL_HOST: ' . config('mail.mailers.smtp.host'));
        $this->info('MAIL_PORT: ' . config('mail.mailers.smtp.port'));
        $this->info('MAIL_USERNAME: ' . config('mail.mailers.smtp.username'));
        $this->info('MAIL_PASSWORD: ' . (config('mail.mailers.smtp.password') ? '[SET]' : '[NOT SET]'));
        $this->info('MAIL_ENCRYPTION: ' . config('mail.mailers.smtp.encryption'));
        $this->info('MAIL_FROM_ADDRESS: ' . config('mail.from.address'));
        $this->info('MAIL_FROM_NAME: ' . config('mail.from.name'));
        
        $this->info("\nSending test email to: $email");
        
        try {
            // Add a retry mechanism for better reliability
            $maxRetries = 3;
            $retryCount = 0;
            $success = false;
            
            while ($retryCount < $maxRetries && !$success) {
                try {
                    Mail::raw('This is a production test email from the Beauty Salon application at ' . now(), function ($message) use ($email) {
                        $message->to($email)
                            ->subject('Production Test Email from Beauty Salon');
                    });
                    
                    $success = true;
                    $this->info('Test email sent successfully on attempt ' . ($retryCount + 1) . '!');
                    Log::info('Production test email sent successfully to: ' . $email);
                } catch (\Exception $e) {
                    $retryCount++;
                    
                    if ($retryCount < $maxRetries) {
                        $this->warn('Email sending failed on attempt ' . $retryCount . ', retrying... Error: ' . $e->getMessage());
                        Log::warning('Production test email failed on attempt ' . $retryCount . ': ' . $e->getMessage());
                        sleep(2); // Wait 2 seconds before retrying
                    } else {
                        $this->error('Email sending failed after ' . $maxRetries . ' attempts. Error: ' . $e->getMessage());
                        Log::error('Production test email failed after ' . $maxRetries . ' attempts: ' . $e->getMessage());
                        return Command::FAILURE;
                    }
                }
            }
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error sending email: ' . $e->getMessage());
            Log::error('Production test email error: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
}
