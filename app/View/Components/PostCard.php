<?php

namespace App\View\Components;

use App\Models\Post;
use Illuminate\View\Component;

class PostCard extends Component
{
    public function __construct(public Post $post)
    {
    }

    public function render()
    {
        return view('components.post-card');
    }
}
