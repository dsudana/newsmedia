<x-layouts.guest-layout>
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

    <!-- Demo Credentials (Only shown in local/development) -->
    @if(app()->environment('local', 'development') || config('app.show_demo_accounts'))
        <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
            <div style="background: #f0f9ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 1.5rem;">
                <h3 style="color: #1e40af; font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-flask-vial" style="font-size: 1.2rem;"></i> Demo Accounts for Testing
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <!-- Demo User -->
                    <div style="background: white; padding: 1rem; border-radius: 6px; border-left: 3px solid #3b82f6;">
                        <p style="font-size: 0.85rem; color: #64748b; font-weight: 600; margin-bottom: 0.5rem;">REGULAR USER</p>
                        <p style="font-size: 0.9rem; margin: 0.25rem 0;"><span style="color: #64748b;">Email:</span> <strong style="color: #1e293b;">demo@newsmedia.com</strong></p>
                        <p style="font-size: 0.9rem; margin: 0.25rem 0;"><span style="color: #64748b;">Password:</span> <strong style="color: #1e293b;">demo1234</strong></p>
                    </div>

                    <!-- Demo User -->
                    <div style="background: white; padding: 1rem; border-radius: 6px; border-left: 3px solid #dc2626;">
                        <p style="font-size: 0.85rem; color: #64748b; font-weight: 600; margin-bottom: 0.5rem;">ADMIN ACCOUNT</p>
                        <p style="font-size: 0.9rem; margin: 0.25rem 0;"><span style="color: #64748b;">Email:</span> <strong style="color: #1e293b;">demo-admin@newsmedia.com</strong></p>
                        <p style="font-size: 0.9rem; margin: 0.25rem 0;"><span style="color: #64748b;">Password:</span> <strong style="color: #1e293b;">demo1234</strong></p>
                    </div>
                </div>

                <p style="font-size: 0.85rem; color: #64748b; margin: 0;">
                    💡 Use demo accounts above to test all features. Admin account has access to full dashboard.
                </p>
            </div>
        </div>
    @endif

    <style>
        .form-group input.border-red-500 {
            border-color: #ef4444;
        }

        .form-group input.border-red-500:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
    </style>
</x-layouts.guest-layout>
