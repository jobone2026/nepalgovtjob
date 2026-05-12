<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class IndexNowService
{
    private string $apiKey;
    private string $host;
    private string $keyLocation;

    public function __construct()
    {
        $this->apiKey = config('services.indexnow.key', $this->generateApiKey());
        $this->host = parse_url(config('app.url'), PHP_URL_HOST);
        $this->keyLocation = config('app.url') . '/' . $this->apiKey . '.txt';
        
        // Ensure API key file exists
        $this->ensureApiKeyFile();
    }

    /**
     * Submit URL to IndexNow API
     */
    public function submitUrl(string $url): bool
    {
        try {
            $response = Http::timeout(10)->post('https://api.indexnow.org/indexnow', [
                'host' => $this->host,
                'key' => $this->apiKey,
                'keyLocation' => $this->keyLocation,
                'urlList' => [$url],
            ]);

            if ($response->successful() || $response->status() === 202) {
                Log::info('IndexNow: URL submitted successfully', ['url' => $url]);
                return true;
            }

            Log::warning('IndexNow: Failed to submit URL', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('IndexNow: Exception occurred', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Submit multiple URLs to IndexNow API
     */
    public function submitUrls(array $urls): bool
    {
        if (empty($urls)) {
            return false;
        }

        try {
            $response = Http::timeout(10)->post('https://api.indexnow.org/indexnow', [
                'host' => $this->host,
                'key' => $this->apiKey,
                'keyLocation' => $this->keyLocation,
                'urlList' => $urls,
            ]);

            if ($response->successful() || $response->status() === 202) {
                Log::info('IndexNow: URLs submitted successfully', ['count' => count($urls)]);
                return true;
            }

            Log::warning('IndexNow: Failed to submit URLs', [
                'count' => count($urls),
                'status' => $response->status(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('IndexNow: Exception occurred', [
                'count' => count($urls),
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Generate API key if not exists
     */
    private function generateApiKey(): string
    {
        $key = Str::random(32);
        
        // Save to config file
        $configPath = config_path('services.php');
        if (file_exists($configPath)) {
            $config = file_get_contents($configPath);
            if (strpos($config, 'indexnow') === false) {
                $indexnowConfig = "\n\n    'indexnow' => [\n        'key' => '{$key}',\n    ],";
                $config = str_replace('];', $indexnowConfig . "\n];", $config);
                file_put_contents($configPath, $config);
            }
        }

        return $key;
    }

    /**
     * Ensure API key file exists in public directory
     * Note: IndexNow API requires this file to be publicly accessible
     * for domain ownership verification. This is by design, not a security issue.
     */
    private function ensureApiKeyFile(): void
    {
        $filePath = public_path($this->apiKey . '.txt');
        
        if (!file_exists($filePath)) {
            file_put_contents($filePath, $this->apiKey);
            Log::info('IndexNow: API key file created for domain verification');
        }
    }

    /**
     * Get API key
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
