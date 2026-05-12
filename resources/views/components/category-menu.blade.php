@php
    $categories = \App\Models\Category::withCount('posts')
        ->orderBy('posts_count', 'desc')
        ->take(15)
        ->get();

    /**
     * Returns [icon, bg, border, text] for each category based on name keywords.
     * Icons are FontAwesome 6 Free solid classes.
     */
    function getCategoryStyle($categoryName) {
        $name = strtolower($categoryName);

        // State Government
        if (str_contains($name, 'state govt') || (str_contains($name, 'state') && str_contains($name, 'govt')))
            return ['fa-landmark',          '#dbeafe', '#93c5fd', '#1d4ed8']; // blue

        // State PSC
        if (str_contains($name, 'psc') || str_contains($name, 'public service'))
            return ['fa-scale-balanced',    '#fef3c7', '#fcd34d', '#92400e']; // amber

        // Defence / Army / Navy / Air Force
        if (str_contains($name, 'defence') || str_contains($name, 'army') || str_contains($name, 'navy') || str_contains($name, 'air force') || str_contains($name, 'military'))
            return ['fa-shield-halved',     '#e2e8f0', '#94a3b8', '#334155']; // slate

        // Banking / Bank
        if (str_contains($name, 'bank') || str_contains($name, 'ibps') || str_contains($name, 'rbi') || str_contains($name, 'sbi'))
            return ['fa-building-columns',  '#dcfce7', '#86efac', '#15803d']; // green

        // Railways / RRB
        if (str_contains($name, 'railway') || str_contains($name, 'rrb') || str_contains($name, 'rail'))
            return ['fa-train',             '#ccfbf1', '#5eead4', '#0f766e']; // teal

        // UPSC
        if (str_contains($name, 'upsc') || str_contains($name, 'ias') || str_contains($name, 'ips'))
            return ['fa-university',        '#e0e7ff', '#a5b4fc', '#4338ca']; // indigo

        // SSC
        if (str_contains($name, 'ssc'))
            return ['fa-user-tie',          '#f0fdfa', '#6ee7b7', '#065f46']; // emerald

        // Police / Constable
        if (str_contains($name, 'police') || str_contains($name, 'constable') || str_contains($name, 'cop'))
            return ['fa-shield-alt',        '#fee2e2', '#fca5a5', '#b91c1c']; // red

        // All India
        if (str_contains($name, 'all india') || str_contains($name, 'central'))
            return ['fa-flag',              '#ede9fe', '#c4b5fd', '#6d28d9']; // violet

        // SSB / Selection Board
        if (str_contains($name, 'ssb') || str_contains($name, 'selection board') || str_contains($name, 'interview'))
            return ['fa-people-group',      '#f0f4ff', '#a5b4fc', '#3730a3']; // indigo-dark

        // Teaching / Education
        if (str_contains($name, 'teach') || str_contains($name, 'teacher') || str_contains($name, 'education') || str_contains($name, 'ctet') || str_contains($name, 'tet'))
            return ['fa-chalkboard-teacher','#faf5ff', '#d8b4fe', '#7e22ce']; // purple

        // Engineering / Technical
        if (str_contains($name, 'engineer') || str_contains($name, 'technical') || str_contains($name, 'iti'))
            return ['fa-screwdriver-wrench','#fff7ed', '#fdba74', '#c2410c']; // orange

        // Medical / Health
        if (str_contains($name, 'medical') || str_contains($name, 'nurse') || str_contains($name, 'doctor') || str_contains($name, 'health') || str_contains($name, 'mbbs'))
            return ['fa-stethoscope',       '#fdf2f8', '#f9a8d4', '#9d174d']; // pink

        // Scholarship
        if (str_contains($name, 'scholarship') || str_contains($name, 'fellowship'))
            return ['fa-graduation-cap',    '#f0fdfa', '#99f6e4', '#0d9488']; // teal

        // Blog
        if (str_contains($name, 'blog') || str_contains($name, 'article') || str_contains($name, 'news'))
            return ['fa-pen-fancy',         '#fdf4ff', '#f5d0fe', '#a21caf']; // fuchsia

        // Judiciary / Law
        if (str_contains($name, 'court') || str_contains($name, 'judge') || str_contains($name, 'law') || str_contains($name, 'judicial'))
            return ['fa-gavel',             '#fefce8', '#fde68a', '#854d0e']; // yellow

        // Default
        return ['fa-briefcase', '#f3f4f6', '#d1d5db', '#4b5563'];
    }
@endphp

@if ($categories->count() > 0)
<div class="bg-white border-b border-gray-200 shadow-sm" style="position:sticky;top:72px;z-index:40;">
    <div class="w-full">
        <div class="overflow-x-auto" style="-ms-overflow-style:none;scrollbar-width:none;">
            <div style="display:flex;gap:8px;padding:10px 12px;min-width:max-content;">

                @foreach ($categories as $category)
                    @php
                        [$icon, $bg, $border, $color] = getCategoryStyle($category->name);

                        $isActive = false;
                        if (request()->route() && request()->route()->getName() === 'categories.show') {
                            $routeCategory = request()->route('category');
                            $isActive = $routeCategory && is_object($routeCategory) && $routeCategory->id === $category->id;
                        }

                        $itemBg     = $isActive ? $color : $bg;
                        $itemBorder = $isActive ? $color : $border;
                        $iconColor  = $isActive ? '#ffffff' : $color;
                        $textColor  = $isActive ? '#ffffff' : '#1f2937';
                        $countColor = $isActive ? '#ffffff' : $color;
                    @endphp

                    <a href="{{ route('categories.show', $category) }}"
                       style="display:flex;flex-direction:column;align-items:center;justify-content:center;
                              min-width:60px;padding:6px 6px;border-radius:8px;
                              background:{{ $itemBg }};border:1px solid {{ $itemBorder }};
                              text-decoration:none;transition:all 0.2s;flex-shrink:0;
                              box-shadow:{{ $isActive ? '0 2px 8px rgba(0,0,0,0.1)' : 'none' }};
                              transform:{{ $isActive ? 'scale(1.02)' : 'scale(1)' }};"
                       onmouseover="this.style.transform='scale(1.05)';this.style.boxShadow='0 2px 6px rgba(0,0,0,0.1)'"
                       onmouseout="this.style.transform='{{ $isActive ? 'scale(1.02)' : 'scale(1)' }}';this.style.boxShadow='{{ $isActive ? '0 2px 8px rgba(0,0,0,0.1)' : 'none' }}'">

                        <i class="fas {{ $icon }}"
                           style="font-size:16px;color:{{ $iconColor }};margin-bottom:3px;"></i>

                        <span style="font-size:10px;font-weight:700;color:{{ $textColor }};
                                     text-align:center;line-height:1.1;max-width:60px;
                                     white-space:normal;word-break:break-word;">
                            {{ Str::limit($category->name, 14) }}
                        </span>

                        <span style="font-size:9px;font-weight:800;color:{{ $countColor }};margin-top:1px;opacity:0.8;">
                            {{ number_format($category->posts_count) }}
                        </span>
                    </a>
                @endforeach

            </div>
        </div>
    </div>
</div>

<style>
    .overflow-x-auto::-webkit-scrollbar { display: none; }
</style>
@endif
