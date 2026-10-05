@props(['user' => null])

@php
    $user = $user ?? Auth::user();
@endphp

<div class="space-y-4">
    <!-- Avatar -->
    <div class="flex flex-col items-center">
        <div class="relative mb-4">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}"
                     alt="{{ $user->name }}"
                     class="w-32 h-32 rounded-md border-4 border-indigo-100 object-cover">
            @else
                <div class="w-32 h-32 rounded-md bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center border-4 border-indigo-100">
                    <span class="text-5xl font-bold text-white">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>
            @endif
            <!-- Online Status -->
            <div class="absolute bottom-2 right-2 w-6 h-6 bg-green-500 rounded-md border-3 border-white"></div>
        </div>

        <!-- User Info -->
        <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
        <p class="text-sm text-gray-600 mt-1">{{ $user->email }}</p>

        <!-- User Role -->
        @if($user->roles->count())
            <div class="mt-3 flex flex-wrap gap-2 justify-center">
                @foreach($user->roles as $role)
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-md">
                        {{ ucfirst($role->name) }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Bio -->
    @if($user->bio)
        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-700">{{ $user->bio }}</p>
        </div>
    @endif

    <!-- Account Info -->
    <div class="pt-4 border-t border-gray-200 space-y-2 text-sm">
        <div class="flex justify-between">
            <span class="text-gray-600">Member Since</span>
            <span class="font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">Last Login</span>
            <span class="font-medium text-gray-900">
                @if($user->last_login_at)
                    {{ $user->last_login_at->diffForHumans() }}
                @else
                    Never
                @endif
            </span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">Email Verified</span>
            @if($user->email_verified_at)
                <span class="font-medium text-green-600">✓ Verified</span>
            @else
                <span class="font-medium text-yellow-600">Pending</span>
            @endif
        </div>
    </div>

    <!-- Slot for additional content -->
    @if($slot && !empty($slot))
        <div class="pt-4 border-t border-gray-200">
            {{ $slot }}
        </div>
    @endif
</div>
