<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HealthCheck extends Command
{
    protected $signature = 'health:check';
    protected $description = 'Perform health check and fix common issues without crashing the server';

    public function handle()
    {
        $this->info('🏥 Running Health Check...');

        $storagePath = storage_path();
        $directories = [
            'framework/views',
            'framework/cache',
            'framework/sessions',
            'logs',
            'app',
        ];

        foreach ($directories as $dir) {
            $path = $storagePath . '/' . $dir;
            if (!is_dir($path)) {
                File::makeDirectory($path, 0775, true);
                $this->warn("Created missing directory: $dir");
            }
        }

        // Optimization: Avoid recursive chown/chmod unless absolutely necessary
        // Instead, we check the main storage directory and its immediate children
        $this->safeFixPermissions($storagePath);
        $this->info('✅ Storage permissions checked/fixed');

        // Clear old compiled views (Safely)
        $viewsPath = storage_path('framework/views');
        if (File::isDirectory($viewsPath)) {
            $files = File::files($viewsPath);
            $count = 0;
            $now = time();
            foreach ($files as $file) {
                if ($file->getFilename() === '.gitignore') continue;
                if ($now - File::lastModified($file) > 86400) { // Older than 1 day
                    File::delete($file);
                    $count++;
                }
            }
            if ($count > 0) {
                $this->info("✅ Cleaned $count old compiled views");
            }
        }

        // Check database connection
        try {
            DB::connection()->getPdo();
            $this->info('✅ Database connection OK');
        } catch (\Exception $e) {
            $this->error('❌ Database connection failed: ' . $e->getMessage());
            return 1;
        }

        $this->info('✅ Health check completed successfully!');
        return 0;
    }

    private function safeFixPermissions($path)
    {
        // Only run recursive fix if the root storage isn't owned correctly
        // This prevents massive I/O spikes every day
        $stat = stat($path);
        $ownerInfo = posix_getpwuid($stat['uid']);
        
        if ($ownerInfo['name'] !== 'www-data' || ($stat['mode'] & 0777) !== 0775) {
            $this->warn("Root storage permissions incorrect, applying recursive fix...");
            exec("sudo chown -R www-data:www-data {$path}");
            exec("sudo chmod -R 775 {$path}");
        }
    }
}
