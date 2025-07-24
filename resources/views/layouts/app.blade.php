<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'My Pocket Monster') }}</title>

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;700&display=swap" rel="stylesheet">
  
        <style>
            body { font-family: 'Baloo 2', cursive; }
            .gradient-bg { background: linear-gradient(135deg, #FFFB7D 0%, #FF7C7C 100%); }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-900 text-gray-200">

        {{-- 1) Fixed navbar --}}
        @include('layouts.navigation')

        {{-- 2) Content wrapper: pushes everything below the 4rem‑high nav, and over the sidebar on md+ --}}
        <div class="flex pt-16">

            {{-- Main content --}}
            <main class="flex-1 px-6 pt-6">
                <div class="max-w-7xl mx-auto">
                    {{-- Optional page header slot --}}
                    @isset($header)
                        <header class="mb-6">
                            <div class="text-2xl font-bold text-indigo-300 dark:text-indigo-200">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    {{-- Page slot --}}
                    {{ $slot }}
                </div>
            </main>
        </div>

        @stack('scripts')
    </body>
</html>
