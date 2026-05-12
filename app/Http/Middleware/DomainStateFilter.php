<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\State;

class DomainStateFilter
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        
        // Get domain-to-state mapping from environment
        $domainStateMap = $this->getDomainStateMap();
        
        // Check if current domain should be filtered to a specific state
        if (isset($domainStateMap[$host])) {
            $stateSlug = $domainStateMap[$host];
            $state = State::where('slug', $stateSlug)->first();
            
            if ($state) {
                // Store state filter in request for controllers to use
                $request->attributes->set('domain_state_filter', $state);
                config(['app.domain_state_id' => $state->id]);
                config(['app.domain_state_slug' => $state->slug]);
                config(['app.domain_state_name' => $state->name]);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Parse domain-to-state mapping from environment
     */
    private function getDomainStateMap(): array
    {
        $map = [];
        $envMap = config('app.domain_state_map', '');
        
        if (empty($envMap)) {
            return $map;
        }
        
        // Parse format: domain1:state_slug1,domain2:state_slug2
        $pairs = explode(',', $envMap);
        foreach ($pairs as $pair) {
            $parts = explode(':', trim($pair));
            if (count($parts) === 2) {
                $map[trim($parts[0])] = trim($parts[1]);
            }
        }
        
        return $map;
    }
}
