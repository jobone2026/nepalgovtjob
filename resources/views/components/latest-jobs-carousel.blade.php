@php
    $latestJobs = \App\Models\Post::published()
        ->ofType('job')
        ->latest()
        ->limit(5)
        ->get();
    
    $today = now();
    $dayName = $today->format('l'); // Monday, Tuesday, etc.
    $dateFormatted = $today->format('d M Y'); // 12 Mar 2026
@endphp

<div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2 md:py-3 sticky top-16 z-40 shadow-md">
    <div class="max-w-7xl mx-auto px-1 sm:px-2 md:px-4 lg:px-8">
        <div class="flex items-center justify-between gap-1 md:gap-3">
            <!-- Today's Date & Day -->
            <div class="flex-shrink-0 text-xs md:text-sm font-semibold whitespace-nowrap">
                <div class="hidden sm:block text-xs md:text-sm">{{ $dayName }}</div>
                <div class="text-xs md:text-sm">{{ $dateFormatted }}</div>
            </div>
            
            <!-- Carousel Container -->
            <div class="flex-1 overflow-hidden">
                <div class="flex items-center gap-1 md:gap-2">
                    <!-- Left Arrow -->
                    <button onclick="scrollCarousel('left')" class="flex-shrink-0 p-1 hover:bg-white hover:bg-opacity-20 rounded transition">
                        <i class="fas fa-chevron-left text-xs md:text-sm"></i>
                    </button>
                    
                    <!-- Jobs Carousel -->
                    <div id="jobsCarousel" class="flex-1 overflow-x-auto scrollbar-hide">
                        <div class="flex gap-1 md:gap-2 pb-0.5">
                            @forelse($latestJobs as $job)
                                <a href="{{ route('posts.show', [$job->type, $job->slug]) }}" 
                                   class="flex-shrink-0 bg-white bg-opacity-10 hover:bg-opacity-20 rounded px-2 md:px-3 py-1 md:py-2 transition whitespace-nowrap text-xs md:text-sm font-medium group">
                                    <div class="truncate group-hover:text-yellow-200 text-xs md:text-sm">{{ $job->title }}</div>
                                    <div class="text-xs opacity-75">{{ $job->created_at->format('d M') }}</div>
                                </a>
                            @empty
                                <div class="text-xs opacity-75">No jobs</div>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Right Arrow -->
                    <button onclick="scrollCarousel('right')" class="flex-shrink-0 p-1 hover:bg-white hover:bg-opacity-20 rounded transition">
                        <i class="fas fa-chevron-right text-xs md:text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Hide scrollbar but keep functionality */
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>

<script>
    function scrollCarousel(direction) {
        const carousel = document.getElementById('jobsCarousel');
        const scrollAmount = 300;
        
        if (direction === 'left') {
            carousel.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        } else {
            carousel.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        }
    }
</script>
