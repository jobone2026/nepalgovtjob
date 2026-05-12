<?php

namespace App\View\Components;

use App\Models\Ad;
use Illuminate\View\Component;

class AdSlot extends Component
{
    public function __construct(public string $position)
    {
    }

    public function render()
    {
        $ad = Ad::where('position', $this->position)
            ->where('is_active', true)
            ->first();

        return view('components.ad-slot', ['ad' => $ad]);
    }
}
