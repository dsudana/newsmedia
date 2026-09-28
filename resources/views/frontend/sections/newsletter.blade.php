{{-- Newsletter Section - Modern Design --}}
@php
    $bgColor = $section->config['background_color'] ?? '#1f2937';
    $buttonText = $section->config['button_text'] ?? 'Subscribe';
    $placeholderText = $section->config['placeholder_text'] ?? 'Enter your email address';
@endphp

<section class="py-16 px-4 sm:px-6 lg:px-8" style="background-color: {{ $bgColor }};">
    <div class="max-w-3xl mx-auto text-center">
        {{-- Accent Line --}}
        <div class="flex items-center justify-center gap-3 mb-6">
            <div class="h-1 w-16 bg-red-600 rounded"></div>
            <span class="text-sm font-bold text-red-600 uppercase tracking-widest">Newsletter</span>
            <div class="h-1 w-16 bg-red-600 rounded"></div>
        </div>

        {{-- Section Title --}}
        @if($section->title)
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
                {{ $section->title }}
            </h2>
        @endif

        {{-- Description --}}
        @if($section->description)
            <p class="text-lg sm:text-xl text-gray-100 mb-10 max-w-2xl mx-auto leading-relaxed">
                {{ $section->description }}
            </p>
        @endif

        {{-- Newsletter Form --}}
        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto">
            @csrf
            <input
                type="email"
                name="email"
                placeholder="{{ $placeholderText }}"
                required
                class="flex-1 px-6 py-4 rounded-lg border-0 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-900 outline-none text-gray-900 placeholder-gray-500 font-medium"
            />
            <button
                type="submit"
                class="px-8 py-4 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition-all transform hover:scale-105 shadow-lg whitespace-nowrap flex items-center justify-center gap-2"
            >
                {{ $buttonText }}
                <i class="fas fa-arrow-right text-sm"></i>
            </button>
        </form>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="mt-6 p-4 rounded-lg bg-green-500/20 border border-green-500 text-green-100 text-sm font-semibold">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->has('email'))
            <div class="mt-6 p-4 rounded-lg bg-red-500/20 border border-red-500 text-red-100 text-sm font-semibold">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $errors->first('email') }}
            </div>
        @endif

        {{-- Trust Badge --}}
        <p class="text-sm text-gray-400 mt-8">
            <i class="fas fa-shield-alt text-red-600 mr-2"></i>
            We respect your privacy. Unsubscribe anytime.
        </p>
    </div>
</section>
