<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\HtmlSanitizerService;
use App\Services\SeoService;
use App\Services\SchemaService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index($type = null)
    {
        // Check if domain is filtered to a specific state
        $stateId = config('app.domain_state_id');
        
        // If type is 'all', get separate collections for each post type for 6-column layout
        if ($type === 'all') {
            $queryBuilder = function($postType) use ($stateId) {
                $q = Post::published()->ofType($postType);
                if ($stateId) {
                    $q->where('state_id', $stateId);
                }
                return $q->with('category', 'state')->latest()->limit(10)->get();
            };
            
            $jobs = $queryBuilder('job');
            $results = $queryBuilder('result');
            $admitCards = $queryBuilder('admit_card');
            $answerKeys = $queryBuilder('answer_key');
            $syllabus = $queryBuilder('syllabus');
            $blogs = $queryBuilder('blog');
            
            // Create a merged collection for pagination info
            $allPosts = collect()
                ->merge($jobs)
                ->merge($results)
                ->merge($admitCards)
                ->merge($answerKeys)
                ->merge($syllabus)
                ->merge($blogs)
                ->sortByDesc('created_at');
            
            // Create fake pagination object for consistency
            $posts = new \Illuminate\Pagination\LengthAwarePaginator(
                $allPosts,
                $allPosts->count(),
                50,
                1,
                ['path' => request()->url()]
            );
            
            // Pass individual collections to view
            $sections = [
                'jobs' => $jobs,
                'results' => $results,
                'admit_cards' => $admitCards,
                'answer_keys' => $answerKeys,
                'syllabus' => $syllabus,
                'blogs' => $blogs
            ];
        } else {
            // For specific types, use normal pagination
            $query = Post::published()->with('category', 'state');
            
            if ($type) {
                $query->ofType($type);
            }
            
            if ($stateId) {
                $query->where('state_id', $stateId);
            }
            
            $posts = $query->latest()->paginate(50);
            $sections = null;
        }

        // SEO
        $seoService = app(SeoService::class);
        $seo = $seoService->generateListingSeo($type ?? 'all');

        return view('posts.index', compact('posts', 'type', 'seo', 'sections'));
    }

    public function show($type, Post $post)
    {
        if (!$post->is_published && !auth('admin')->check()) {
            abort(404);
        }

        // Don't increment view count here - let JavaScript handle it to work with page cache
        // View count will be incremented via AJAX call from the frontend

        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->with('category', 'state')
            ->orderByRaw("
                (category_id = ? AND state_id = ?) DESC,
                (category_id = ?) DESC,
                (education_level = ? AND education_level IS NOT NULL) DESC,
                (state_id = ?) DESC,
                created_at DESC
            ", [
                $post->category_id, $post->state_id,
                $post->category_id,
                $post->education_level,
                $post->state_id
            ])
            ->limit(4)
            ->get();

        // SEO
        $seoService = app(SeoService::class);
        $schemaService = app(SchemaService::class);
        
        $seo = $seoService->generatePostSeo($post);
        $schema = [];

        if ($post->type === 'job') {
            $schema[] = $schemaService->generateJobPostingSchema($post);
        } else {
            $schema[] = $schemaService->generateArticleSchema($post);
        }

        $schema[] = $schemaService->generateBreadcrumbSchema($post);

        // FAQ schema: prefer $post->faq JSON, fallback to HTML extraction
        $faqSchema = $schemaService->generateFAQSchema($post->content ?? '', $post->faq ?? null);
        if ($faqSchema) $schema[] = $faqSchema;

        // Sanitize content server-side to fix inline width/overflow on mobile
        $sanitizer = app(HtmlSanitizerService::class);
        $post->content = $sanitizer->cleanContent($post->content ?? '');

        return view('posts.show', compact('post', 'related', 'seo', 'schema'));
    }

    public function loadMore(Request $request, $type)
    {
        $page = $request->input('page', 2);
        $stateId = config('app.domain_state_id');
        
        $query = Post::published()->ofType($type)->with('category', 'state');
        
        if ($stateId) {
            $query->where('state_id', $stateId);
        }
        
        $posts = $query->latest()->simplePaginate(50, ['*'], 'page', $page);

        return view('posts.load-more', compact('posts'));
    }
}
