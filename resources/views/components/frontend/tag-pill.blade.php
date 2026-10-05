@props(['label', 'url' => null])

@if($url)
    <a href="{{ $url }}" class="tag-pill">{{ $label }}</a>
@else
    <span class="tag-pill">{{ $label }}</span>
@endif
