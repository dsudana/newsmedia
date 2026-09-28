@extends('layouts.app-modern')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white flex items-center justify-center px-4">
    <div class="max-w-md text-center">
        <!-- Error Code -->
        <div class="mb-6">
            <div class="text-9xl font-bold text-red-200 leading-none">500</div>
            <div class="text-4xl font-bold text-gray-900 mt-4">Server Error</div>
        </div>

        <!-- Description -->
        <p class="text-gray-600 text-lg mb-8 leading-relaxed">
            Oops! Something went wrong on our end. Our team has been notified and is working to fix the issue.
        </p>

        <!-- Actions -->
        <div class="space-y-3">
            <a href="/" class="inline-block w-full bg-indigo-600 text-white font-semibold py-3 rounded-lg hover:bg-indigo-700 transition transform hover:scale-105">
                <i class="fas fa-home mr-2"></i>Back to Home
            </a>

            <a href="{{ route('blog.index') }}" class="inline-block w-full bg-gray-200 text-gray-900 font-semibold py-3 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-newspaper mr-2"></i>Browse Articles
            </a>
        </div>

        <!-- Illustration -->
        <div class="mt-12 text-6xl text-red-300 opacity-50">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
    </div>
</div>
@endsection
