@props(['src', 'alt' => '', 'class' => '', 'width' => null, 'height' => null])

<img
    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 {{ $width ?? 1000 }} {{ $height ?? 600 }}'%3E%3C/svg%3E"
    data-src="{{ $src }}"
    alt="{{ $alt }}"
    class="lazy-image {{ $class }}"
    {{ $attributes->merge([
        'width' => $width,
        'height' => $height,
        'loading' => 'lazy'
    ]) }}
/>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('.lazy-image');

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px'
            });

            images.forEach(img => observer.observe(img));
        } else {
            // Fallback for older browsers
            images.forEach(img => {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            });
        }
    });
</script>
