<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Send appointment reminders every day at 8:00 AM
        $schedule->command('app:send-appointment-reminders')->dailyAt('08:00');

        // Send pending emails every 10 minutes
        $schedule->command('emails:send-pending')->everyTenMinutes();

        // Upgrade customer types every Sunday at midnight
        $schedule->command('customers:upgrade-types')->weekly()->sundays()->at('00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
