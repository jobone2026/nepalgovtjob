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
