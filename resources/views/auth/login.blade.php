<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="gradient-bg min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8">
            <div class="text-center mb-6">
                <img src="{{ asset("storage/img/logo.png") }}" alt="MPMO Logo" class="mx-auto h-16 w-16 mb-2">
                <h1 class="text-3xl font-bold text-pink-500">Welcome Back</h1>
                <p class="text-gray-600 mt-1">Login to continue your MPMO journey!</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-gray-700 font-semibold">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                           class="mt-1 block w-full px-4 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400" />
                    @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-gray-700 font-semibold">Password</label>
                    <input id="password" name="password" type="password" required
                           class="mt-1 block w-full px-4 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400" />
                    @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                           class="h-4 w-4 text-pink-600 focus:ring-pink-400 border-gray-300 rounded" />
                    <label for="remember_me" class="ml-2 block text-gray-700">Remember me</label>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-pink-500 text-white py-3 rounded-full font-bold hover:bg-pink-600 transition">
                        Login
                    </button>
                </div>
            </form>

            <p class="mt-6 text-center text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-pink-500 font-semibold hover:underline">Register here</a>
            </p>
        </div>
    </div>
</x-guest-layout>
