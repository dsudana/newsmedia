<div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 lg:sticky lg:top-6 space-y-4">
    <h3 class="text-lg font-bold text-gray-900 mb-6">Statistics</h3>

    <!-- Articles Metric -->
    <div class="pb-4 border-b border-gray-200">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-600">📰 Articles</span>
        </div>
        <div class="text-2xl font-bold text-gray-900">{{ $metrics['articles']['total'] }}</div>
        @php
            $article_trend = $metrics['articles']['this_month'] - $metrics['articles']['last_month'];
            $trend_color = $article_trend > 0 ? 'text-green-600' : ($article_trend < 0 ? 'text-red-600' : 'text-gray-500');
        @endphp
        <div class="text-sm {{ $trend_color }}">
            @if($article_trend > 0)
                +{{ $article_trend }} this month ✓
            @elseif($article_trend < 0)
                {{ $article_trend }} this month
            @else
                No change
            @endif
        </div>
    </div>

    <!-- Users Metric -->
    <div class="pb-4 border-b border-gray-200">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-600">👥 Users</span>
        </div>
        <div class="text-2xl font-bold text-gray-900">{{ $metrics['users']['total'] }}</div>
        @php
            $user_trend = $metrics['users']['this_month'] - $metrics['users']['last_month'];
            $trend_color = $user_trend > 0 ? 'text-green-600' : ($user_trend < 0 ? 'text-red-600' : 'text-gray-500');
        @endphp
        <div class="text-sm {{ $trend_color }}">
            @if($user_trend > 0)
                +{{ $user_trend }} this month ✓
            @elseif($user_trend < 0)
                {{ $user_trend }} this month
            @else
                No change
            @endif
        </div>
    </div>

    <!-- Categories Metric -->
    <div class="pb-4 border-b border-gray-200">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-600">🏷️ Categories</span>
        </div>
        <div class="text-2xl font-bold text-gray-900">{{ $metrics['categories']['total'] }}</div>
        @php
            $cat_trend = $metrics['categories']['this_month'];
            $trend_color = $cat_trend > 0 ? 'text-green-600' : 'text-gray-500';
        @endphp
        <div class="text-sm {{ $trend_color }}">
            @if($cat_trend > 0)
                +{{ $cat_trend }} this month ✓
            @else
                No change
            @endif
        </div>
    </div>

    <!-- Subscribers Metric -->
    <div class="pb-4 border-b border-gray-200">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-600">📧 Subscribers</span>
        </div>
        <div class="text-2xl font-bold text-gray-900">{{ $metrics['subscribers']['total'] }}</div>
        @php
            $sub_trend = $metrics['subscribers']['this_month'];
            $trend_color = $sub_trend > 0 ? 'text-green-600' : 'text-gray-500';
        @endphp
        <div class="text-sm {{ $trend_color }}">
            @if($sub_trend > 0)
                +{{ $sub_trend }} this month ✓
            @else
                No change
            @endif
        </div>
    </div>

    <!-- Homepage Sections Metric -->
    <div class="pb-4 border-b border-gray-200">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-600">🎨 Builder Sections</span>
        </div>
        <div class="text-2xl font-bold text-gray-900">{{ $metrics['homepage_sections'] }}</div>
        <div class="text-sm text-gray-500">active sections</div>
    </div>

    <!-- Average Engagement Metric -->
    <div class="pb-4 border-b border-gray-200">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-600">📊 Avg Engagement</span>
        </div>
        <div class="text-2xl font-bold text-gray-900">{{ number_format($metrics['avg_engagement']) }}</div>
        <div class="text-sm text-gray-500">views per post</div>
    </div>

    <!-- Refresh Button -->
    <button onclick="location.reload()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-900 font-medium py-2 px-4 rounded-lg transition">
        ↻ Refresh
    </button>
</div>
