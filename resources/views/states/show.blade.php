@extends('layouts.app')

@php
    $typeLabels = [
        'job'        => 'Jobs',
        'result'     => 'Results',
        'admit_card' => 'Admit Cards',
        'answer_key' => 'Answer Keys',
        'syllabus'   => 'Syllabus',
        'blog'       => 'Blogs',
        'scholarship'=> 'Scholarships',
    ];
    $typeIcons = [
        'job'        => 'fa-briefcase',
        'result'     => 'fa-chart-bar',
        'admit_card' => 'fa-id-card',
        'answer_key' => 'fa-key',
        'syllabus'   => 'fa-book',
        'blog'       => 'fa-pen-fancy',
        'scholarship'=> 'fa-graduation-cap',
    ];
    $typeHeaderBg = [
        'job'        => ['bg'=>'#eff6ff','border'=>'#bfdbfe','color'=>'#1d4ed8'],
        'result'     => ['bg'=>'#fff7ed','border'=>'#fed7aa','color'=>'#c2410c'],
        'admit_card' => ['bg'=>'#faf5ff','border'=>'#e9d5ff','color'=>'#7e22ce'],
        'answer_key' => ['bg'=>'#fefce8','border'=>'#fde68a','color'=>'#92400e'],
        'syllabus'   => ['bg'=>'#eef2ff','border'=>'#c7d2fe','color'=>'#3730a3'],
        'blog'       => ['bg'=>'#fdf2f8','border'=>'#fbcfe8','color'=>'#9d174d'],
        'scholarship'=> ['bg'=>'#f0fdfa','border'=>'#99f6e4','color'=>'#0f766e'],
    ];

    $filteredType  = $type ?? null;
    $filteredLabel = $filteredType ? ($typeLabels[$filteredType] ?? ucfirst($filteredType)) : null;
    $pageTitle     = $filteredLabel
        ? $state->name . ' ' . $filteredLabel
        : $state->name . ' - Government Jobs';
@endphp

@section('title', $pageTitle . ' | JobOne')
@section('description', 'Latest ' . ($filteredLabel ?? 'government jobs') . ' in ' . $state->name)

@section('content')

    {{-- Page Header --}}
    <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6">
        <div class="flex flex-wrap items-center gap-3 mb-1">
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                <i class="fas fa-map-marker-alt"></i> {{ $state->name }}
            </span>
            @if($filteredLabel)
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full bg-green-50 text-green-700 border border-green-200">
                <i class="fas {{ $typeIcons[$filteredType] ?? 'fa-file' }}"></i> {{ $filteredLabel }}
            </span>
            @endif
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-1">
            {{ $pageTitle }}
        </h1>
        <p class="text-gray-500 text-sm">
            {{ $posts->total() }} post{{ $posts->total() !== 1 ? 's' : '' }} found
            @if($filteredType) · Filtered by: <strong>{{ $filteredLabel }}</strong> @endif
        </p>

        {{-- Filter type pills --}}
        <div class="mt-4 flex flex-wrap gap-2">
            <a href="{{ route('states.show', $state) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all
                      {{ !$filteredType ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-600 border-gray-300 hover:border-gray-500' }}">
                <i class="fas fa-th-large"></i> All
            </a>
            @foreach($typeLabels as $t => $label)
            <a href="{{ route('states.show', $state) }}?type={{ $t }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all
                      {{ $filteredType === $t ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:border-blue-400' }}">
                <i class="fas {{ $typeIcons[$t] }}"></i> {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    @if($filteredType)
        {{-- ══ SINGLE TYPE MODE: flat 4-column grid ══ --}}
        @if($posts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                @foreach($posts as $post)
                    <div class="transform hover:-translate-y-1 transition-all duration-200 h-full">
                        <x-post-card :post="$post" />
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
                <i class="fas fa-inbox text-5xl text-gray-200 mb-4 block"></i>
                <p class="text-gray-500 font-medium">No {{ $filteredLabel }} found in {{ $state->name }}</p>
                <a href="{{ route('states.show', $state) }}" class="mt-4 inline-block text-blue-600 text-sm font-semibold hover:underline">
                    View all posts in {{ $state->name }}
                </a>
            </div>
        @endif

    @else
        {{-- ══ ALL TYPES MODE: columned sections ══ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-8">

            @foreach($typeLabels as $t => $label)
            @php
                $colPosts  = $posts->where('type', $t);
                $colStyle  = $typeHeaderBg[$t] ?? ['bg'=>'#f9fafb','border'=>'#e5e7eb','color'=>'#374151'];
            @endphp
            <div>
                <a href="{{ route('states.show', $state) }}?type={{ $t }}"
                   class="flex items-center justify-between px-4 py-3 font-bold rounded-lg mb-3 transition-all hover:opacity-90"
                   style="background:{{ $colStyle['bg'] }};border:1px solid {{ $colStyle['border'] }};text-decoration:none;">
                    <span style="color:{{ $colStyle['color'] }};">
                        <i class="fa-solid {{ $typeIcons[$t] }}"></i> {{ $label }}
                    </span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full"
                          style="background:{{ $colStyle['color'] }};color:#fff;">
                        {{ $colPosts->count() }}
                    </span>
                </a>
                <div class="space-y-3">
                    @forelse($colPosts->take(10) as $post)
                        <x-post-card :post="$post" />
                    @empty
                        <div class="text-center py-6 text-gray-400 text-sm">
                            <i class="fa-solid fa-inbox text-2xl mb-2 block opacity-40"></i>
                            No {{ $label }} found
                        </div>
                    @endforelse
                    @if($colPosts->count() > 10)
                    <a href="{{ route('states.show', $state) }}?type={{ $t }}"
                       class="block text-center text-xs font-semibold py-2 rounded-lg mt-1 transition-all"
                       style="background:{{ $colStyle['bg'] }};color:{{ $colStyle['color'] }};border:1px solid {{ $colStyle['border'] }};">
                        View all {{ $colPosts->count() }} {{ $label }} <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach

        </div>
    @endif

    {{-- Pagination --}}
    @if($posts->hasPages())
    <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex justify-center">
        {{ $posts->appends(request()->query())->links() }}
    </div>
    @endif

    <!-- SEO Content -->
    @if(!empty($state->seo_content))
    <div class="mt-8 bg-white p-6 rounded-lg border border-gray-200 prose prose-blue max-w-none text-sm text-gray-700">
        {!! $state->seo_content !!}
    </div>
    @endif

@endsection
