<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::published()->count(),
            'total_views' => Post::sum('view_count') ?? 0,
            'total_categories' => Category::count(),
            'total_authors' => Author::count(),
            'active_authors' => Author::where('is_active', true)->count(),
        ];

        $recent_posts = Post::with('category', 'state')
            ->latest()
            ->limit(10)
            ->get();

        $recent_authors = Author::latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_posts', 'recent_authors'));
    }
}
