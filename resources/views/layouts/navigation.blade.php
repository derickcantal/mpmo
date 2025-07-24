{{-- resources/views/layouts/navigation.blade.php --}}
<nav class="fixed top-0 inset-x-0 z-30 bg-gray-800 border-b border-gray-700 px-4 lg:px-6 py-2.5 text-gray-200">
  <div class="max-w-7xl mx-auto flex items-center">
    <button id="sidebarToggle" class="md:hidden p-2 mr-3 hover:bg-gray-700 rounded-lg focus:outline-none">
      <!-- hamburger icon -->
    </button>
    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
      <img src="{{ asset('storage/img/logo.png') }}"
           class="h-8 w-8 rounded-full"
           alt="MPMO Logo"/>
      <span class="text-2xl font-semibold text-indigo-400">MPMO</span>
    </a>
    <div class="ml-auto flex items-center space-x-4">
      @include('layouts.nav-apps-dropdown')
      @include('layouts.nav-user-dropdown')
    </div>
  </div>
</nav>

{{-- Sidebar (desktop & drawer) --}}
<aside id="sidebar" class="fixed top-14 bottom-0 left-0 z-20 w-64 transform -translate-x-full md:translate-x-0 transition-transform bg-gray-800 border-r border-gray-700 p-6 flex flex-col">
  <h2 class="text-2xl font-extrabold text-indigo-400 mb-6">Admin Panel</h2>

  <nav class="flex-1 space-y-2">
    <a href="{{ route('dashboard') }}"
       class="flex items-center px-3 py-2 rounded-lg hover:bg-indigo-500 hover:text-white transition">
      <x-heroicon-o-home class="w-5 h-5 mr-2"/> Dashboard
    </a>
    <a href="{{ route('manageuser.index') }}"
       class="flex items-center px-3 py-2 rounded-lg hover:bg-indigo-500 hover:text-white transition">
      <x-heroicon-o-users class="w-5 h-5 mr-2"/> Users
    </a>
    <a href="{{ route('managetxn.index') }}"
       class="flex items-center px-3 py-2 rounded-lg hover:bg-indigo-500 hover:text-white transition">
      <x-heroicon-o-currency-dollar class="w-5 h-5 mr-2"/> Transactions
    </a>
    <a href="{{ route('dashboard') }}"
       class="flex items-center px-3 py-2 rounded-lg hover:bg-indigo-500 hover:text-white transition">
      <x-heroicon-o-cog class="w-5 h-5 mr-2"/> Settings
    </a>
  </nav>

  <div class="mt-6">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit"
              class="flex items-center w-full px-4 py-2 bg-red-600 rounded-lg hover:bg-red-500 transition">
        <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5 mr-2"/> Log Out
      </button>
    </form>
  </div>
</aside>

{{-- Overlay for mobile when sidebar is open --}}
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden md:hidden"></div>

{{-- Sidebar toggle script --}}
@push('scripts')
<script>
  const sidebar   = document.getElementById('sidebar');
  const overlay   = document.getElementById('overlay');
  const toggleBtn = document.getElementById('sidebarToggle');

  function openSidebar() {
    sidebar.classList.remove('-translate-x-full');
    overlay.classList.remove('hidden');
  }
  function closeSidebar() {
    sidebar.classList.add('-translate-x-full');
    overlay.classList.add('hidden');
  }

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar();
  });
  overlay.addEventListener('click', closeSidebar);
</script>
@endpush