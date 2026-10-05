<!-- Featured Hero Section -->
@if(isset($latestArticles) && $latestArticles->count() > 0)
    @php $featured = $latestArticles->first(); @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Large Featured -->
        <a href="{{ route('articles.show', $featured->slug) }}" class="group block md:col-span-2">
            <div class="bg-gray-900 h-80 relative overflow-hidden">
                @if($featured->featured_image)
                    <img src="{{ asset('storage/' . $featured->featured_image) }}"
                         alt="{{ $featured->title }}"
                         class="w-full h-full object-cover opacity-60 group-hover:opacity-80 transition">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent flex flex-col justify-end p-4">
                    @if($featured->category)
                        <span class="inline-block px-2 py-1 bg-red-600 text-white text-xs font-bold w-fit mb-2">
                            {{ strtoupper($featured->category->name) }}
                        </span>
                    @endif
                    <h2 class="text-white font-bold text-2xl line-clamp-3 group-hover:text-red-600 transition">
                        {{ $featured->title }}
                    </h2>
                </div>
            </div>
        </a>

        <!-- 2 Small Featured Cards -->
        <div class="space-y-4">
            @foreach($latestArticles->skip(1)->take(2) as $article)
                <x-frontend.featured-card-modern :article="$article" :height="'h-44'" />
            @endforeach
        </div>
    </div>
@endif
