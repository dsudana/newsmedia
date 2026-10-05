@props(['article'])

@php
    $affiliateLinks = $article->affiliateLinks()->where('is_active', true)->get();
@endphp

@if ($affiliateLinks->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700/50 shadow-sm dark:shadow-md transition-shadow duration-300 mb-8">
        <!-- Header -->
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-amber-50 dark:from-amber-900 dark:to-amber-800 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-cart text-amber-600 dark:text-amber-400 text-lg"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recommended Products</h3>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach ($affiliateLinks as $link)
                <a href="{{ url('go/' . $link->slug) }}" target="_blank" rel="noopener noreferrer"
                    class="group flex flex-col bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600/40 rounded-lg overflow-hidden hover:shadow-lg dark:hover:shadow-md hover:border-amber-300 dark:hover:border-amber-400/50 transition">

                    <!-- Product Image -->
                    <div class="relative w-full aspect-square bg-gray-200 dark:bg-gray-900 overflow-hidden">
                        @if ($link->image)
                            @php
                                $imageUrl = str_starts_with($link->image, 'http')
                                    ? $link->image
                                    : asset('storage/' . $link->image);
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $link->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-400 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                                <i class="fas fa-box text-4xl text-gray-400 dark:text-gray-600"></i>
                            </div>
                        @endif

                        <!-- Cart Icon Badge -->
                        <div class="absolute top-2 right-2 w-9 h-9 bg-amber-600 rounded-full flex items-center justify-center text-white shadow-lg group-hover:bg-amber-700 transition">
                            <i class="fas fa-shopping-cart text-sm"></i>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-4 flex flex-col flex-1">
                        <!-- Product Name -->
                        <h4 class="font-semibold text-gray-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition line-clamp-3 mb-3">
                            {{ $link->name }}
                        </h4>

                        <!-- Price -->
                        @if ($link->price)
                            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-auto">
                                Rp {{ number_format($link->price, 0, ',', '.') }}
                            </p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Footer Note -->
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700/50">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                <i class="fas fa-info-circle mr-1"></i>
                Produk rekomendasi yang kami percaya. Jika Anda melakukan pembelian, kami dapat memperoleh komisi tanpa biaya tambahan bagi Anda.
            </p>
        </div>
    </div>
@endif
