<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RET NEWS - Latest News & Stories')</title>
    <meta name="description" content="@yield('description', 'Get the latest news and stories from around the world')">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

    @yield('extra_head')
</head>
<body class="bg-white">
    <!-- Top Bar -->
    @include('layouts.partials.topbar')

    <!-- Header -->
    @include('layouts.partials.header-modern')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.partials.footer-modern')

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @yield('extra_scripts')
</body>
</html>
