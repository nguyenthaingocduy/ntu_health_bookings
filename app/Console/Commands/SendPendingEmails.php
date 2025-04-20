<?php

namespace App\Console\Commands;

use App\Jobs\SendEmailNotification;
use App\Models\EmailNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendPendingEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send all pending email notifications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pendingNotifications = EmailNotification::where('status', 'pending')->get();
        
        $count = $pendingNotifications->count();
        
        if ($count === 0) {
            $this->info('No pending emails to send.');
            return 0;
        }
        
        $this->info("Found {$count} pending emails. Processing...");
        
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        foreach ($pendingNotifications as $notification) {
            SendEmailNotification::dispatch($notification);
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("Dispatched {$count} email notifications for sending.");
        
        Log::info("Dispatched {$count} pending email notifications for sending.");
        
        return 0;
    }
}
