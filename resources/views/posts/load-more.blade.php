@foreach ($posts as $post)
    <div class="modern-list-item">
        <a href="{{ route('posts.show', ['type' => $post->type, 'post' => $post->slug]) }}">
            {{ $post->title }}
        </a>
        <div class="modern-list-item-date"><i class="fas fa-calendar-alt"></i> {{ $post->created_at->format('M d, Y') }}</div>
    </div>
@endforeach
