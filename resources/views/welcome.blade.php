<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>My Pocket Monster</title>

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;700&display=swap" rel="stylesheet">
         <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Baloo 2', cursive; }
            .gradient-bg { background: linear-gradient(135deg, #FFFB7D 0%, #FF7C7C 100%); }
        </style>
       
    </head>
    <body class="gradient-bg min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="px-6 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset("storage/img/logo.png") }}" alt="MPMO Logo" class="h-12 w-12 mr-2">
                <span class="text-2xl text-white font-bold">MPMO</span>
            </div>
            <div class="space-x-4">
                <a href="#presale" class="text-white font-semibold hover:underline">Presale</a>
                <a href="#eggs" class="text-white font-semibold hover:underline">Eggs</a>
                <a href="#roadmap" class="text-white font-semibold hover:underline">Roadmap</a>
                @guest
                    <a href="{{ route('login') }}" class="text-white font-semibold hover:underline">Login</a>
                    <a href="{{ route('register') }}" class="text-white font-semibold hover:underline">Register</a>
                @else
                    <a href="{{ route('dashboard') }}" class="text-white font-semibold hover:underline">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-white font-semibold hover:underline">Logout</button>
                    </form>
                @endguest
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="flex-grow flex flex-col justify-center items-center text-center p-4">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-4 animate-bounce">Hatch Your Adventure!</h1>
            <p class="text-lg md:text-xl text-white/90 mb-8 max-w-2xl">
                Join the <span class="font-semibold">MPMO Token Presale</span> and collect adorable monster eggs on Tron—1 TRX = 3 MPMO.
            </p>
            @guest
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-pink-500 font-bold rounded-full shadow-lg hover:bg-pink-100 transition">
                    Get Started
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-white text-pink-500 font-bold rounded-full shadow-lg hover:bg-pink-100 transition">
                    Go to Dashboard
                </a>
            @endguest
        </section>

        <!-- Feature Cards -->
        <section id="eggs" class="py-12 bg-white/80">
            <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Common Egg -->
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center hover:scale-105 transition">
                    <h3 class="text-2xl font-bold text-pink-500 mb-2">Common Egg</h3>
                    <p class="text-gray-600 mb-4">100 MPMO (33.3 TRX)</p>
                    <ul class="text-left text-gray-700 mb-6 space-y-1">
                        <li>✅ 70% Common</li>
                        <li>✨ 25% Rare</li>
                        <li>🌟 5% Epic</li>
                    </ul>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-pink-500 text-white rounded-full">Buy Now</a>
                </div>
                <!-- Rare Egg -->
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center hover:scale-105 transition">
                    <h3 class="text-2xl font-bold text-yellow-500 mb-2">Rare Egg</h3>
                    <p class="text-gray-600 mb-4">300 MPMO (100 TRX)</p>
                    <ul class="text-left text-gray-700 mb-6 space-y-1">
                        <li>✨ 50% Rare</li>
                        <li>🌟 40% Epic</li>
                        <li>💎 10% Legendary</li>
                    </ul>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-yellow-500 text-white rounded-full">Buy Now</a>
                </div>
                <!-- Legendary Egg -->
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center hover:scale-105 transition">
                    <h3 class="text-2xl font-bold text-purple-500 mb-2">Legendary Egg</h3>
                    <p class="text-gray-600 mb-4">800 MPMO (266.7 TRX)</p>
                    <ul class="text-left text-gray-700 mb-6 space-y-1">
                        <li>🌟 Guaranteed Epic</li>
                        <li>💎 Legendary Odds</li>
                    </ul>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-purple-500 text-white rounded-full">Buy Now</a>
                </div>
            </div>
        </section>

        <!-- Roadmap -->
        <section id="roadmap" class="py-12 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Roadmap</h2>
            <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-4">
                <div class="bg-white/90 rounded-2xl p-6 shadow">
                    <h3 class="text-2xl font-semibold text-pink-600 mb-2">Q4 2025</h3>
                    <p>Tron presale, testnet launch, egg mint beta.</p>
                </div>
                <div class="bg-white/90 rounded-2xl p-6 shadow">
                    <h3 class="text-2xl font-semibold text-yellow-600 mb-2">Q1 2026</h3>
                    <p>Mainnet launch, staking dashboard.</p>
                </div>
                <div class="bg-white/90 rounded-2xl p-6 shadow">
                    <h3 class="text-2xl font-semibold text-purple-600 mb-2">Q2 2026+</h3>
                    <p>More features. To be announced.</p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-6 text-center bg-white/80">
            <p class="text-gray-700">© 2025 MPMO Token. All rights reserved.</p>
        </footer>

        <!-- Vite Scripts -->
        @vite(['resources/js/app.js'])
    </body>
    
</html>
