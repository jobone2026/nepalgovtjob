<nav class="mb-6">
    <ol class="flex items-center space-x-2 text-sm">
        @foreach ($items as $index => $item)
            @if ($index < count($items) - 1)
                <li>
                    <a href="{{ $item['url'] }}" class="text-blue-600 hover:text-blue-800">
                        {{ $item['label'] }}
                    </a>
                </li>
                <li class="text-gray-400">/</li>
            @else
                <li class="text-gray-600">{{ $item['label'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>
