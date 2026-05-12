<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AutoRecover extends Command
{
    protected $signature = 'health:recover';
    protected $description = 'Attempt to auto-recover from common issues and disable maintenance mode';

    public function handle()
    {
        $this->info('Starting auto-recovery process...');
        
        $recovered = false;

        try {
            // Fix 1: Ensure bootstrap/cache exists
            $this->fixBootstrapCache();

            // Fix 2: Ensure storage directories exist and are writable
            $this->fixStorageDirectories();

            // Fix 3: Clean up large cache files (if needed)
            $this->cleanupCache();

            // Check if we can disable maintenance mode
            if (app()->isDownForMaintenance()) {
                $this->info('Attempting to disable maintenance mode...');
                Artisan::call('up');
                $this->info('Maintenance mode disabled');
                
                Log::info('Auto-recovery successful - maintenance mode disabled');
                $recovered = true;
            }

            $this->info('Auto-recovery completed successfully');
            return 0;

        } catch (\Exception $e) {
            $this->error('Auto-recovery failed: ' . $e->getMessage());
            Log::error('Auto-recovery failed', ['error' => $e->getMessage()]);
            return 1;
        }
    }

    private function fixBootstrapCache()
    {
        $path = base_path('bootstrap/cache');
        
        if (!File::isDirectory($path)) {
            $this->warn('Creating bootstrap/cache directory...');
            File::makeDirectory($path, 0775, true);
            $this->info('✓ Bootstrap cache directory created');
        }
    }

    private function fixStorageDirectories()
    {
        $directories = [
            'storage/logs',
            'storage/framework/cache/data',
            'storage/framework/views',
            'storage/framework/sessions',
        ];

        foreach ($directories as $dir) {
            $path = base_path($dir);
            
            if (!File::isDirectory($path)) {
                $this->warn("Creating {$dir}...");
                File::makeDirectory($path, 0775, true);
                $this->info("✓ {$dir} created");
            }
        }

        // Ensure log file exists
        $logFile = storage_path('logs/laravel.log');
        if (!File::exists($logFile)) {
            File::put($logFile, '');
            $this->info('✓ Log file created');
        }
    }

    private function rebuildCaches()
    {
        $this->info('Clearing caches...');
        
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:cache');
            
            $this->info('✓ Caches rebuilt');
        } catch (\Exception $e) {
            $this->warn('Cache rebuild had issues: ' . $e->getMessage());
        }
    }

    private function cleanupCache()
    {
        $cachePath = storage_path('framework/cache/data');
        
        if (!File::isDirectory($cachePath)) {
            return;
        }

        $totalSize = 0;
        $files = File::allFiles($cachePath);
        
        foreach ($files as $file) {
            $totalSize += $file->getSize();
        }

        $sizeMB = round($totalSize / 1024 / 1024, 2);
        
        if ($sizeMB > 100) {
            $this->warn("Cache size is {$sizeMB}MB - cleaning up...");
            
            // Delete files older than 7 days
            $deleted = 0;
            $sevenDaysAgo = now()->subDays(7)->timestamp;
            
            foreach ($files as $file) {
                if ($file->getMTime() < $sevenDaysAgo) {
                    File::delete($file->getPathname());
                    $deleted++;
                }
            }
            
            $this->info("✓ Deleted {$deleted} old cache files");
        }
    }
}
