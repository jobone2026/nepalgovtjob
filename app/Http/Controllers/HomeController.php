<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\State;
use App\Services\SeoService;
use App\Services\SchemaService;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Check if domain is filtered to a specific state
        $stateId = config('app.domain_state_id');
        $cacheKey = $stateId ? "home_sections_state_{$stateId}" : 'home_sections';
        
        // Cache home page sections for 10 minutes
        $sections = Cache::remember($cacheKey, 600, function () use ($stateId) {
            $query = function($type) use ($stateId) {
                $q = Post::published()->ofType($type);
                if ($stateId) {
                    $q->where('state_id', $stateId);
                }
                return $q->with('category', 'state')->latest()->limit(50)->get();
            };
            
            return [
                'jobs'         => $query('job'),
                'admit_cards'  => $query('admit_card'),
                'results'      => $query('result'),
                'answer_keys'  => $query('answer_key'),
                'syllabus'     => $query('syllabus'),
                'blogs'        => $query('blog'),
                'scholarships' => $query('scholarship'),
            ];
        });

        $categories = Cache::remember('categories_list', 600, 
            fn() => Category::all()
        );
        
        // Get trending jobs (most viewed in last 7 days)
        $trendingJobs = Cache::remember('trending_jobs', 600, function () {
            return Post::published()
                ->ofType('job')
                ->where('created_at', '>=', now()->subDays(7))
                ->latest()
                ->with('category', 'state')
                ->limit(10)
                ->get();
        });
        
        // Get closing soon jobs (deadline within 7 days)
        $closingSoon = Cache::remember('closing_soon_jobs', 600, function () {
            return Post::published()
                ->ofType('job')
                ->whereNotNull('last_date')
                ->whereBetween('last_date', [now(), now()->addDays(7)])
                ->orderBy('last_date', 'asc')
                ->with('category', 'state')
                ->limit(10)
                ->get();
        });
        
        // Get states with job counts for the map
        $states = Cache::remember('states_with_counts', 600, function () {
            $statesWithCounts = State::withCount(['posts' => function($query) {
                $query->published()->where('type', 'job');
            }])->get();
            
            // Add "All India" as a pseudo-state for jobs without state
            $allIndiaCount = Post::published()
                ->where('type', 'job')
                ->whereNull('state_id')
                ->count();
            
            $allIndia = new State();
            $allIndia->id = null;
            $allIndia->name = 'All India';
            $allIndia->slug = 'all-india';
            $allIndia->posts_count = $allIndiaCount;
            
            return collect([$allIndia])->merge($statesWithCounts);
        });

        // SEO
        $seoService = app(SeoService::class);
        $schemaService = app(SchemaService::class);
        
        $seo = $seoService->generateHomeSeo();
        $schema = [
            $schemaService->generateWebSiteSchema(),
            $schemaService->generateOrganizationSchema(),
        ];

        return view('home', compact('sections', 'categories', 'states', 'seo', 'schema', 'trendingJobs', 'closingSoon'));
    }
}
