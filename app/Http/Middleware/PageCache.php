<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PageCache
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only cache GET requests for guests
        if (!$request->isMethod('GET') || auth()->check() || auth('admin')->check()) {
            return $next($request);
        }

        // Never serve cached pages to search engine crawlers — they must see fresh content
        $ua = strtolower($request->userAgent() ?? '');
        $isCrawler = preg_match('/(googlebot|bingbot|slurp|duckduckbot|yandexbot|baiduspider|facebot|ia_archiver|semrushbot|ahrefsbot)/i', $ua);
        if ($isCrawler) {
            $response = $next($request);
            // Add Last-Modified header for crawler freshness signals
            $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', time()) . ' GMT');
            $response->headers->set('X-Robots-Tag', 'index, follow');
            return $response;
        }

        $cacheKey = 'page_cache:' . md5($request->fullUrl());
        $ttl = $this->getCacheTTL($request);

        // Return cached response if exists
        if (Cache::has($cacheKey)) {
            return response(Cache::get($cacheKey));
        }

        $response = $next($request);

        // Cache successful HTML responses
        if ($response->isSuccessful() && $response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {
            Cache::put($cacheKey, $response->getContent(), $ttl);
        }

        return $response;
    }

    private function getCacheTTL(Request $request): int
    {
        $path = $request->path();

        // Homepage: 10 minutes
        if ($path === '/') {
            return 600;
        }

        // Post detail: 60 minutes
        if (preg_match('/^\w+\/[\w-]+$/', $path)) {
            return 3600;
        }

        // Listings: 30 minutes
        return 1800;
    }
}
