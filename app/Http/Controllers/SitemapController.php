<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\State;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $xml = Cache::remember('sitemap:index', 3600, function () {
            $sitemaps = [
                ['loc' => url('/sitemap-posts.xml'), 'lastmod' => Post::max('updated_at') ? \Carbon\Carbon::parse(Post::max('updated_at')) : now()],
                ['loc' => url('/sitemap-categories.xml'), 'lastmod' => Category::max('updated_at') ? \Carbon\Carbon::parse(Category::max('updated_at')) : now()],
                ['loc' => url('/sitemap-states.xml'), 'lastmod' => State::max('updated_at') ? \Carbon\Carbon::parse(State::max('updated_at')) : now()],
                ['loc' => url('/sitemap-static.xml'), 'lastmod' => now()],
                ['loc' => url('/sitemap-news.xml'), 'lastmod' => Post::where('created_at', '>=', now()->subDays(2))->max('created_at') ? \Carbon\Carbon::parse(Post::where('created_at', '>=', now()->subDays(2))->max('created_at')) : now()],
            ];

            return view('sitemap.index', compact('sitemaps'))->render();
        });

        return response($xml)->header('Content-Type', 'application/xml');
    }

    public function posts()
    {
        $xml = Cache::remember('sitemap:posts', 3600, function () {
            $posts = Post::published()
                ->select('slug', 'type', 'updated_at')
                ->get()
                ->map(function ($post) {
                    $post->slug = $this->sanitizeSlug($post->slug);
                    $post->type = $this->sanitizeSlug($post->type);
                    return $post;
                })
                ->filter(fn($post) => !empty($post->slug) && !empty($post->type));
            return view('sitemap.posts', compact('posts'))->render();
        });

        return response($xml)->header('Content-Type', 'application/xml');
    }

    public function categories()
    {
        $xml = Cache::remember('sitemap:categories', 3600, function () {
            $categories = Category::select('slug', 'updated_at')->get();
            return view('sitemap.categories', compact('categories'))->render();
        });

        return response($xml)->header('Content-Type', 'application/xml');
    }

    public function states()
    {
        $xml = Cache::remember('sitemap:states', 3600, function () {
            $states = State::select('slug', 'updated_at')->get();
            return view('sitemap.states', compact('states'))->render();
        });

        return response($xml)->header('Content-Type', 'application/xml');
    }

    public function static()
    {
        $xml = Cache::remember('sitemap:static', 3600, function () {
            $pages = [
                ['url' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
                ['url' => url('/jobs'), 'priority' => '0.9', 'changefreq' => 'daily'],
                ['url' => url('/admit-cards'), 'priority' => '0.9', 'changefreq' => 'daily'],
                ['url' => url('/results'), 'priority' => '0.9', 'changefreq' => 'daily'],
                ['url' => url('/answer-keys'), 'priority' => '0.8', 'changefreq' => 'weekly'],
                ['url' => url('/syllabus'), 'priority' => '0.8', 'changefreq' => 'weekly'],
                ['url' => url('/blogs'), 'priority' => '0.7', 'changefreq' => 'weekly'],
                ['url' => url('/about'), 'priority' => '0.5', 'changefreq' => 'monthly'],
                ['url' => url('/contact'), 'priority' => '0.5', 'changefreq' => 'monthly'],
                ['url' => url('/privacy-policy'), 'priority' => '0.3', 'changefreq' => 'yearly'],
                ['url' => url('/disclaimer'), 'priority' => '0.3', 'changefreq' => 'yearly'],
            ];
            return view('sitemap.static', compact('pages'))->render();
        });

        return response($xml)->header('Content-Type', 'application/xml');
    }

    public function news()
    {
        $xml = Cache::remember('sitemap:news', 3600, function () {
            $posts = Post::published()
                ->where('created_at', '>=', now()->subDays(2))
                ->select('slug', 'type', 'title', 'created_at')
                ->get()
                ->map(function ($post) {
                    $post->slug = $this->sanitizeSlug($post->slug);
                    $post->type = $this->sanitizeSlug($post->type);
                    $post->title = trim($post->title);
                    return $post;
                })
                ->filter(fn($post) => !empty($post->slug) && !empty($post->type));
            return view('sitemap.news', compact('posts'))->render();
        });

        return response($xml)->header('Content-Type', 'application/xml');
    }

    /**
     * Strip newlines, tabs, and leading/trailing whitespace from a URL slug.
     * This prevents malformed <loc> tags in the XML sitemap output.
     */
    private function sanitizeSlug(?string $value): string
    {
        if ($value === null) {
            return '';
        }
        // Remove ALL whitespace characters (newlines, tabs, spaces)
        return trim(preg_replace('/\s+/', '', $value));
    }
}
