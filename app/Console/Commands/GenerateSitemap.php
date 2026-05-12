<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate and cache all sitemaps with error handling';

    public function handle()
    {
        $this->info('Generating sitemaps...');

        // Clear existing sitemap cache
        Cache::forget('sitemap:index');
        Cache::forget('sitemap:posts');
        Cache::forget('sitemap:categories');
        Cache::forget('sitemap:states');
        Cache::forget('sitemap:static');
        Cache::forget('sitemap:news');

        // Generate sitemaps by hitting the URLs
        $sitemaps = [
            '/sitemap.xml',
            '/sitemap-posts.xml',
            '/sitemap-categories.xml',
            '/sitemap-states.xml',
            '/sitemap-static.xml',
            '/sitemap-news.xml',
        ];

        foreach ($sitemaps as $sitemap) {
            $this->info("Generating {$sitemap}...");
            try {
                // Using a timeout and suppression to avoid hanging the command
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 30, // 30 seconds timeout
                        'ignore_errors' => true
                    ]
                ]);
                $result = @file_get_contents(url($sitemap), false, $context);
                
                if ($result === false) {
                    $this->error("Failed to generate {$sitemap} (Network/Timeout error)");
                } else {
                    $this->info("Success: {$sitemap}");
                }
            } catch (\Exception $e) {
                $this->error("Error generating {$sitemap}: " . $e->getMessage());
                Log::error("Sitemap generation error: " . $e->getMessage(), ['url' => $sitemap]);
            }
        }

        $this->info('Sitemap generation process finished!');
        return 0;
    }
}
