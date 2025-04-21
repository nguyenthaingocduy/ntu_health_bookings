<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestEmailConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email? : The email address to send the test to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?: 'ntuhealthbooking@gmail.com';
        
        $this->info('Testing email configuration...');
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
            Mail::raw('This is a test email from the Beauty Salon application at ' . now(), function ($message) use ($email) {
                $message->to($email)
                    ->subject('Test Email from Beauty Salon');
            });
            
            $this->info('Test email sent successfully!');
            Log::info('Test email sent successfully to: ' . $email);
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error sending email: ' . $e->getMessage());
            Log::error('Test email error: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
}
