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
        // Health check at 6:55 AM daily (before potential 7 AM issues)
        $schedule->command('health:check')
            ->dailyAt('06:55')
            ->timezone('Asia/Kolkata')
            ->withoutOverlapping()
            ->onFailure(function () {
                \Log::error('Health check failed at 6:55 AM');
            });

        // Sitemap generation at 2 AM daily
        $schedule->command('sitemap:generate')
            ->dailyAt('02:00')
            ->timezone('Asia/Kolkata')
            ->withoutOverlapping();

        // Cache cleanup at 2:30 AM daily
        $schedule->command('cache:cleanup --max-size=100')
            ->dailyAt('02:30')
            ->timezone('Asia/Kolkata')
            ->withoutOverlapping();

        // Deadline alerts at 8:00 AM — today + 1,2,3 days
        $schedule->command('notify:deadline-alerts --days=0,1,2,3')
            ->dailyAt('08:00')
            ->timezone('Asia/Kolkata')
            ->withoutOverlapping()
            ->onFailure(function () {
                \Log::error('Deadline alert (8AM) failed');
            });

        // Midday reminder for TODAY-only deadlines at 12:00 PM
        $schedule->command('notify:deadline-alerts --days=0')
            ->dailyAt('12:00')
            ->timezone('Asia/Kolkata')
            ->withoutOverlapping()
            ->onFailure(function () {
                \Log::error('Deadline alert (12PM) failed');
            });
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
