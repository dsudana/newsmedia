@extends('layouts.app-modern')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <article class="prose prose-lg max-w-none">
        <h1 class="text-4xl font-bold text-gray-900 mb-6">{{ $page->title }}</h1>
        
        <div class="text-gray-600 mb-8 pb-8 border-b border-gray-200">
            <p class="text-sm">Terakhir diperbarui: {{ $page->updated_at->format('d F Y') }}</p>
        </div>

        <div class="text-gray-700 leading-relaxed">
            {!! $page->content !!}
        </div>
    </article>

    <div class="mt-12 pt-8 border-t border-gray-200">
        <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda
        </a>
    </div>
</div>
@endsection

