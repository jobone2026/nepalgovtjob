<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\State;
use App\Services\SeoService;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function show(State $state, Request $request)
    {
        $type = $request->get('type'); // e.g. job, result, admit_card …

        $query = $state->posts()
            ->published()
            ->with(['category', 'state'])
            ->latest();

        // Filter by type if provided
        if ($type && $type !== 'all') {
            $query->where('type', $type);
        }

        $posts = $query->paginate(50);

        $states     = State::all();
        $categories = Category::all();

        // SEO
        $seoService = app(SeoService::class);
        $seo = $seoService->generateStateSeo($state);

        return view('states.show', compact('posts', 'state', 'states', 'categories', 'seo', 'type'));
    }
}
