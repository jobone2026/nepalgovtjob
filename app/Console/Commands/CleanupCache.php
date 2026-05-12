<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CleanupCache extends Command
{
    protected $signature = 'cache:cleanup {--max-size=100 : Max cache size in MB}';
    protected $description = 'Clean up old cache files to prevent memory issues';

    public function handle()
    {
        $maxSizeMB = $this->option('max-size');
        $maxSizeBytes = $maxSizeMB * 1024 * 1024;

        $this->info("Starting cache cleanup (max size: {$maxSizeMB}MB)...");

        // Clean file cache
        $this->cleanFileCache($maxSizeBytes);

        // Clean view cache (only files older than 3 days)
        $this->cleanViewCache(3);

        $this->info('✓ Cache cleanup completed successfully!');
    }

    private function cleanFileCache($maxSizeBytes)
    {
        $cacheDir = storage_path('framework/cache/data');

        if (!is_dir($cacheDir)) {
            $this->warn('Cache directory not found');
            return;
        }

        $totalSize = 0;
        $files = [];

        foreach (File::allFiles($cacheDir) as $file) {
            $size = $file->getSize();
            $totalSize += $size;
            $files[] = [
                'path' => $file->getRealPath(),
                'size' => $size,
                'time' => $file->getMTime()
            ];
        }

        $this->line("Current cache size: " . $this->formatBytes($totalSize));

        if ($totalSize > $maxSizeBytes) {
            $this->warn("Cache size exceeds limit! Cleaning up...");
            usort($files, function ($a, $b) {
                return $a['time'] - $b['time'];
            });

            $deletedSize = 0;
            $deletedCount = 0;

            foreach ($files as $file) {
                if ($totalSize - $deletedSize <= $maxSizeBytes * 0.8) {
                    break;
                }
                if (@unlink($file['path'])) {
                    $deletedSize += $file['size'];
                    $deletedCount++;
                }
            }
            $this->line("Deleted {$deletedCount} old cache files (" . $this->formatBytes($deletedSize) . ")");
        }
    }

    private function cleanViewCache($daysOld)
    {
        $viewCacheDir = storage_path('framework/views');
        if (is_dir($viewCacheDir)) {
            $files = File::allFiles($viewCacheDir);
            $count = 0;
            $now = time();
            foreach ($files as $file) {
                if ($file->getFilename() === '.gitignore') continue;
                if ($now - $file->getMTime() > ($daysOld * 24 * 60 * 60)) {
                    if (@unlink($file->getRealPath())) {
                        $count++;
                    }
                }
            }
            if ($count > 0) {
                $this->line("Cleaned {$count} old view cache files");
            }
        }
    }

    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
