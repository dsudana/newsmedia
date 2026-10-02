<x-guest-layout>
    <!-- Throttle/Rate Limit Error -->
    @if ($errors->has('throttle'))
        <div class="error-message">
            <strong><i class="fas fa-exclamation-circle"></i> Too Many Attempts</strong><br>
            {{ $errors->first('throttle') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">
                <i class="fas fa-envelope" style="margin-right: 6px; color: #ED1C29;"></i>Email Address
            </label>
            <input
                id="email"
                type="email"
                name="email"
                class="form-input @error('email') border-red-500 @enderror"
                value="{{ old('email') }}"
                placeholder="admin@newsmedia.com"
                required
                autofocus
                autocomplete="username"
                aria-label="Email address"
                aria-describedby="email-error">
            @error('email')
                <div id="email-error" class="text-red-500 text-sm mt-2 flex items-center gap-1">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">
                <i class="fas fa-lock" style="margin-right: 6px; color: #ED1C29;"></i>Password
            </label>
            <input
                id="password"
                type="password"
                name="password"
                class="form-input @error('password') border-red-500 @enderror"
                placeholder="••••••••"
                required
                autocomplete="current-password"
                aria-label="Password"
                aria-describedby="password-error">
            @error('password')
                <div id="password-error" class="text-red-500 text-sm mt-2 flex items-center gap-1">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="checkbox-group">
            <label>
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                {{ __('Remember me') }}
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-password">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <button type="submit" class="btn-login" :disabled="loading" :class="{ 'opacity-75 cursor-not-allowed': loading }">
            <span x-show="!loading">
                <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>{{ __('Sign In') }}
            </span>
            <span x-show="loading">
                <i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i>Signing in...
            </span>
        </button>
    </form>

    <style>
        .form-group input.border-red-500 {
            border-color: #ef4444;
        }

        .form-group input.border-red-500:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
    </style>
</x-guest-layout>
