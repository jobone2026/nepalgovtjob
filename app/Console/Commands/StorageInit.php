<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StorageInit extends Command
{
    protected $signature = 'storage:init';
    protected $description = 'Initialize and fix storage directory permissions';

    public function handle()
    {
        $storagePath = storage_path();
        $directories = [
            'framework/views',
            'framework/cache',
            'framework/sessions',
            'logs',
        ];

        foreach ($directories as $dir) {
            $path = $storagePath . '/' . $dir;
            if (!is_dir($path)) {
                mkdir($path, 0775, true);
                $this->info("Created: $path");
            }
        }

        // Fix permissions
        exec("chown -R www-data:www-data {$storagePath}");
        exec("chmod -R 775 {$storagePath}");

        $this->info('Storage directories initialized successfully!');
        return 0;
    }
}
