<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\State;
use App\Services\SeoService;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    /**
     * Handle combined filtering: category + state + type
     */
    public function filter(Request $request, $type = null, $param1 = null, $param2 = null)
    {
        $category = null;
        $state = null;
        $postType = $type && $type !== 'all' ? $type : null;
        
        // Parse parameters based on URL structure
        // /filter/{type}/{category}/{state}
        // /filter/category/{category}/state/{state}
        // /filter/{type}/category/{category}
        // /filter/{type}/state/{state}
        
        if ($type === 'category' && $param1 && $param2 === 'state') {
            // /filter/category/{category}/state/{state}
            $category = Category::where('slug', $param1)->firstOrFail();
            $state = State::where('slug', request()->segment(5))->firstOrFail();
            $postType = null;
        } elseif ($param1 === 'category' && $param2) {
            // /filter/{type}/category/{category}
            $category = Category::where('slug', $param2)->firstOrFail();
            $postType = $type;
        } elseif ($param1 === 'state' && $param2) {
            // /filter/{type}/state/{state}
            $state = State::where('slug', $param2)->firstOrFail();
            $postType = $type;
        } elseif ($param1 && $param2) {
            // /filter/{type}/{category}/{state}
            $category = Category::where('slug', $param1)->firstOrFail();
            $state = State::where('slug', $param2)->firstOrFail();
            $postType = $type;
        }
        
        // Build query
        $query = Post::published()->with('category', 'state');
        
        if ($category) {
            $query->where('category_id', $category->id);
        }
        
        if ($state) {
            $query->where('state_id', $state->id);
        }
        
        if ($postType && $postType !== 'all') {
            $query->where('type', $postType);
        }
        
        // Apply search if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        $posts = $query->latest()->paginate(50);
        
        // Generate title
        $titleParts = [];
        if ($category) $titleParts[] = $category->name;
        if ($state) $titleParts[] = $state->name;
        if ($postType && $postType !== 'all') {
            $titleParts[] = ucfirst(str_replace('_', ' ', $postType));
        }
        $title = !empty($titleParts) ? implode(' - ', $titleParts) : 'All Posts';
        
        // SEO
        $seoService = app(SeoService::class);
        $seo = [
            'title' => $title . ' - Government Job Portal',
            'description' => 'Browse ' . strtolower($title) . ' on Government Job Portal',
            'keywords' => implode(', ', array_filter([$category?->name, $state?->name, $postType])),
            'canonical' => url()->current(),
        ];
        
        return view('posts.index', compact(
            'posts',
            'category',
            'state',
            'postType',
            'title',
            'seo'
        ))->with('type', $postType ?? 'all');
    }
}
