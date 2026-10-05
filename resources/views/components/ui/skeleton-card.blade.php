{{-- Skeleton Card Component for article cards loading state --}}
<div class="group hover:opacity-85 transition">
    <div class="relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition duration-300 h-full">
        <!-- Skeleton Image -->
        <div class="w-full h-48 bg-gradient-to-r from-slate-200 via-slate-100 to-slate-200 dark:from-slate-700 dark:via-slate-600 dark:to-slate-700 animate-pulse"></div>

        <!-- Skeleton Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent"></div>

        <!-- Skeleton Content -->
        <div class="absolute bottom-0 left-0 right-0 p-6">
            <!-- Category Badge Skeleton -->
            <div class="mb-3">
                <div class="inline-block h-6 w-20 bg-slate-300 dark:bg-slate-600 rounded-full animate-pulse"></div>
            </div>

            <!-- Title Skeleton (3 lines) -->
            <div class="space-y-2 mb-2">
                <div class="h-5 bg-slate-300 dark:bg-slate-600 rounded animate-pulse"></div>
                <div class="h-5 bg-slate-300 dark:bg-slate-600 rounded animate-pulse w-5/6"></div>
            </div>

            <!-- Metadata Skeleton -->
            <div class="flex items-center gap-4">
                <div class="h-3 w-24 bg-slate-300 dark:bg-slate-600 rounded animate-pulse"></div>
                <div class="h-3 w-20 bg-slate-300 dark:bg-slate-600 rounded animate-pulse"></div>
            </div>
        </div>
    </div>
</div>
