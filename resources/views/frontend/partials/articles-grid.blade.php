<!-- Main Articles Grid (4 Columns) -->
@if(isset($latestArticles) && $latestArticles->count() > 3)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($latestArticles->skip(3)->take(12) as $article)
            <x-article-card-modern :article="$article" />
        @endforeach
    </div>
@endif
