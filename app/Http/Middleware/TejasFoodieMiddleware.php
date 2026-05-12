<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TejasFoodieMiddleware
{
    /**
     * Handle an incoming request for tejasfoodie.store domain
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        
        // Check if the request is for tejasfoodie.store
        if (str_contains($host, 'tejasfoodie.store')) {
            return response()->file(public_path('tejasfoodie.html'));
        }
        
        return $next($request);
    }
}
