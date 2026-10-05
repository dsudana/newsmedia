<x-admin.layout-modern>


    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Video YouTube</h1>
                <p class="text-gray-600 mt-2">Kelola video YouTube dari channel Anda</p>
            </div>
            <a href="{{ route('admin.videos.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-semibold transition">
                + Tambah Video
            </a>
        </div>

        <!-- Success Message -->
        @if ($message = Session::get('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-md">
                {{ $message }}
            </div>
        @endif

        <!-- Videos Grid -->
        @if ($videos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($videos as $video)
                    <div class="bg-white rounded-md shadow-md overflow-hidden hover:shadow-lg transition">
                        <!-- Thumbnail -->
                        <div class="relative h-40 bg-gray-200 overflow-hidden group cursor-pointer">
                            <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition">
                            <div
                                class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                <a href="{{ $video->youtube_url }}" target="_blank" class="text-white">
                                    <i class="fab fa-youtube text-4xl"></i>
                                </a>
                            </div>
                            <span
                                class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded">
                                {{ $video->status === 'published' ? 'Dipublikasikan' : 'Draft' }}
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 line-clamp-2 mb-2">{{ $video->title }}</h3>

                            @if ($video->category)
                                <span
                                    class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded mb-3">
                                    {{ $video->category->name }}
                                </span>
                            @endif

                            <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                {{ $video->description ?? 'Tidak ada deskripsi' }}
                            </p>

                            <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                <span>👁️ {{ $video->views_count }} views</span>
                                <span>{{ $video->published_at?->format('d M Y') }}</span>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('admin.videos.edit', $video) }}"
                                    class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 px-3 py-2 rounded text-sm font-semibold text-center transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.videos.destroy', $video) }}" method="POST"
                                    class="flex-1" onsubmit="return confirm('Yakin ingin menghapus video ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded text-sm font-semibold transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $videos->links() }}
            </div>
        @else
            <div class="bg-white rounded-md p-12 text-center">
                <i class="fab fa-youtube text-6xl text-gray-300 mb-4 block"></i>
                <p class="text-gray-500 text-lg mb-4">Belum ada video</p>
                <a href="{{ route('admin.videos.create') }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-semibold transition">
                    Tambah Video Pertama
                </a>
            </div>
        @endif
    </div>
</x-admin.layout-modern>
