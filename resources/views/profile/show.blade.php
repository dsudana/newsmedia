<x-layouts.app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Profile') }}
            </h2>
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium text-sm">
                <i class="fas fa-edit mr-2"></i>Edit Profile
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Card -->
            <div class="bg-white rounded-md shadow-lg border border-gray-200 overflow-hidden">
                <!-- Header Background -->
                <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600"></div>

                <!-- Profile Content -->
                <div class="px-6 py-8 sm:px-12">
                    <div class="flex flex-col sm:flex-row gap-8">
                        <!-- Avatar & Basic Info -->
                        <div class="flex flex-col items-center sm:items-start gap-4">
                            <!-- Avatar -->
                            <div class="relative -mt-24 mb-4">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}"
                                         alt="{{ $user->name }}"
                                         class="w-40 h-40 rounded-md border-4 border-white object-cover shadow-lg">
                                @else
                                    <div class="w-40 h-40 rounded-md bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center border-4 border-white shadow-lg">
                                        <span class="text-6xl font-bold text-white">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <!-- Online Status -->
                                <div class="absolute bottom-2 right-2 w-5 h-5 bg-green-500 rounded-md border-2 border-white"></div>
                            </div>

                            <!-- User Name & Email -->
                            <div class="text-center sm:text-left">
                                <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                                <p class="text-gray-600 mt-1">{{ $user->email }}</p>

                                <!-- Verification Badge -->
                                @if($user->email_verified_at)
                                    <p class="text-sm text-green-600 mt-2 flex items-center gap-1">
                                        <i class="fas fa-check-circle"></i> Email verified
                                    </p>
                                @else
                                    <p class="text-sm text-yellow-600 mt-2 flex items-center gap-1">
                                        <i class="fas fa-clock"></i> Email pending verification
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="flex-1 space-y-6">
                            <!-- Roles -->
                            <div>
                                <p class="text-sm font-semibold text-gray-600 uppercase tracking-widest">Roles & Permissions</p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @forelse($user->roles as $role)
                                        <span class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-md font-medium text-sm">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @empty
                                        <span class="text-gray-600 text-sm">No roles assigned</span>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Bio -->
                            @if($user->bio)
                                <div>
                                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-widest">Bio</p>
                                    <p class="mt-3 text-gray-700 leading-relaxed">{{ $user->bio }}</p>
                                </div>
                            @endif

                            <!-- Account Info -->
                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                                <div>
                                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-widest">Member Since</p>
                                    <p class="mt-2 text-lg font-medium text-gray-900">{{ $user->created_at->translatedFormat('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-widest">Account Status</p>
                                    <p class="mt-2 text-lg font-medium">
                                        @if($user->is_active)
                                            <span class="text-green-600 flex items-center gap-1">
                                                <i class="fas fa-circle"></i> Active
                                            </span>
                                        @else
                                            <span class="text-gray-600 flex items-center gap-1">
                                                <i class="fas fa-circle"></i> Inactive
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="bg-white rounded-md shadow border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Articles Created</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Article::where('user_id', $user->id)->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-indigo-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-book text-indigo-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-md shadow border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Views</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                {{ \App\Models\ArticleView::whereHas('article', fn($q) => $q->where('user_id', $user->id))->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-eye text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-md shadow border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Comments Received</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                {{ \App\Models\Comment::whereHas('article', fn($q) => $q->where('user_id', $user->id))->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-comments text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Section -->
            <div class="bg-white rounded-md shadow border border-gray-200 p-6 mt-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Security & Settings</h3>
                <div class="space-y-3 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition font-medium text-sm">
                        <i class="fas fa-key mr-2"></i>Change Password
                    </a>
                    <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition font-medium text-sm">
                        <i class="fas fa-shield-alt mr-2"></i>Privacy Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition font-medium text-sm">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app-layout>
