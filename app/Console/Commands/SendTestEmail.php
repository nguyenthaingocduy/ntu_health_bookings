<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EmailService;

class SendTestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-test {email : The email address to send the test to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify email configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
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
        
        // Send the test email
        $emailService = new EmailService();
        $result = $emailService->sendTestEmail($email);
        
        if ($result) {
            $this->info('Test email sent successfully!');
            return Command::SUCCESS;
        } else {
            $this->error('Failed to send test email. Check the logs for more information.');
            return Command::FAILURE;
        }
    }
}
