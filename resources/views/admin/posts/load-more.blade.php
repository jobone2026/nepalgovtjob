@foreach ($posts as $post)
    <tr class="hover:bg-slate-50 transition-all duration-200">
        <td class="px-6 py-4">
            <input type="checkbox" :value="{{ $post->id }}" 
                @change="selectedPosts.includes({{ $post->id }}) ? selectedPosts = selectedPosts.filter(id => id !== {{ $post->id }}) : selectedPosts.push({{ $post->id }})"
                :checked="selectedPosts.includes({{ $post->id }})"
                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
        </td>
        <td class="px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-slate-200 to-slate-300 rounded-xl flex items-center justify-center flex-shrink-0">
                    @switch($post->type)
                        @case('job')
                            <i class="fas fa-briefcase text-slate-600"></i>
                            @break
                        @case('result')
                            <i class="fas fa-chart-bar text-slate-600"></i>
                            @break
                        @case('admit_card')
                            <i class="fas fa-id-card text-slate-600"></i>
                            @break
                        @case('answer_key')
                            <i class="fas fa-key text-slate-600"></i>
                            @break
                        @case('syllabus')
                            <i class="fas fa-book text-slate-600"></i>
                            @break
                        @case('blog')
                            <i class="fas fa-pen-fancy text-slate-600"></i>
                            @break
                        @default
                            <i class="fas fa-file text-slate-600"></i>
                    @endswitch
                </div>
                <div>
                    <p class="font-semibold text-slate-800 mb-1">{{ Str::limit($post->title, 40) }}</p>
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <i class="fas fa-calendar"></i>
                        <span>{{ $post->created_at->format('M d, Y') }}</span>
                        <span>•</span>
                        <span>{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="space-y-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                    @switch($post->type)
                        @case('job') bg-blue-100 text-blue-800 @break
                        @case('result') bg-emerald-100 text-emerald-800 @break
                        @case('admit_card') bg-orange-100 text-orange-800 @break
                        @case('answer_key') bg-purple-100 text-purple-800 @break
                        @case('syllabus') bg-indigo-100 text-indigo-800 @break
                        @case('blog') bg-pink-100 text-pink-800 @break
                        @default bg-slate-100 text-slate-800
                    @endswitch
                ">
                    {{ ucfirst(str_replace('_', ' ', $post->type)) }}
                </span>
                <div class="text-xs text-slate-600">
                    <i class="fas fa-tag mr-1"></i>
                    {{ $post->category->name ?? 'No Category' }}
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            @if ($post->is_published)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                    <i class="fas fa-check-circle mr-1"></i>
                    Published
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <i class="fas fa-clock mr-1"></i>
                    Draft
                </span>
            @endif
        </td>
        <td class="px-6 py-4">
            <div class="flex items-center gap-4 text-sm text-slate-600">
                <div class="flex items-center gap-1">
                    <i class="fas fa-eye text-blue-500"></i>
                    <span class="font-medium">{{ number_format($post->view_count) }}</span>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.posts.edit', $post) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition-all duration-200" title="Edit">
                    <i class="fas fa-edit text-sm"></i>
                </a>
                <a href="{{ route('posts.show', [$post->type, $post]) }}" target="_blank" class="bg-emerald-100 hover:bg-emerald-200 text-emerald-700 p-2 rounded-lg transition-all duration-200" title="View">
                    <i class="fas fa-external-link-alt text-sm"></i>
                </a>
                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition-all duration-200" title="Delete">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@endforeach
