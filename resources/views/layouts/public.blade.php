<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Inicio')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-BXCDZ13YXK"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-BXCDZ13YXK');
</script>
</head>
<body class="bg-white text-gray-800 antialiased">

    {{-- Navbar --}}
    @include('partials.public.navbar')

    {{-- Contenido principal --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.public.footer')

    @stack('scripts')
    @fluxScripts
</body>
</html>