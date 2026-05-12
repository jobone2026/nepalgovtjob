<div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
    <div class="bg-{{ $color }}-600 text-white px-6 py-4">
        <h2 class="text-2xl font-bold">{{ $title }}</h2>
    </div>
    
    <div class="p-6">
        @if ($posts->count() > 0)
            <div class="space-y-4">
                @foreach ($posts as $post)
                    <x-post-card :post="$post" />
                @endforeach
            </div>
            
            <div class="mt-6 text-center">
                @php
                    $routeMap = [
                        'job' => 'posts.jobs',
                        'admit_card' => 'posts.admit-cards',
                        'result' => 'posts.results',
                        'answer_key' => 'posts.answer-keys',
                        'syllabus' => 'posts.syllabus',
                        'blog' => 'posts.blogs',
                    ];
                    $routeName = $routeMap[$type] ?? 'home';
                @endphp
                <a href="{{ route($routeName) }}" class="inline-block bg-{{ $color }}-600 text-white px-6 py-2 rounded-lg hover:bg-{{ $color }}-700">
                    View All {{ $title }}
                </a>
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No posts available</p>
        @endif
    </div>
</div>
