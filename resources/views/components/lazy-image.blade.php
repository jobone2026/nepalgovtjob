@props(['src', 'alt' => '', 'width' => null, 'height' => null, 'class' => ''])

@php
    $webpSrc = str_replace(['.jpg', '.jpeg', '.png'], '.webp', $src);
@endphp

<picture>
    <source srcset="{{ $webpSrc }}" type="image/webp">
    <img 
        src="{{ $src }}" 
        alt="{{ $alt }}" 
        loading="lazy"
        decoding="async"
        @if($width) width="{{ $width }}" @endif
        @if($height) height="{{ $height }}" @endif
        class="{{ $class }}">
</picture>
