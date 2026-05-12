@props(['type' => 'card'])

@if($type === 'card')
    <!-- Card Skeleton -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border-2 border-gray-100 animate-pulse">
        <div class="bg-gray-200 h-20 px-4 py-3">
            <div class="h-4 bg-gray-300 rounded w-3/4 mb-2"></div>
            <div class="h-3 bg-gray-300 rounded w-1/2"></div>
        </div>
        <div class="px-4 py-3 space-y-3">
            <div class="h-12 bg-gray-200 rounded"></div>
            <div class="grid grid-cols-2 gap-2">
                <div class="h-16 bg-gray-200 rounded"></div>
                <div class="h-16 bg-gray-200 rounded"></div>
            </div>
            <div class="h-10 bg-gray-200 rounded"></div>
        </div>
    </div>
@elseif($type === 'list')
    <!-- List Item Skeleton -->
    <div class="bg-white rounded-lg p-4 border border-gray-200 animate-pulse">
        <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
        <div class="h-3 bg-gray-200 rounded w-1/2"></div>
    </div>
@elseif($type === 'detail')
    <!-- Detail Page Skeleton -->
    <div class="bg-white rounded-xl shadow-lg p-4 md:p-8 mb-4 animate-pulse">
        <div class="h-8 bg-gray-200 rounded w-3/4 mb-4"></div>
        <div class="flex gap-2 mb-4">
            <div class="h-6 bg-gray-200 rounded w-20"></div>
            <div class="h-6 bg-gray-200 rounded w-24"></div>
            <div class="h-6 bg-gray-200 rounded w-20"></div>
        </div>
        <div class="h-32 bg-gray-200 rounded mb-4"></div>
        <div class="space-y-2">
            <div class="h-4 bg-gray-200 rounded"></div>
            <div class="h-4 bg-gray-200 rounded"></div>
            <div class="h-4 bg-gray-200 rounded w-5/6"></div>
        </div>
    </div>
@endif

<style>
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
