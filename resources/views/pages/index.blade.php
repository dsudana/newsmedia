@extends('layouts.app-modern')

@section('title', 'Pages')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold mb-6">Pages</h1>
    <p class="text-gray-600 mb-8">Pages directory coming soon.</p>
    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">← Back to Home</a>
</div>
@endsection
