@props([
    'src' => '/images/placeholder.jpg',
    'alt' => 'Image',
    'class' => 'w-full h-full object-cover',
    'loading' => 'lazy',
    'sizes' => '(max-width: 768px) 100vw, 50vw',
])

<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    class="{{ $class }}"
    loading="{{ $loading }}"
    sizes="{{ $sizes }}"
    decoding="async"
    {{ $attributes }}
/>
