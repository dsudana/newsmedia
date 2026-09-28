<!-- Categories Section -->
@if(isset($categories) && $categories->count() > 0)
    @foreach($categories->chunk(4) as $categoryChunk)
        <section class="border-t border-gray-300 py-8">
            @foreach($categoryChunk as $category)
                <div class="mb-8">
                    <div class="border-l-4 border-red-600 pl-3 mb-4">
                        <h3 class="font-bold text-lg text-gray-900 uppercase">{{ $category->name }}</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @if($category->articles)
                            @foreach($category->articles->take(4) as $article)
                                <x-article-card-modern :article="$article" />
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </section>
    @endforeach
@endif
