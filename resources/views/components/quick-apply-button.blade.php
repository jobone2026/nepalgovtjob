@props(['post'])

@if($post->type === 'job' && $post->important_links && count($post->important_links) > 0)
    @php
        $applyLink = null;
        $importantLinks = is_string($post->important_links) ? json_decode($post->important_links, true) : $post->important_links;
        
        // Find the apply link
        foreach ($importantLinks as $key => $value) {
            $linkUrl = is_array($value) && isset($value['url']) ? $value['url'] : $value;
            $linkLabel = is_array($value) && isset($value['label']) ? $value['label'] : $key;
            
            if (stripos($linkLabel, 'apply') !== false || stripos($key, 'apply') !== false) {
                $applyLink = $linkUrl;
                break;
            }
        }
        
        // If no apply link found, use the first link
        if (!$applyLink && count($importantLinks) > 0) {
            $firstLink = reset($importantLinks);
            $applyLink = is_array($firstLink) && isset($firstLink['url']) ? $firstLink['url'] : $firstLink;
        }
    @endphp

    @if($applyLink)
        <!-- Floating Quick Apply Button -->
        <div class="fixed bottom-6 right-6 z-50 animate-bounce-slow">
            <a href="{{ $applyLink }}" 
               target="_blank" 
               rel="noopener noreferrer"
               class="flex items-center gap-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-4 rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300 font-bold text-base group">
                <svg class="w-6 h-6 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="hidden sm:inline">Apply Now</span>
                <span class="sm:hidden">Apply</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>

        <style>
            @keyframes bounce-slow {
                0%, 100% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-10px);
                }
            }
            .animate-bounce-slow {
                animation: bounce-slow 2s infinite;
            }
            .shadow-3xl {
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
        </style>
    @endif
@endif
