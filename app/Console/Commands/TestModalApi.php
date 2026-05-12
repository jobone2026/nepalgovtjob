<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\JobScrapeService;

class TestModalApi extends Command
{
    protected $signature = 'modal:test {--url= : Test scraping a specific URL}';
    protected $description = 'Test Modal.com API connection and job scraping';

    public function handle()
    {
        $scrapeService = new JobScrapeService();
        
        $this->info('Testing Modal.com API Connection...');
        $this->info('Token ID: ' . substr(config('services.modal.token_id'), 0, 8) . '...');
        $this->info('Base URL: ' . config('services.modal.base_url'));
        $this->newLine();

        // Test connection
        $this->info('1. Testing API Connection...');
        $result = $scrapeService->testConnection();
        
        if ($result['success']) {
            $this->info('✓ Connection successful!');
            $this->info('Status: ' . $result['status']);
            if (isset($result['data'])) {
                $this->info('Response: ' . json_encode($result['data'], JSON_PRETTY_PRINT));
            }
        } else {
            $this->error('✗ Connection failed!');
            $this->error('Error: ' . ($result['error'] ?? 'Unknown error'));
            return 1;
        }

        $this->newLine();

        // Test scraping if URL provided
        if ($url = $this->option('url')) {
            $this->info('2. Testing Job Scraping...');
            $this->info('URL: ' . $url);
            
            $scrapeResult = $scrapeService->scrapeJob($url);
            
            if ($scrapeResult['success']) {
                $this->info('✓ Scraping successful!');
                $this->info('Data: ' . json_encode($scrapeResult['data'], JSON_PRETTY_PRINT));
            } else {
                $this->error('✗ Scraping failed!');
                $this->error('Error: ' . ($scrapeResult['error'] ?? 'Unknown error'));
                if (isset($scrapeResult['status'])) {
                    $this->error('Status: ' . $scrapeResult['status']);
                }
                return 1;
            }
        }

        $this->newLine();
        $this->info('All tests completed!');
        return 0;
    }
}
