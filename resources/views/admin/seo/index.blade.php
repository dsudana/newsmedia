<x-admin-layout-modern>
    <x-slot name="header">
        SEO Settings - Manage Meta Tags
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">SEO Settings</h2>
                <p class="text-sm text-gray-600 mt-1">Manage SEO meta tags and optimization settings</p>
            </div>
            <a href="{{ route('admin.seo-settings.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition-colors">
                <i class="fas fa-plus"></i> New SEO Setting
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex gap-3">
                <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                <div>
                    <p class="font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Settings</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $seoSettings->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cog text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\SeoSetting::where('is_active', true)->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Indexed</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\SeoSetting::where('index', true)->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-search text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Page Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Title</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Meta Title</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody divide-y>
                    @forelse($seoSettings as $seo)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $seo->page_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $seo->page_title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($seo->meta_title, 40) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $seo->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $seo->is_active ? '✓ Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.seo-settings.edit', $seo) }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.seo-settings.destroy', $seo) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm" onclick="return confirm('Delete this setting?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox text-3xl text-gray-300 mb-2 block"></i>
                                <p class="text-gray-500 font-medium">No SEO settings found</p>
                                <p class="text-sm text-gray-400 mt-1">Create your first SEO setting to get started</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($seoSettings->hasPages())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                {{ $seoSettings->links() }}
            </div>
        @endif
    </div>
</x-admin-layout-modern>
