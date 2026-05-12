<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class GoogleIndexingService
{
    protected $client_email;
    protected $private_key;
    protected $token_url = 'https://oauth2.googleapis.com/token';
    protected $index_url = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

    public function __construct()
    {
        $this->loadCredentials();
    }

    protected function loadCredentials()
    {
        // We'll look for a file named google-indexing-key.json in the storage/app folder
        $path = storage_path('app/google-indexing-key.json');
        
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
            $this->client_email = $data['client_email'] ?? null;
            $this->private_key = $data['private_key'] ?? null;
        }
    }

    /**
     * Notify Google about a new or updated URL
     */
    public function notifyUpdate(string $url)
    {
        return $this->publish($url, 'URL_UPDATED');
    }

    /**
     * Notify Google that a URL has been deleted
     */
    public function notifyDelete(string $url)
    {
        return $this->publish($url, 'URL_DELETED');
    }

    protected function publish(string $url, string $type)
    {
        if (!$this->client_email || !$this->private_key) {
            Log::warning('Google Indexing API: Credentials not found at storage/app/google-indexing-key.json');
            return false;
        }

        try {
            $token = $this->getAccessToken();
            
            $payload = json_encode([
                'url' => $url,
                'type' => $type
            ]);

            $ch = curl_init($this->index_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ]);

            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($status >= 200 && $status < 300) {
                Log::info("Google Indexing API Success ($type): $url");
                return true;
            }

            Log::error("Google Indexing API Error ($status): " . $response);
            return false;

        } catch (Exception $e) {
            Log::error("Google Indexing API Exception: " . $e->getMessage());
            return false;
        }
    }

    protected function getAccessToken()
    {
        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        
        $now = time();
        $payload = json_encode([
            'iss' => $this->client_email,
            'scope' => 'https://www.googleapis.com/auth/indexing',
            'aud' => $this->token_url,
            'iat' => $now,
            'exp' => $now + 3600
        ]);

        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode($payload);

        $signature = '';
        openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $this->private_key, OPENSSL_ALGO_SHA256);
        $base64UrlSignature = $this->base64UrlEncode($signature);

        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        $ch = curl_init($this->token_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $data = json_decode($response, true);
        curl_close($ch);

        return $data['access_token'] ?? null;
    }

    protected function base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
