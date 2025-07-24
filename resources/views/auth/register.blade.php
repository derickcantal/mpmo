<x-guest-layout>
  <div class="min-h-screen bg-gray-900 text-gray-200 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-gray-800 rounded-2xl shadow-2xl p-8 space-y-6">
      {{-- Logo & Heading --}}
      <div class="text-center">
        <img src="{{ asset('storage/img/logo.png') }}" alt="MPMO Logo" class="mx-auto h-16 w-16 mb-4">
        <h2 class="text-3xl font-extrabold text-indigo-300">Create Your Account</h2>
        <p class="text-gray-400 mt-1">Join the MPMO adventure!</p>
      </div>

      @include('layouts.notifications')

      @if($referrerId)
        @php $referrer = \App\Models\User::find($referrerId); @endphp
        <p class="text-sm text-gray-400">
          Referred by {{ $referrer->fullname }} ({{ $referrer->referral_code }})
        </p>
      @endif

      <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf
        @if(session('referrer_code'))
          <div class="text-sm text-gray-400">
            You were referred by <strong class="text-indigo-300">{{ session('referrer_code') }}</strong>
          </div>
        @endif
        <input type="hidden" name="referred_by" value="{{ old('referred_by', session('referrer_id')) }}"/>

        <div>
          <label for="referral_code" class="block text-gray-200 font-semibold">Referral Code</label>
          <input id="referral_code" name="referral_code" type="text"
                 value="{{ old('referrer_code', session('referrer_code')) }}" readonly
                 class="mt-1 w-full px-4 py-2 bg-gray-700 text-gray-200 rounded-lg border border-gray-600
                        focus:outline-none focus:ring-2 focus:ring-indigo-300"/>
          @error('referral_code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label for="username" class="block text-gray-200 font-semibold">Username</label>
          <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus
                 class="mt-1 w-full px-4 py-2 bg-gray-700 text-gray-200 rounded-lg border border-gray-600
                        focus:outline-none focus:ring-2 focus:ring-indigo-300"/>
          @error('username')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label for="fullname" class="block text-gray-200 font-semibold">Full Name</label>
          <input id="fullname" name="fullname" type="text" value="{{ old('fullname') }}" required
                 class="mt-1 w-full px-4 py-2 bg-gray-700 text-gray-200 rounded-lg border border-gray-600
                        focus:outline-none focus:ring-2 focus:ring-indigo-300"/>
          @error('fullname')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label for="email" class="block text-gray-200 font-semibold">Email</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" required
                 class="mt-1 w-full px-4 py-2 bg-gray-700 text-gray-200 rounded-lg border border-gray-600
                        focus:outline-none focus:ring-2 focus:ring-indigo-300"/>
          @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label for="password" class="block text-gray-200 font-semibold">Password</label>
          <input id="password" name="password" type="password" required
                 class="mt-1 w-full px-4 py-2 bg-gray-700 text-gray-200 rounded-lg border border-gray-600
                        focus:outline-none focus:ring-2 focus:ring-indigo-300"/>
          @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label for="password_confirmation" class="block text-gray-200 font-semibold">Confirm Password</label>
          <input id="password_confirmation" name="password_confirmation" type="password" required
                 class="mt-1 w-full px-4 py-2 bg-gray-700 text-gray-200 rounded-lg border border-gray-600
                        focus:outline-none focus:ring-2 focus:ring-indigo-300"/>
        </div>

        <div>
          <x-button type="submit" class="w-full flex justify-center py-2 px-4">
            Register
          </x-button>
        </div>
      </form>

      <p class="mt-6 text-center text-gray-400 text-sm">
        Already have an account?
        <a href="{{ route('login') }}" class="text-indigo-300 font-semibold hover:underline ml-1">
          Log in
        </a>
      </p>
    </div>
  </div>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(window.location.search);
        if (params.has('ref')) {
          const ref = params.get('ref');
          const input = document.querySelector('input[name="referred_by"]');
          if (input) input.value = ref;
        }
      });
    </script>
  @endpush
</x-guest-layout>