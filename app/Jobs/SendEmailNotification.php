<?php

namespace App\Jobs;

use App\Models\EmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class SendEmailNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The email notification instance.
     *
     * @var \App\Models\EmailNotification
     */
    protected $notification;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\EmailNotification  $notification
     * @return void
     */
    public function __construct(EmailNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::send([], [], function ($message) {
                $message->to($this->notification->email)
                    ->subject($this->notification->subject)
                    ->setBody($this->notification->message, 'text/html');
                
                $message->from(config('mail.from.address'), config('mail.from.name'));
            });

            $this->notification->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            Log::info('Email sent successfully', [
                'notification_id' => $this->notification->id,
                'type' => $this->notification->type,
                'email' => $this->notification->email,
            ]);
        } catch (Exception $e) {
            Log::error('Failed to send email', [
                'notification_id' => $this->notification->id,
                'error' => $e->getMessage(),
            ]);

            $this->notification->update([
                'status' => 'failed',
            ]);

            if ($this->attempts() >= $this->tries) {
                Log::error('Email sending failed after maximum attempts', [
                    'notification_id' => $this->notification->id,
                ]);
            } else {
                throw $e; // Retry the job
            }
        }
    }
}
