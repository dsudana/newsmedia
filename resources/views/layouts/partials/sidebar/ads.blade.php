<div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">
        <i class="fas fa-bullhorn text-red-600 mr-2"></i>Advertisement
    </h3>

    {{-- Ad Space (Placeholder) --}}
    <div class="bg-gray-100 rounded-lg p-8 text-center">
        <div class="aspect-square flex items-center justify-center">
            <div>
                <i class="fas fa-image text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-500 text-sm font-medium">Ad Space 300x300</p>
                <p class="text-gray-400 text-xs mt-1">Your ad here</p>
            </div>
        </div>
    </div>

    {{-- Configurable ads would go here in production --}}
    @if(isset($ads) && count($ads) > 0)
        @foreach($ads as $ad)
            <a href="{{ $ad['url'] ?? '#' }}" target="_blank" rel="noopener"
               class="block mt-4 rounded-lg overflow-hidden hover:opacity-80 transition-opacity">
                <img src="{{ $ad['image'] ?? '' }}" alt="Advertisement" class="w-full">
            </a>
        @endforeach
    @endif
</div>
