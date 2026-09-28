<x-guest-layout>
    @if ($errors->any())
        <div class="error-message">
            <strong><i class="fas fa-exclamation-circle"></i> Login Failed</strong><br>
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
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
                placeholder="admin@retnews.com"
                required
                autofocus
                autocomplete="username">
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
                autocomplete="current-password">
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
        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>{{ __('Sign In to Admin') }}
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
