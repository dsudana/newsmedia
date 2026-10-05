@php
    $ad = \App\Models\Advertisement::active()
        ->byPlacement($placement)
        ->first();

    // Set default dimensions based on placement
    $width = $ad->width ?? ($placement === 'header_banner' ? 1200 : 300);
    $height = $ad->height ?? ($placement === 'header_banner' ? 128 : 250);
@endphp

@if ($ad)
    <div class="advertisement-slot bg-gray-200 dark:bg-gray-800 border border-gray-300 dark:border-gray-700/50 rounded-lg flex items-center justify-center overflow-hidden transition-colors duration-300 shadow-sm dark:shadow-md"
        style="width: {{ $width }}px; height: {{ $height }}px; max-width: 100%;">

        @if ($ad->type === 'banner')
            {{-- Banner Image Ad --}}
            @php
                $imageUrl = str_starts_with($ad->image, 'http') ? $ad->image : asset('storage/' . $ad->image);
            @endphp
            @if ($ad->url)
                <a href="{{ $ad->url }}" target="_blank" rel="noopener noreferrer"
                    title="{{ $ad->name }}"
                    onclick="recordAdClick('{{ $ad->id }}')">
                    <img src="{{ $imageUrl }}"
                        alt="{{ $ad->name }}"
                        class="w-full h-full object-cover hover:opacity-90 transition-opacity"
                        loading="lazy"
                        onerror="this.src='https://via.placeholder.com/{{ $width }}x{{ $height }}?text={{ urlencode($ad->name) }}'">
                </a>
            @else
                <img src="{{ $imageUrl }}"
                    alt="{{ $ad->name }}"
                    class="w-full h-full object-cover"
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
@else
    {{-- Dummy Advertisement when no real ad exists --}}
    <div class="advertisement-slot bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 rounded-lg flex items-center justify-center overflow-hidden transition-colors duration-300"
        style="width: {{ $width }}px; height: {{ $height }}px; max-width: 100%;">
        <div class="text-center p-4">
            <div class="flex justify-center mb-3">
                <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Advertisement Space</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $width }} × {{ $height }}px</p>
        </div>
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
    const adId = '{{ $ad->id ?? "" }}';
    if (adId) {
        fetch('/api/advertisements/' + adId + '/view', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        }).catch(e => console.log('Ad view recorded'));
    }
});
</script>
