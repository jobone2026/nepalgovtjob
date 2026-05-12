<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class MonitorHealth extends Command
{
    protected $signature = 'health:monitor';
    protected $description = 'Monitor application health and enable maintenance mode on critical errors';

    public function handle()
    {
        try {
            // Check if already in maintenance mode
            if (app()->isDownForMaintenance()) {
                $this->info('Already in maintenance mode');
                return 0;
            }

            // Check 1: Bootstrap cache directory exists
            if (!File::isDirectory(base_path('bootstrap/cache'))) {
                $this->error('Bootstrap cache missing - enabling maintenance mode');
                $this->enableMaintenanceMode('Bootstrap cache directory missing');
                return 1;
            }

            // Check 2: Storage directories are writable
            $criticalDirs = [
                'storage/logs',
                'storage/framework/cache',
                'storage/framework/views',
                'storage/framework/sessions',
            ];

            foreach ($criticalDirs as $dir) {
                $path = base_path($dir);
                if (!File::isDirectory($path) || !File::isWritable($path)) {
                    $this->error("Directory not writable: {$dir}");
                    $this->enableMaintenanceMode("Critical directory issue: {$dir}");
                    return 1;
                }
            }

            // Check 3: Recent error logs
            $logFile = storage_path('logs/laravel.log');
            if (File::exists($logFile)) {
                $recentErrors = $this->checkRecentErrors($logFile);
                if ($recentErrors > 10) {
                    $this->error("Too many recent errors: {$recentErrors}");
                    $this->enableMaintenanceMode("High error rate detected: {$recentErrors} errors");
                    return 1;
                }
            }

            // Check 4: Cache size
            $cacheSize = $this->getCacheSize();
            if ($cacheSize > 500 * 1024 * 1024) { // 500MB
                $this->warn("Cache size critical: " . round($cacheSize / 1024 / 1024, 2) . " MB");
                // Don't enable maintenance, just log warning
                Log::warning('Cache size critical', ['size_mb' => round($cacheSize / 1024 / 1024, 2)]);
            }

            $this->info('Health check passed');
            return 0;

        } catch (\Exception $e) {
            $this->error('Health check failed: ' . $e->getMessage());
            $this->enableMaintenanceMode('Health check exception: ' . $e->getMessage());
            return 1;
        }
    }

    private function enableMaintenanceMode($reason)
    {
        try {
            // Enable maintenance mode with custom message
            Artisan::call('down', [
                '--render' => 'errors.503',
                '--retry' => 60,
            ]);

            // Log the reason
            Log::critical('Maintenance mode enabled automatically', [
                'reason' => $reason,
                'timestamp' => now(),
            ]);

            // Send notification (you can customize this)
            $this->sendNotification($reason);

            $this->info('Maintenance mode enabled');
        } catch (\Exception $e) {
            Log::error('Failed to enable maintenance mode', ['error' => $e->getMessage()]);
        }
    }

    private function checkRecentErrors($logFile)
    {
        $errors = 0;
        $fiveMinutesAgo = now()->subMinutes(5);
        
        try {
            $lines = File::lines($logFile);
            foreach ($lines as $line) {
                if (str_contains($line, '.ERROR:') || str_contains($line, '.CRITICAL:')) {
                    $errors++;
                }
            }
        } catch (\Exception $e) {
            // If can't read log, assume no errors
            return 0;
        }

        return $errors;
    }

    private function getCacheSize()
    {
        $size = 0;
        $cachePath = storage_path('framework/cache/data');
        
        if (!File::isDirectory($cachePath)) {
            return 0;
        }

        try {
            foreach (File::allFiles($cachePath) as $file) {
                $size += $file->getSize();
            }
        } catch (\Exception $e) {
            return 0;
        }

        return $size;
    }

    private function sendNotification($reason)
    {
        // You can implement email/SMS notification here
        // For now, just log it
        Log::critical('ALERT: Site in maintenance mode', [
            'reason' => $reason,
            'url' => config('app.url'),
            'time' => now()->toDateTimeString(),
        ]);
    }
}
