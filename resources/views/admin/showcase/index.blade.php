<x-admin-layout-modern>
    <x-slot name="header">
        Platform Showcase
    </x-slot>

    <div class="space-y-8">
        <!-- Hero Banner -->
        <div class="bg-gradient-to-r from-purple-500 to-blue-600 rounded-lg shadow-lg overflow-hidden">
            <div class="px-8 py-20 text-center">
                <h1 class="text-4xl font-bold text-white mb-4">Platform Overview</h1>
                <p class="text-lg text-gray-100">Real-time snapshot of your content ecosystem and platform health</p>
            </div>
        </div>

        <!-- Main Grid: Feature Cards (left) + Statistics Sidebar (right) -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <!-- Feature Cards Section (3/4 width on desktop) -->
            <div class="lg:col-span-3">
                @include('admin.showcase.partials.feature-cards', compact('metrics'))
            </div>

            <!-- Statistics Sidebar (1/4 width on desktop) -->
            <aside class="lg:col-span-1">
                @include('admin.showcase.partials.statistics-sidebar', compact('metrics'))
            </aside>

        </div>
    </div>
</x-admin-layout-modern>
