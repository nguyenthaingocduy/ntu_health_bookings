<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Notifications\HealthCheckupAppointmentNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-appointment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications for appointments scheduled for the next day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        
        $this->info("Sending reminders for appointments scheduled on {$tomorrow}");
        
        // Get all confirmed appointments for tomorrow
        $appointments = Appointment::with(['user', 'service', 'doctor', 'timeSlot'])
            ->whereDate('appointment_date', $tomorrow)
            ->where('status', 'confirmed')
            ->get();
            
        $this->info("Found {$appointments->count()} appointments to remind");
        
        foreach ($appointments as $appointment) {
            try {
                // Skip if the user doesn't exist
                if (!$appointment->user) {
                    $this->warn("User not found for appointment {$appointment->id}. Skipping...");
                    continue;
                }
                
                // Send reminder notification
                $appointment->user->notify(new HealthCheckupAppointmentNotification($appointment, 'reminder'));
                
                $this->info("Reminder sent to {$appointment->user->email} for appointment at {$appointment->appointment_date->format('H:i')}");
            } catch (\Exception $e) {
                $this->error("Failed to send reminder for appointment {$appointment->id}: {$e->getMessage()}");
            }
        }
        
        $this->info('Appointment reminders sent successfully');
    }
}
