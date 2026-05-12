<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\State;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

abstract class Controller
{
    protected function shareGlobalData()
    {
        $categories = Cache::remember('categories_list', 600, 
            fn() => Category::all()
        );
        $states = Cache::remember('states_list', 600, 
            fn() => State::all()
        );
        
        View::share('categories', $categories);
        View::share('states', $states);
    }
}
