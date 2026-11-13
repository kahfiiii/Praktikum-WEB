<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#5568ee">
    <title>@yield('title', 'STUDORA') — Belajar bareng anak UB</title>
    {{-- Aset lokal: font self-hosted, tanpa CDN --}}
    <link rel="preload" href="/fonts/PlusJakartaSans-800.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="/fonts/DMSans-400.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="/fonts.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-canvas-warm">
    @yield('body')
</body>
</html>
