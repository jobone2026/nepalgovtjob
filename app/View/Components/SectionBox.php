<?php

namespace App\View\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class SectionBox extends Component
{
    public function __construct(
        public string $title,
        public string $type,
        public string $color,
        public Collection $posts
    ) {
    }

    public function render()
    {
        return view('components.section-box');
    }
}
