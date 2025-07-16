<x-guest-layout>
    <!-- Page Content -->
    <div class="gradient-bg min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8">
            <div class="text-center mb-6">
                <img src="{{ asset("storage/img/logo.png") }}" alt="MPMO Logo" class="mx-auto h-16 w-16 mb-2">
                <h1 class="text-3xl font-bold text-pink-500">Create Your Account</h1>
                <p class="text-gray-600 mt-1">Join the MPMO adventure!</p>
            </div>
            @include('layouts.notifications') 
            <!-- If you want the user to see where they came from: -->
            @if($referrerId)
                @php $referrer = \App\Models\User::find($referrerId); @endphp
                <p class="text-sm text-gray-600">
                    You were referred by {{ $referrer->name }} ({{ $referrer->referral_code }})
                </p>
            @endif
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <!-- show it to the user (readonly) -->
                @if(session('referrer_code'))
                <div class="mb-4 text-sm text-gray-700">
                    You were referred by <strong>{{ session('referrer_code') }}</strong>
                </div>
                @endif

                <!-- …and a hidden field for the DB: -->
                <input
                type="hidden"
                name="referred_by"
                value="{{ old('referred_by', session('referrer_id')) }}"
                />
                <!-- Name -->
                <div>
                    <label for="referral_code" class="block text-gray-700 font-semibold">Referral Code</label>
                    <input id="referral_code" name="referral_code" type="text" value="{{ old('referrer_code', session('referrer_code')) }}" required readonly
                           class="mt-1 block w-full px-4 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400" />
                    @error('referral_code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <!-- Name -->
                <div>
                    <label for="username" class="block text-gray-700 font-semibold">Username</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus
                           class="mt-1 block w-full px-4 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400" />
                    @error('username')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <!-- Name -->
                <div>
                    <label for="fullname" class="block text-gray-700 font-semibold">Full Name</label>
                    <input id="fullname" name="fullname" type="text" value="{{ old('fullname') }}" required autofocus
                           class="mt-1 block w-full px-4 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400" />
                    @error('fullname')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-gray-700 font-semibold">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
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

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-gray-700 font-semibold">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="mt-1 block w-full px-4 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400" />
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-pink-500 text-white py-3 rounded-full font-bold hover:bg-pink-600 transition">
                        Register
                    </button>
                </div>
            </form>

            <p class="mt-6 text-center text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-pink-500 font-semibold hover:underline">Login here</a>
            </p>
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
    </div>
</x-guest-layout>

