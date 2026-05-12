@extends('layouts.app')

@section('title', '404 - Page Not Found | JobOne.in')

@section('content')
<div class="min-h-screen bg-gray-50 px-4 py-8">
    <div class="max-w-6xl mx-auto">
        
        <!-- 404 Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full mb-6">
                <i class="fas fa-exclamation-triangle text-5xl text-blue-600"></i>
            </div>
            <h1 class="text-7xl font-bold text-gray-800 mb-3">404</h1>
            <h2 class="text-3xl font-semibold text-gray-700 mb-3">Page Not Found</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto mb-6">
                The page you're looking for might have been removed, had its name changed, or is temporarily unavailable.
            </p>
            
            <!-- Search Box -->
            <form action="{{ route('search') }}" method="GET" class="max-w-2xl mx-auto mb-8">
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ implode(' ', array_filter(explode('/', request()->path()), fn($word) => strlen($word) > 2 && !is_numeric($word))) }}"
                        placeholder="🔍 Search for jobs, admit cards, results, answer keys..." 
                        class="flex-1 px-6 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-600 text-lg"
                        autofocus>
                    <button type="submit" class="px-8 py-4 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold text-lg shadow-lg hover:shadow-xl transition">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Navigation Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
            <a href="{{ route('posts.jobs') }}" class="group p-6 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-600 hover:shadow-lg transition text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-600 transition">
                    <i class="fas fa-briefcase text-2xl text-blue-600 group-hover:text-white transition"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-1">Latest Jobs</h3>
                <p class="text-sm text-gray-600">Govt Job Notifications</p>
            </a>
            
            <a href="{{ route('posts.admit-cards') }}" class="group p-6 bg-white border-2 border-gray-200 rounded-xl hover:border-purple-600 hover:shadow-lg transition text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-600 transition">
                    <i class="fas fa-id-card text-2xl text-purple-600 group-hover:text-white transition"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-1">Admit Cards</h3>
                <p class="text-sm text-gray-600">Download Hall Tickets</p>
            </a>
            
            <a href="{{ route('posts.results') }}" class="group p-6 bg-white border-2 border-gray-200 rounded-xl hover:border-green-600 hover:shadow-lg transition text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-600 transition">
                    <i class="fas fa-chart-bar text-2xl text-green-600 group-hover:text-white transition"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-1">Results</h3>
                <p class="text-sm text-gray-600">Check Exam Results</p>
            </a>
            
            <a href="{{ route('posts.answer-keys') }}" class="group p-6 bg-white border-2 border-gray-200 rounded-xl hover:border-orange-600 hover:shadow-lg transition text-center">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-orange-600 transition">
                    <i class="fas fa-key text-2xl text-orange-600 group-hover:text-white transition"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-1">Answer Keys</h3>
                <p class="text-sm text-gray-600">Download Answer Keys</p>
            </a>
        </div>

        <!-- Latest Posts Section -->
        @php
            $latestJobs = \App\Models\Post::where('is_published', 1)->where('type', 'job')->orderBy('created_at', 'desc')->limit(6)->get();
            $latestResults = \App\Models\Post::where('is_published', 1)->where('type', 'result')->orderBy('created_at', 'desc')->limit(6)->get();
            $latestAdmitCards = \App\Models\Post::where('is_published', 1)->where('type', 'admit_card')->orderBy('created_at', 'desc')->limit(6)->get();
        @endphp

        <!-- Latest Jobs -->
        @if($latestJobs->count() > 0)
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-1 h-8 bg-blue-600 rounded"></span>
                    Latest Government Jobs
                </h3>
                <a href="{{ route('posts.jobs') }}" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-2">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($latestJobs as $job)
                <a href="{{ route('posts.show', [$job->type, $job->slug]) }}" class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-blue-600 transition">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-briefcase text-blue-600 text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-800 line-clamp-2 mb-2">{{ $job->title }}</h4>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span class="flex items-center gap-1">
                                    <i class="far fa-calendar text-xs"></i>
                                    {{ $job->created_at->format('d M Y') }}
                                </span>
                                @if($job->view_count > 0)
                                <span class="flex items-center gap-1">
                                    <i class="far fa-eye text-xs"></i>
                                    {{ number_format($job->view_count) }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Latest Results -->
        @if($latestResults->count() > 0)
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-1 h-8 bg-green-600 rounded"></span>
                    Latest Results
                </h3>
                <a href="{{ route('posts.results') }}" class="text-green-600 hover:text-green-700 font-semibold flex items-center gap-2">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($latestResults as $result)
                <a href="{{ route('posts.show', [$result->type, $result->slug]) }}" class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-green-600 transition">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-chart-bar text-green-600 text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-800 line-clamp-2 mb-2">{{ $result->title }}</h4>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span class="flex items-center gap-1">
                                    <i class="far fa-calendar text-xs"></i>
                                    {{ $result->created_at->format('d M Y') }}
                                </span>
                                @if($result->view_count > 0)
                                <span class="flex items-center gap-1">
                                    <i class="far fa-eye text-xs"></i>
                                    {{ number_format($result->view_count) }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Latest Admit Cards -->
        @if($latestAdmitCards->count() > 0)
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-1 h-8 bg-purple-600 rounded"></span>
                    Latest Admit Cards
                </h3>
                <a href="{{ route('posts.admit-cards') }}" class="text-purple-600 hover:text-purple-700 font-semibold flex items-center gap-2">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($latestAdmitCards as $admitCard)
                <a href="{{ route('posts.show', [$admitCard->type, $admitCard->slug]) }}" class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-purple-600 transition">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-id-card text-purple-600 text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-800 line-clamp-2 mb-2">{{ $admitCard->title }}</h4>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span class="flex items-center gap-1">
                                    <i class="far fa-calendar text-xs"></i>
                                    {{ $admitCard->created_at->format('d M Y') }}
                                </span>
                                @if($admitCard->view_count > 0)
                                <span class="flex items-center gap-1">
                                    <i class="far fa-eye text-xs"></i>
                                    {{ number_format($admitCard->view_count) }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Popular Categories -->
        @php
            $popularCategories = \App\Models\Category::withCount('posts')->orderBy('posts_count', 'desc')->limit(8)->get();
        @endphp
        
        @if($popularCategories->count() > 0)
        <div class="mb-12">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                <span class="w-1 h-8 bg-indigo-600 rounded"></span>
                Browse by Category
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($popularCategories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" class="bg-white border border-gray-200 rounded-lg p-4 hover:border-indigo-600 hover:shadow-md transition text-center">
                    <h4 class="font-semibold text-gray-800 mb-1">{{ $category->name }}</h4>
                    <p class="text-sm text-gray-600">{{ $category->posts_count }} Posts</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Back to Home -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-semibold text-lg shadow-lg hover:shadow-xl transition">
                <i class="fas fa-home"></i>
                Back to Homepage
            </a>
        </div>

    </div>
</div>
@endsection
