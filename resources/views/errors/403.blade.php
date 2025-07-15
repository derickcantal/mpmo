<x-app-layout>
<div class="gradient-bg min-h-screen flex items-center justify-center bg-gray-100 p-6">
  <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8 text-center">
     {{-- Logo --}}
    <div class="mb-6">
      <a href="{{ url('/') }}">
        <img src="{{ asset("storage/img/logo.png") }}" alt="{{ config('app.name') }} Logo" class="mx-auto h-16 w-auto">
      </a>
    </div>
    <h1 class="text-6xl font-extrabold text-red-600 mb-4">403</h1>
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Unauthorized</h2>
    <p class="text-gray-600 mb-6">
      Oops—you don’t have permission to access this page.
    </p>
    <a href="{{ url()->previous() }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
      ← Go Back
    </a>
    <a href="{{ route('dashboard') }}" class="inline-block ml-4 px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
      Home
    </a>
  </div>
</div>
</x-app-layout>
