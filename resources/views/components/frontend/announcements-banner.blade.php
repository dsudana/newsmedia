@if ($announcements->count() > 0)
    <div class="bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-red-600 p-6 mb-8 rounded-r-lg">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-start gap-4">
                <div class="text-2xl">📢</div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Pengumuman Penting</h3>
                    <div class="space-y-2">
                        @foreach ($announcements as $announcement)
                            <div class="flex items-start gap-3">
                                <span
                                    class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $announcement->getPriorityColor() }} mt-0.5">
                                    {{ $announcement->getPriorityLabel() }}
                                </span>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $announcement->title }}</p>
                                    <p class="text-sm text-gray-600 line-clamp-1">{{ $announcement->content }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
