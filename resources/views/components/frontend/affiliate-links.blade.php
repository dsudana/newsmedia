@props(['article'])

@php
    $affiliateLinks = $article->affiliateLinks()->where('is_active', true)->get();
@endphp

@if ($affiliateLinks->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-md transition-shadow duration-300 mb-8">
        <!-- Header -->
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-amber-50 dark:from-amber-900 dark:to-amber-800 rounded-lg flex items-center justify-center">
                <i class="fas fa-link text-amber-600 dark:text-amber-400 text-lg"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recommended Products</h3>
        </div>

        <!-- Links Grid -->
        <div class="space-y-3">
            @foreach ($affiliateLinks as $link)
                <a href="{{ url('go/' . $link->slug) }}" target="_blank" rel="noopener noreferrer"
                    class="block group p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-amber-50 dark:hover:bg-gray-600 transition">

                    <!-- Link Content -->
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <!-- Product Name -->
                            <h4 class="font-semibold text-gray-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition">
                                {{ $link->name }}
                            </h4>

                            <!-- Commission Info (jika ada) -->
                            @if ($link->commission_value)
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                    @if ($link->commission_type === 'percentage')
                                        Commission: <span class="font-semibold text-amber-600 dark:text-amber-400">{{ $link->commission_value }}%</span>
                                    @else
                                        Commission: <span class="font-semibold text-amber-600 dark:text-amber-400">Rp {{ number_format($link->commission_value, 0, ',', '.') }}</span>
                                    @endif
                                </p>
                            @endif
                        </div>

                        <!-- Arrow Icon -->
                        <div class="ml-3 text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition">
                            <i class="fas fa-external-link-alt text-sm"></i>
                        </div>
                    </div>

                    <!-- Click Counter -->
                    <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-600 flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                        <i class="fas fa-mouse"></i>
                        <span>{{ number_format($link->clicks_count ?? 0) }} clicks</span>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Footer Note -->
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                <i class="fas fa-info-circle mr-1"></i>
                These are recommended products we trust. If you make a purchase, we may earn a commission at no extra cost to you.
            </p>
        </div>
    </div>
@endif
