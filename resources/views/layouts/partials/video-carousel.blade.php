<section class="mb-12">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
            Video Pilihan
            <span class="text-red-600 text-2xl">›</span>
        </h2>
        <a href="{{ route('gallery.index') }}" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-semibold transition">
            Lihat Semua Videos →
        </a>
    </div>

    @if($videoGallery && $videoGallery->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($videoGallery as $video)
                <a href="{{ $video->youtube_url }}" target="_blank" rel="noopener noreferrer" class="group">
                    <x-video-card :$video />
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <i class="fas fa-video text-4xl text-gray-300 dark:text-gray-600 mb-4 block"></i>
            <p class="text-gray-500 dark:text-gray-400">No videos available</p>
        </div>
    @endif
</section>
