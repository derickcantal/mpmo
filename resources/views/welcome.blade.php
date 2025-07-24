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
    {{-- Top Navigation --}}
    <header class="bg-gray-800 text-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <div class="flex items-center">
                    <img src="{{ asset('storage/img/logo.png') }}" alt="MPMO" class="h-8 w-8 mr-2">
                    <span class="text-xl font-bold text-indigo-400">MPMO</span>
                </div>

                {{-- Desktop Links --}}
                <nav class="hidden md:flex space-x-6">
                    <a href="#presale" class="hover:text-white">Presale</a>
                    <a href="#eggs"    class="hover:text-white">Eggs</a>
                    <a href="#roadmap" class="hover:text-white">Roadmap</a>
                    @guest
                        <a href="{{ route('login') }}"    class="hover:text-white">Login</a>
                        <a href="{{ route('register') }}" class="hover:text-white">Register</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="hover:text-white">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-white">Logout</button>
                        </form>
                    @endguest
                </nav>

                {{-- Mobile Hamburger --}}
                <button id="mobile-menu-button" class="md:hidden p-2 focus:outline-none">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="md:hidden hidden bg-gray-700">
            <a href="#presale" class="block px-4 py-2 hover:bg-gray-600">Presale</a>
            <a href="#eggs"    class="block px-4 py-2 hover:bg-gray-600">Eggs</a>
            <a href="#roadmap" class="block px-4 py-2 hover:bg-gray-600">Roadmap</a>
            @guest
                <a href="{{ route('login') }}"    class="block px-4 py-2 hover:bg-gray-600">Login</a>
                <a href="{{ route('register') }}" class="block px-4 py-2 hover:bg-gray-600">Register</a>
            @else
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-600">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-600">Logout</button>
                </form>
            @endguest
        </div>

        {{-- Toggle Script --}}
        <script>
            const btn  = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');
            btn.addEventListener('click', () => menu.classList.toggle('hidden'));
        </script>
    </header>

    {{-- Main Content --}}
    <main class="bg-gray-900 text-gray-200 flex-1 p-6">
        {{-- Hero Section --}}
        <section id="presale" class="flex flex-col items-center justify-center text-center py-16">
            <h1 class="text-5xl md:text-6xl font-extrabold text-indigo-300 mb-4 animate-pulse">
                Hatch Your Adventure!
            </h1>
            <p class="text-lg md:text-xl text-gray-300 mb-8 max-w-2xl">
                Join the <span class="font-semibold text-pink-400">MPMO Token Presale</span> and collect
                adorable monster eggs on Tron —<br>1 TRX = 3 MPMO.
            </p>
            @guest
                <a href="{{ route('register') }}"
                   class="px-8 py-4 bg-pink-500 text-white font-bold rounded-full shadow-lg hover:bg-pink-400 transition">
                    Get Started
                </a>
            @else
                <a href="{{ route('dashboard') }}"
                   class="px-8 py-4 bg-indigo-500 text-white font-bold rounded-full shadow-lg hover:bg-indigo-400 transition">
                    Go to Dashboard
                </a>
            @endguest
        </section>

        {{-- Feature Cards --}}
        <section id="eggs" class="mb-16">
            <h2 class="text-3xl font-bold text-indigo-300 mb-6 text-center">Egg Marketplace</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4">
                @foreach ([
                    ['title'=>'Common Egg','price'=>'100 TRX','mpmo'=>'300 MPMO','color'=>'text-pink-400','rates'=>['70% Common','25% Rare','5% Elite']],
                    ['title'=>'Rare Egg','price'=>'266.7 TRX','mpmo'=>'800 MPMO','color'=>'text-yellow-400','rates'=>['50% Rare','40% Common','10% Elite']],
                    ['title'=>'Elite Egg','price'=>'533.4 TRX','mpmo'=>'1600 MPMO','color'=>'text-purple-400','rates'=>['50% Elite','40% Rare','10% Epic']],
                ] as $egg)
                    <div class="bg-gray-800 rounded-2xl shadow-xl border-2 border-transparent hover:border-pink-500 transition p-6 text-center">
                        <h3 class="text-2xl font-bold {{ $egg['color'] }} mb-2">
                            {{ $egg['title'] }}
                        </h3>
                        <p class="text-gray-300 mb-4">{{ $egg['mpmo'] }} ({{ $egg['price'] }})</p>
                        <ul class="text-left text-gray-400 mb-6 space-y-1">
                            @foreach($egg['rates'] as $rate)
                                <li>• {{ $rate }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('register') }}"
                           class="px-4 py-2 bg-pink-500 text-white rounded-full hover:bg-pink-400 transition">
                            Buy Now
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Roadmap --}}
        <section id="roadmap" class="mb-16 text-center">
            <h2 class="text-3xl font-bold text-indigo-300 mb-6">Roadmap</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 px-4">
                @foreach ([
                    ['quarter'=>'Q4 2025','color'=>'text-pink-400','desc'=>'Tron presale, testnet launch, egg mint beta.'],
                    ['quarter'=>'Q1 2026','color'=>'text-yellow-400','desc'=>'Mainnet launch, staking dashboard.'],
                    ['quarter'=>'Q2 2026+','color'=>'text-purple-400','desc'=>'More features. To be announced.'],
                ] as $phase)
                    <div class="bg-gray-800/80 rounded-2xl p-6 shadow-xl">
                        <h3 class="text-2xl font-semibold {{ $phase['color'] }} mb-2">
                            {{ $phase['quarter'] }}
                        </h3>
                        <p class="text-gray-300">{{ $phase['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Footer --}}
        <footer class="py-6 text-center bg-gray-800 border-t border-gray-700">
            <p class="text-gray-500 text-sm">© 2025 MPMO Token. All rights reserved.</p>
        </footer>
    </main>
</html>
