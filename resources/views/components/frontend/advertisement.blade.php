@php
    try {
        $ad = \App\Models\Advertisement::active()->byPlacement($placement)->first();
    } catch (\Exception $e) {
        $ad = null;
    }

    // Set default dimensions based on placement
    $width = $ad->width ?? ($placement === 'header_banner' ? 1200 : 300);
    $height = $ad->height ?? ($placement === 'header_banner' ? 128 : 250);
@endphp

@if ($ad)
    <div class="advertisement-slot bg-gray-200 dark:bg-gray-800 border border-gray-400 dark:border-gray-700/50 rounded-lg flex items-center justify-center overflow-hidden transition-colors duration-300 shadow-sm dark:shadow-md"
        style="width: {{ $width }}px; height: {{ $height }}px; max-width: 100%;">

        @if ($ad->type === 'banner')
            {{-- Banner Image Ad --}}
            @php
                $imageUrl = str_starts_with($ad->image, 'http') ? $ad->image : asset('storage/' . $ad->image);
            @endphp
            @if ($ad->url)
                <a href="{{ $ad->url }}" target="_blank" rel="noopener noreferrer" title="{{ $ad->name }}"
                    onclick="recordAdClick('{{ $ad->id }}')">
                    <img src="{{ $imageUrl }}" alt="{{ $ad->name }}"
                        class="w-full h-full object-cover hover:opacity-90 transition-opacity" loading="lazy"
                        onerror="this.src='https://via.placeholder.com/{{ $width }}x{{ $height }}?text={{ urlencode($ad->name) }}'">
                </a>
            @else
                <img src="{{ $imageUrl }}" alt="{{ $ad->name }}" class="w-full h-full object-cover"
                    loading="lazy"
                    onerror="this.src='https://via.placeholder.com/{{ $width }}x{{ $height }}?text={{ urlencode($ad->name) }}'">
            @endif
        @elseif ($ad->type === 'adsense')
            {{-- Google AdSense Script --}}
            <div class="w-full h-full flex items-center justify-center">
                {!! $ad->script !!}
            </div>
        @elseif ($ad->type === 'script')
            {{-- Custom Script --}}
            <div class="w-full h-full flex items-center justify-center">
                {!! $ad->script !!}
            </div>
        @endif
    </div>
@endif

<script>
    function recordAdClick(adId) {
        fetch('/api/advertisements/' + adId + '/click', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        }).catch(e => console.log('Ad click recorded'));
    }

    // Record view
    document.addEventListener('DOMContentLoaded', function() {
        const adId = '{{ $ad->id ?? '' }}';
        if (adId) {
            fetch('/api/advertisements/' + adId + '/view', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content'),
                    'Content-Type': 'application/json'
                }
            }).catch(e => console.log('Ad view recorded'));
        }
    });
</script>
