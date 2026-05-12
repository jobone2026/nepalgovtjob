@extends('layouts.app')

@section('title', 'Search Results - Government Job Portal')
@section('description', 'Search results for: ' . $query)

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
            <i class="fas fa-search text-blue-600"></i> Search Results
        </h1>
        <p class="text-gray-600 text-sm mb-4">
            @if ($query)
                Found <span class="font-bold text-blue-600">{{ $posts->total() }}</span> results for "<strong class="text-gray-800">{{ $query }}</strong>"
            @else
                Please enter a search query
            @endif
        </p>
        
        <!-- Search Form -->
        <form action="{{ route('search') }}" method="GET" class="relative max-w-xl">
            <input type="text" name="q" placeholder="Search jobs, results, admit cards..." value="{{ $query }}" 
                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            <i class="fas fa-search text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2"></i>
            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                Search
            </button>
        </form>
    </div>

    @if ($posts->count() > 0)
        <!-- Grid of Post Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($posts as $post)
                <x-post-card :post="$post" />
            @endforeach
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
        <div class="mt-8 flex justify-between items-center">
            @if ($posts->onFirstPage())
                <span class="text-gray-400 flex items-center font-medium opacity-50">
                    <i class="fas fa-arrow-left mr-2"></i> Previous
                </span>
            @else
                <a href="{{ $posts->previousPageUrl() }}" class="text-blue-600 hover:text-blue-800 flex items-center font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Previous
                </a>
            @endif

            <div class="text-gray-600 font-medium bg-white border border-gray-300 px-4 py-2 rounded-lg text-sm">
                Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}
            </div>

            @if ($posts->hasMorePages())
                <a href="{{ $posts->nextPageUrl() }}" class="text-blue-600 hover:text-blue-800 flex items-center font-medium transition-colors">
                    Next <i class="fas fa-arrow-right ml-2"></i>
                </a>
            @else
                <span class="text-gray-400 flex items-center font-medium opacity-50">
                    Next <i class="fas fa-arrow-right ml-2"></i>
                </span>
            @endif
        </div>
        @endif
    @else
        <!-- Premium Empty State -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-white opacity-50 z-0"></div>
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-24 h-24 mb-6 bg-gray-50 rounded-full flex items-center justify-center shadow-inner">
                    <i class="fas fa-search text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    @if ($query)
                        No results found for "<span class="text-gray-900">{{ $query }}</span>"
                    @else
                        Please enter a search query
                    @endif
                </h3>
                <p class="text-gray-500 max-w-sm mx-auto mb-6">Try adjusting your keywords or search for a different organization or job title.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 shadow-md hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Home
                </a>
            </div>
        </div>
    @endif
@endsection
