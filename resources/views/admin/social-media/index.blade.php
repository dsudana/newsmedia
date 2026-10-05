<x-admin.layout-modern>
    <div class="space-y-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Social Media Links</h1>
                    <a href="{{ route('admin.social-media.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Add New
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Platform</th>
                                <th class="px-4 py-2 text-left">Icon</th>
                                <th class="px-4 py-2 text-left">URL</th>
                                <th class="px-4 py-2 text-left">Active</th>
                                <th class="px-4 py-2 text-left">Order</th>
                                <th class="px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($socialMedias as $social)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $social->platform }}</td>
                                    <td class="px-4 py-3">
                                        <i class="{{ $social->icon }}"></i>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ $social->url }}" target="_blank" class="text-blue-600 hover:underline">
                                            {{ $social->url }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-3 py-1 rounded-full text-sm {{ $social->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $social->is_active ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $social->sort_order }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.social-media.edit', $social) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.social-media.destroy', $social) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                                        No social media links found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

        <div class="mt-6">
            {{ $socialMedias->links() }}
        </div>
    </div>
</x-admin.layout-modern>
