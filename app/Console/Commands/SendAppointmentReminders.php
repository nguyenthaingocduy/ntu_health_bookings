<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Notifications\HealthCheckupAppointmentNotification;
use App\Services\EmailNotificationService;
use App\Services\EmailService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        $appointments = Appointment::with(['customer', 'service', 'timeAppointment'])
            ->whereDate('date_appointments', $tomorrow)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $count = $appointments->count();
        $this->info("Found {$count} appointments to remind");

        if ($count === 0) {
            return 0;
        }

        // Initialize email services
        $emailService = new EmailService();
        $legacyEmailService = new EmailNotificationService();
        $successCount = 0;

        foreach ($appointments as $appointment) {
            try {
                // Skip if the customer doesn't exist
                if (!$appointment->customer) {
                    $this->warn("Customer not found for appointment {$appointment->id}. Skipping...");
                    continue;
                }

                // Try to send using the new email service
                $result = $emailService->sendAppointmentReminder($appointment);

                if ($result) {
                    $successCount++;
                    $this->info("Reminder sent to {$appointment->customer->email} for appointment on {$tomorrow}");
                } else {
                    // Fallback to the legacy email service
                    $this->warn("Using legacy email service for appointment {$appointment->id}");
                    $notification = $legacyEmailService->sendAppointmentReminder($appointment);

                    if ($notification && $notification->status === 'sent') {
                        $successCount++;
                        $this->info("Reminder sent (legacy) to {$appointment->customer->email}");
                    } else {
                        // Fallback to the old notification method as a last resort
                        $this->warn("Using fallback notification method for appointment {$appointment->id}");

                        // Only use the old method if the user property exists
                        if ($appointment->user) {
                            $appointment->user->notify(new HealthCheckupAppointmentNotification($appointment, 'reminder'));
                            $successCount++;
                            $this->info("Reminder sent (fallback) to {$appointment->user->email}");
                        } else {
                            $this->warn("User not found for fallback notification. Skipping...");
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->error("Failed to send reminder for appointment {$appointment->id}: {$e->getMessage()}");
                Log::error("Failed to send appointment reminder", [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Appointment reminders completed. Success: {$successCount}/{$count}");
        return 0;
    }
}
