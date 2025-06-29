<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased">
<div class="flex">
    @include('layouts.sidebar')

    <div class="ml-64 w-full min-h-screen bg-gray-50">
        @include('layouts.navbar')

        @if(isset($header))
            <div class="px-6 pt-6 pb-4">
                <div class="bg-white rounded-lg px-6 py-4 shadow-sm border">
                    {!! $header !!}
                </div>
            </div>
        @endif

        <main class="p-6">
            {{ $slot }}
        </main>
    </div>

</div>

<footer class="mt-8 border-t border-gray-200 py-4 bg-white">
    <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500">
        <p class="mb-2">&copy; {{ date('Y') }} NaturaCorp — Tous droits réservés.</p>
        <a href="{{ route('confidentialite') }}" class="text-gray-600 hover:text-gray-800 hover:underline transition">
            Politique de confidentialité (RGPD)
        </a>
    </div>
</footer>

@stack('scripts')
</body>
</html>
