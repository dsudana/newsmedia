<div class="space-y-8">
    {{-- Latest Post Section --}}
    <div>
        <div class="news-section-title mb-6">
            <h3 class="text-2xl font-bold text-black">Latest Post</h3>
        </div>

        @php
            $latestPost = collect($data['latestPost'] ?? [])->first();
        @endphp

        @if ($latestPost)
            <article class="news-card group">
                {{-- IMAGE --}}
                <a href="{{ route('blog.show', $latestPost->slug) }}" class="news-card__image mb-4">
                    <img src="{{ \App\Helpers\ImageHelper::articleImage($latestPost) }}" alt="{{ $latestPost->title }}"
                        loading="lazy" class="w-full aspect-video object-cover">
                </a>

                {{-- CATEGORY --}}
                @if ($latestPost->category)
                    <span class="inline-block bg-red-600 text-white text-xs font-bold px-3 py-1 mb-3">
                        {{ strtoupper($latestPost->category->name) }}
                    </span>
                @endif

                {{-- META --}}
                <div class="news-card__meta">
                    <span class="news-card__author">
                        By {{ $latestPost->user?->name ?? 'Admin' }}
                    </span>
                    <span class="text-gray-300">|</span>
                    <span class="news-card__date">
                        {{ $latestPost->published_at?->format('F d, Y') }}
                    </span>
                </div>

                {{-- TITLE --}}
                <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                    <a href="{{ route('blog.show', $latestPost->slug) }}" class="hover:text-red-600 transition">
                        {{ $latestPost->title }}
                    </a>
                </h3>

                {{-- EXCERPT --}}
                @if ($latestPost->excerpt)
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        {{ $latestPost->excerpt }}
                    </p>
                @endif

                {{-- READ MORE BUTTON --}}
                <a href="{{ route('blog.show', $latestPost->slug) }}"
                    class="inline-block border-2 border-red-600 text-red-600 font-bold px-6 py-2 hover:bg-red-600 hover:text-white transition">
                    Read More
                </a>
            </article>
        @endif
    </div>

    {{-- Related Articles Section --}}
    <div>
        <div class="news-section-title mb-6">
            <h3 class="text-2xl font-bold text-black">Related Articles</h3>
        </div>

        <div class="space-y-4">
            @php
                $relatedArticles = collect($data['relatedArticles'] ?? [])->take(3);
            @endphp

            @forelse ($relatedArticles as $article)
                <article class="flex gap-3 group">
                    {{-- THUMBNAIL --}}
                    <a href="{{ route('blog.show', $article->slug) }}" class="flex-shrink-0 w-20 h-20 overflow-hidden">
                        <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : asset('images/placeholder.jpg') }}"
                            alt="{{ $article->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition">
                    </a>

                    {{-- CONTENT --}}
                    <div class="flex-1 min-w-0">
                        <h4
                            class="font-bold text-sm text-gray-900 mb-2 line-clamp-2 group-hover:text-red-600 transition">
                            <a href="{{ route('blog.show', $article->slug) }}">
                                {{ $article->title }}
                            </a>
                        </h4>
                        <div class="news-card__meta text-xs">
                            <span class="news-card__author">
                                {{ $article->user?->name ?? 'Admin' }}
                            </span>
                            <span class="text-gray-300">|</span>
                            <span class="news-card__date">
                                {{ \App\Helpers\DateHelper::relativeTime($article->published_at) }}
                            </span>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-gray-500 text-sm">Belum ada artikel lainnya.</p>
            @endforelse
        </div>
    </div>

    {{-- Stay Connected Section --}}
    <div>
        <div class="news-section-title mb-6">
            <h3 class="text-2xl font-bold text-black">Stay Connected</h3>
        </div>

        <div class="mb-6 bg-gray-100 rounded-md p-4">
            <p class="text-sm font-semibold text-gray-900 mb-3">Follow us:</p>

            <div class="flex gap-3 flex-wrap justify-center">
                <!-- Facebook -->
                @php $facebook_url = \App\Models\SiteSetting::get('facebook_url') ?? 'https://facebook.com'; @endphp
                <a href="{{ $facebook_url }}" target="_blank" rel="noopener"
                    class="w-10 h-10 bg-blue-600 rounded-md flex items-center justify-center text-white hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg transform hover:scale-110 duration-200">
                    <i class="fab fa-facebook-f text-lg"></i>
                </a>

                <!-- Twitter -->
                @php $twitter_url = \App\Models\SiteSetting::get('twitter_url') ?? 'https://twitter.com'; @endphp
                <a href="{{ $twitter_url }}" target="_blank" rel="noopener"
                    class="w-10 h-10 bg-black rounded-md flex items-center justify-center text-white hover:bg-gray-800 transition-colors shadow-md hover:shadow-lg transform hover:scale-110 duration-200">
                    <i class="fab fa-twitter text-lg"></i>
                </a>

                <!-- Telegram -->
                @php $telegram_url = \App\Models\SiteSetting::get('telegram_url') ?? 'https://telegram.me'; @endphp
                <a href="{{ $telegram_url }}" target="_blank" rel="noopener"
                    class="w-10 h-10 bg-sky-400 rounded-md flex items-center justify-center text-white hover:bg-sky-500 transition-colors shadow-md hover:shadow-lg transform hover:scale-110 duration-200">
                    <i class="fab fa-telegram-plane text-lg"></i>
                </a>

                <!-- Instagram -->
                @php $instagram_url = \App\Models\SiteSetting::get('instagram_url') ?? 'https://instagram.com'; @endphp
                <a href="{{ $instagram_url }}" target="_blank" rel="noopener"
                    class="w-10 h-10 bg-gradient-to-r from-pink-500 to-orange-400 rounded-md flex items-center justify-center text-white hover:shadow-lg transform hover:scale-110 duration-200 transition-all">
                    <i class="fab fa-instagram text-lg"></i>
                </a>

                <!-- YouTube -->
                @php $youtube_url = \App\Models\SiteSetting::get('youtube_url') ?? 'https://youtube.com'; @endphp
                <a href="{{ $youtube_url }}" target="_blank" rel="noopener"
                    class="w-10 h-10 bg-red-600 rounded-md flex items-center justify-center text-white hover:bg-red-700 transition-colors shadow-md hover:shadow-lg transform hover:scale-110 duration-200">
                    <i class="fab fa-youtube text-lg"></i>
                </a>

                <!-- WhatsApp -->
                @php $whatsapp_url = \App\Models\SiteSetting::get('whatsapp_url') ?? 'https://whatsapp.com'; @endphp
                <a href="{{ $whatsapp_url }}" target="_blank" rel="noopener"
                    class="w-10 h-10 bg-green-500 rounded-md flex items-center justify-center text-white hover:bg-green-600 transition-colors shadow-md hover:shadow-lg transform hover:scale-110 duration-200">
                    <i class="fab fa-whatsapp text-lg"></i>
                </a>

                <!-- TikTok -->
                @php $tiktok_url = \App\Models\SiteSetting::get('tiktok_url') ?? 'https://tiktok.com'; @endphp
                <a href="{{ $tiktok_url }}" target="_blank" rel="noopener"
                    class="w-10 h-10 bg-black rounded-md flex items-center justify-center text-white hover:bg-gray-900 transition-colors shadow-md hover:shadow-lg transform hover:scale-110 duration-200">
                    <i class="fab fa-tiktok text-lg"></i>
                </a>
            </div>
        </div>

    </div>

    {{-- Tags Section --}}
    <div>
        <div class="news-section-title mb-6">
            <h3 class="text-2xl font-bold text-black">Kategori</h3>
        </div>

        <div class="flex flex-wrap gap-3">
            @php
                $categories = \App\Models\Category::where('id', '!=', 1)
                    ->orderBy('name')
                    ->get(['name', 'slug']);
            @endphp

            @forelse($categories as $category)
                <a href="{{ route('blog.category', $category->slug) }}"
                    class="inline-block text-xs font-bold uppercase px-4 py-2 border-2 border-gray-400 text-gray-700 rounded-md hover:border-red-600 hover:text-red-600 hover:bg-red-50 transition duration-300">
                    {{ $category->name }}
                </a>
            @empty
                <p class="text-gray-500 text-sm">Belum ada kategori.</p>
            @endforelse
        </div>
    </div>

    {{-- Advertisement Section --}}
    <div>
        <div class="bg-red-50 border-2 border-red-600 rounded p-6 text-center">
            <div class="bg-red-600 text-white p-4 rounded mb-4">
                <h3 class="text-2xl font-bold mb-2">ADVERTISE</h3>
                <p class="text-xs text-red-50">Your advertising here</p>
            </div>
            <a href="#"
                class="inline-block bg-red-600 text-white font-bold px-6 py-2 rounded hover:bg-red-700 transition">
                Learn More
            </a>
        </div>
    </div>
</div>

