<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="min-h-screen bg-gray-900 text-gray-200 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-gray-800 rounded-2xl shadow-xl p-8 space-y-6">
      {{-- Logo & Heading --}}
      <div class="text-center">
        <img src="{{ asset('storage/img/logo.png') }}" alt="MPMO Logo" class="mx-auto w-24 max-w-full h-auto md:w-32 lg:w-40">
        <h2 class="text-3xl font-extrabold text-indigo-300">Sign in to your account</h2>
      </div>

      {{-- Form --}}
      <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
        @csrf

        <div class="space-y-4">
          <div>
            <label for="email" class="sr-only">Email address</label>
            <input id="email" name="email" type="email" autocomplete="email" required
                   class="w-full px-3 py-2 bg-gray-700 text-gray-200 placeholder-gray-400 border border-gray-600 rounded-lg
                          focus:outline-none focus:ring-indigo-300 focus:border-indigo-300"
                   placeholder="Email address">
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required
                   class="w-full px-3 py-2 bg-gray-700 text-gray-200 placeholder-gray-400 border border-gray-600 rounded-lg
                          focus:outline-none focus:ring-indigo-300 focus:border-indigo-300"
                   placeholder="Password">
          </div>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input id="remember_me" name="remember" type="checkbox"
                   class="h-4 w-4 text-indigo-500 focus:ring-indigo-400 border-gray-600 rounded">
            <label for="remember_me" class="ml-2 text-sm text-gray-200">Remember me</label>
          </div>

          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}"
               class="text-sm font-medium text-indigo-300 hover:text-indigo-400">
              Forgot your password?
            </a>
          @endif
        </div>

        <div>
          <button type="submit"
                  class="w-full flex justify-center py-2 px-4 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold
                         rounded-full shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-300">
            Sign In
          </button>
        </div>
      </form>

      <p class="mt-6 text-center text-sm text-gray-400">
        Don’t have an account?
        <a href="{{ route('register') }}" class="font-medium text-pink-500 hover:text-pink-600 ml-1">
          Register
        </a>
      </p>
    </div>
  </div>
</x-guest-layout>
