@extends('layouts.app')

@section('title', $category->name . ' - Government Jobs')
@section('description', 'Latest government jobs in ' . $category->name)

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-700 bg-clip-text text-transparent mb-2">
            <i class="fas fa-folder"></i> {{ $category->name }}
        </h1>
        <p class="text-gray-600 text-sm"><i class="fas fa-briefcase"></i> All posts in {{ $category->name }} category ({{ $posts->total() }} total)</p>
    </div>

    @php
        $jobPosts = $posts->where('type', 'job');
        $resultPosts = $posts->where('type', 'result');
        $admitCardPosts = $posts->where('type', 'admit_card');
        $answerKeyPosts = $posts->where('type', 'answer_key');
        $syllabusPosts = $posts->where('type', 'syllabus');
        $blogPosts = $posts->where('type', 'blog');
        $scholarshipPosts = $posts->where('type', 'scholarship');
    @endphp

    <!-- Column Sections for Each Type -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-8">
        <!-- Jobs Column -->
        <div>
            <div class="px-4 py-3 font-bold flex items-center rounded-lg mb-3" style="background:#eff6ff;border:1px solid #bfdbfe;">
                <span style="color:#1d4ed8;"><i class="fa-solid fa-briefcase"></i> Jobs in {{ $category->name }}</span>
            </div>
            <div class="space-y-4">
                @forelse ($jobPosts->take(25) as $post)
                    <x-post-card :post="$post" />
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-inbox text-4xl mb-2 opacity-50"></i>
                        <p>No jobs found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Results Column -->
        <div>
            <div class="px-4 py-3 font-bold flex items-center rounded-lg mb-3" style="background:#fff7ed;border:1px solid #fed7aa;">
                <span style="color:#c2410c;"><i class="fa-solid fa-chart-bar"></i> Results in {{ $category->name }}</span>
            </div>
            <div class="space-y-4">
                @forelse ($resultPosts->take(25) as $post)
                    <x-post-card :post="$post" />
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-inbox text-4xl mb-2 opacity-50"></i>
                        <p>No results found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Admit Cards Column -->
        <div>
            <div class="px-4 py-3 font-bold flex items-center rounded-lg mb-3" style="background:#faf5ff;border:1px solid #e9d5ff;">
                <span style="color:#7e22ce;"><i class="fa-solid fa-id-card"></i> Admit Cards in {{ $category->name }}</span>
            </div>
            <div class="space-y-4">
                @forelse ($admitCardPosts->take(25) as $post)
                    <x-post-card :post="$post" />
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-inbox text-4xl mb-2 opacity-50"></i>
                        <p>No admit cards found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Answer Keys Column -->
        <div>
            <div class="px-4 py-3 font-bold flex items-center rounded-lg mb-3" style="background:#fefce8;border:1px solid #fde68a;">
                <span style="color:#92400e;"><i class="fa-solid fa-key"></i> Answer Keys in {{ $category->name }}</span>
            </div>
            <div class="space-y-4">
                @forelse ($answerKeyPosts->take(25) as $post)
                    <x-post-card :post="$post" />
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-inbox text-4xl mb-2 opacity-50"></i>
                        <p>No answer keys found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Syllabus Column -->
        <div>
            <div class="px-4 py-3 font-bold flex items-center rounded-lg mb-3" style="background:#eef2ff;border:1px solid #c7d2fe;">
                <span style="color:#3730a3;"><i class="fa-solid fa-book"></i> Syllabus in {{ $category->name }}</span>
            </div>
            <div class="space-y-4">
                @forelse ($syllabusPosts->take(25) as $post)
                    <x-post-card :post="$post" />
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-inbox text-4xl mb-2 opacity-50"></i>
                        <p>No syllabus found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Blogs Column -->
        <div>
            <div class="px-4 py-3 font-bold flex items-center rounded-lg mb-3" style="background:#fdf2f8;border:1px solid #fbcfe8;">
                <span style="color:#9d174d;"><i class="fa-solid fa-pen-fancy"></i> Blogs in {{ $category->name }}</span>
            </div>
            <div class="space-y-4">
                @forelse ($blogPosts->take(25) as $post)
                    <x-post-card :post="$post" />
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-inbox text-4xl mb-2 opacity-50"></i>
                        <p>No blogs found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Scholarships Column -->
        <div>
            <div class="px-4 py-3 font-bold flex items-center rounded-lg mb-3" style="background:#f0fdfa;border:1px solid #99f6e4;">
                <span style="color:#0f766e;"><i class="fa-solid fa-graduation-cap"></i> Scholarships in {{ $category->name }}</span>
            </div>
            <div class="space-y-4">
                @forelse ($scholarshipPosts->take(25) as $post)
                    <x-post-card :post="$post" />
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-inbox text-4xl mb-2 opacity-50"></i>
                        <p>No scholarships found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="mt-8">
        {{ $posts->links() }}
    </div>
    @endif
    <!-- SEO Content -->
    @if(!empty($category->seo_content))
    <div class="mt-8 bg-white p-6 rounded-lg border border-gray-200 prose prose-blue max-w-none text-sm text-gray-700">
        {!! $category->seo_content !!}
    </div>
    @endif
@endsection
