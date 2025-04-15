<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:mail {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra chức năng gửi email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?: 'ntuhealthbooking@gmail.com';
        
        $this->info('Bắt đầu kiểm tra gửi email đến: ' . $email);
        
        // Ghi log cấu hình mail
        $this->info('Cấu hình mail:');
        $this->info('MAIL_MAILER: ' . config('mail.mailer'));
        $this->info('MAIL_HOST: ' . config('mail.host'));
        $this->info('MAIL_PORT: ' . config('mail.port'));
        $this->info('MAIL_USERNAME: ' . config('mail.username'));
        $this->info('MAIL_ENCRYPTION: ' . config('mail.encryption'));
        $this->info('MAIL_FROM_ADDRESS: ' . config('mail.from.address'));
        
        try {
            // Gửi mail test
            Mail::raw('Đây là email kiểm tra từ NTU Health Booking vào ' . now(), function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email từ NTU Health Booking');
            });
            
            $this->info('Email test đã được gửi đến ' . $email);
            Log::info('Email test đã được gửi đến ' . $email);
        } catch (\Exception $e) {
            $this->error('Không thể gửi email: ' . $e->getMessage());
            Log::error('Không thể gửi email test: ' . $e->getMessage());
        }
        
        return Command::SUCCESS;
    }
}
