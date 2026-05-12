<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\State;
use App\Services\SeoService;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $stateId = config('app.domain_state_id');
        
        $postsQuery = $category->posts()->published()->with('state');
        
        if ($stateId) {
            $postsQuery->where('state_id', $stateId);
        }
        
        $posts = $postsQuery->latest()->paginate(50); // 50 posts per page

        $states = State::all();
        $categories = Category::all();

        // SEO
        $seoService = app(SeoService::class);
        $seo = $seoService->generateCategorySeo($category);

        return view('categories.show', compact('posts', 'category', 'states', 'categories', 'seo'));
    }
}
