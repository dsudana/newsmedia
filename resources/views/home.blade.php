@extends('layouts.app')

@section('title', 'RET NEWS - World News & Magazine')

@section('content')

    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        <section id="section-hero" class="py-4">
            @include('partials.hero')
        </section>

        <section id="section-category-strip" class="py-4">
            @include('partials.category-strip')
        </section>

        <section id="section-recent-popular" class="py-4">
            @include('partials.recent-and-popular')
        </section>

        <section id="section-sports" class="py-4">
            @include('partials.sports')
        </section>

        <section id="section-content" class="py-4">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">

                    {{-- Konten utama (75%) --}}
                    <div id="section-main" class="lg:col-span-3 space-y-8 min-w-0">

                        <section id="section-lifestyle">
                            @include('partials.lifestyle')
                        </section>

                        <section id="section-technology">
                            @include('partials.technology')
                        </section>

                        <section id="section-pagination">
                            @include('partials.pagination')
                        </section>

                    </div>

                    {{-- Sidebar (25%) --}}
                    <aside id="section-sidebar" class="lg:col-span-1 min-w-0">
                        @include('partials.sidebar')
                    </aside>

                </div>
            </div>
        </section>
    </div>

@endsection
