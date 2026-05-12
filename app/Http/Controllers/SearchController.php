<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $stateId = config('app.domain_state_id');

        $postsQuery = Post::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%")
                  ->orWhereHas('category', function($categoryQuery) use ($query) {
                      $categoryQuery->where('name', 'like', "%{$query}%");
                  });
            })
            ->with('category', 'state');
        
        if ($stateId) {
            $postsQuery->where('state_id', $stateId);
        }
        
        $posts = $postsQuery->latest()->paginate(50);

        // Add noindex for empty results or deep pagination
        $noindex = $posts->isEmpty() || $posts->currentPage() > 3;

        return view('posts.search', compact('posts', 'query', 'noindex'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('q', '');
        $stateId = config('app.domain_state_id');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $postsQuery = Post::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%")
                  ->orWhereHas('category', function($categoryQuery) use ($query) {
                      $categoryQuery->where('name', 'like', "%{$query}%");
                  });
            })
            ->with('category')
            ->select('id', 'title', 'slug', 'type', 'category_id');
        
        if ($stateId) {
            $postsQuery->where('state_id', $stateId);
        }
        
        $posts = $postsQuery->limit(10)->get();

        return response()->json($posts);
    }

}
