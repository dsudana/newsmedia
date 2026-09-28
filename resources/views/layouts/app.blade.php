<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NewsMedia - Berita & Majalah')</title>

    <!-- SEO Meta Tags -->
    @if(isset($article))
        {!! \App\Helpers\SeoHelper::renderMetaTags(null, $article) !!}
    @elseif(isset($pageName))
        {!! \App\Helpers\SeoHelper::renderMetaTags($pageName) !!}
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" integrity="sha384-gAPqlBuTCdtVcYt9ocMOYWrnBZ4XSL6q+4eXqwNycOr4iFczhNKtnYhF3NEXJM51" crossorigin="anonymous">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-white text-rn-body font-sans antialiased">

    <header id="site-header">
        @include('partials.topbar')
        @include('partials.header')
        @include('partials.breaking-strip')
    </header>

    <main id="main-content">
        @yield('content')
    </main>

    <footer id="site-footer">
        @include('partials.footer')
    </footer>

    {{-- Back-to-top button (kept as plain Blade/Alpine; only the hero slider is a Next.js island) --}}
    <button
        id="back-to-top"
        x-data
        x-show="window.scrollY > 400"
        @click="window.scrollTo({top:0, behavior:'smooth'})"
        class="fixed bottom-6 right-6 bg-rn-red text-white w-10 h-10 rounded flex items-center justify-center shadow-lg"
        aria-label="Back to top">
        ↑
    </button>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" integrity="sha384-2UI1PfnXFjVMQ7/ZDEF70CR943oH3v6uZrFQGGqJYlvhh4g6z6uVktxYbOlAczav" crossorigin="anonymous"></script>
    @vite(['resources/js/app.js'])
</body>
</html>
