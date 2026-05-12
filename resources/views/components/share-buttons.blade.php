<div class="bg-white rounded-lg shadow-sm p-3 mb-6 border border-gray-200">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-2">
            <i class="fas fa-share-alt text-blue-600 text-lg"></i>
            <div>
                <h3 class="text-xs font-bold text-gray-800">Share</h3>
                <p class="text-xs text-gray-500">{{ url()->current() }}</p>
            </div>
        </div>
        <div class="flex gap-1.5 flex-wrap">
            <!-- WhatsApp Share -->
            <a href="https://wa.me/?text={{ urlencode(request()->getHost() . ' - ' . url()->current()) }}" 
               target="_blank" 
               rel="noopener noreferrer"
               @click="typeof trackShare !== 'undefined' && trackShare('whatsapp', '{{ url()->current() }}', '{{ $title ?? "Post" }}')"
               class="inline-flex items-center justify-center w-7 h-7 rounded-full hover:scale-110 transition shadow-sm hover:shadow-md"
               title="Share on WhatsApp">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/whatsapp.svg" alt="WhatsApp" class="w-7 h-7" style="filter: invert(48%) sepia(79%) saturate(2476%) hue-rotate(86deg) brightness(118%) contrast(119%);" loading="lazy">
            </a>

            <!-- Telegram Share -->
            <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode(request()->getHost()) }}" 
               target="_blank" 
               rel="noopener noreferrer"
               @click="typeof trackShare !== 'undefined' && trackShare('telegram', '{{ url()->current() }}', '{{ $title ?? "Post" }}')"
               class="inline-flex items-center justify-center w-7 h-7 rounded-full hover:scale-110 transition shadow-sm hover:shadow-md"
               title="Share on Telegram">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/telegram.svg" alt="Telegram" class="w-7 h-7" style="filter: invert(58%) sepia(89%) saturate(1583%) hue-rotate(182deg) brightness(101%) contrast(101%);" loading="lazy">
            </a>

            <!-- Instagram Share -->
            <a href="https://instagram.com" 
               target="_blank" 
               rel="noopener noreferrer"
               @click="typeof trackShare !== 'undefined' && trackShare('instagram', '{{ url()->current() }}', '{{ $title ?? "Post" }}')"
               class="inline-flex items-center justify-center w-7 h-7 rounded-full hover:scale-110 transition shadow-sm hover:shadow-md"
               title="Follow on Instagram">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/instagram.svg" alt="Instagram" class="w-7 h-7" style="filter: invert(37%) sepia(93%) saturate(4151%) hue-rotate(316deg) brightness(94%) contrast(94%);" loading="lazy">
            </a>

            <!-- Facebook Share -->
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
               target="_blank" 
               rel="noopener noreferrer"
               @click="typeof trackShare !== 'undefined' && trackShare('facebook', '{{ url()->current() }}', '{{ $title ?? "Post" }}')"
               class="inline-flex items-center justify-center w-7 h-7 rounded-full hover:scale-110 transition shadow-sm hover:shadow-md"
               title="Share on Facebook">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/facebook.svg" alt="Facebook" class="w-7 h-7" style="filter: invert(35%) sepia(93%) saturate(2466%) hue-rotate(201deg) brightness(96%) contrast(106%);" loading="lazy">
            </a>

            <!-- Twitter Share -->
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode(request()->getHost()) }}" 
               target="_blank" 
               rel="noopener noreferrer"
               @click="typeof trackShare !== 'undefined' && trackShare('twitter', '{{ url()->current() }}', '{{ $title ?? "Post" }}')"
               class="inline-flex items-center justify-center w-7 h-7 rounded-full hover:scale-110 transition shadow-sm hover:shadow-md"
               title="Share on Twitter">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/x.svg" alt="Twitter/X" class="w-7 h-7" style="filter: invert(0%);" loading="lazy">
            </a>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Link copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}
</script>
