{{-- resources/views/layouts/nav-user-dropdown.blade.php --}}

{{-- User dropdown menu --}}
<div class="relative inline-block text-left">
     <button id="userMenuButton" class="flex items-center p-1 rounded-full hover:bg-gray-700 focus:outline-none">
        <img src="{{ asset('storage/' . (auth()->user()->avatar ?? 'img/avatar-default.png')) }}"
             class="h-8 w-8 rounded-full" alt="User"/>
      </button>
    <div id="userDropdown" class="hidden origin-top-right absolute right-0 top-full mt-2 w-48 bg-gray-700 rounded-lg shadow-lg z-30">
        <div class="px-4 py-2 text-gray-200 border-b border-gray-600">
            <span class="block font-semibold">{{ auth()->user()->fullname }}</span>
            <span class="block text-sm text-gray-400 truncate">{{ auth()->user()->email }}</span>
        </div>
        <ul class="py-1 text-gray-200">
            <li>
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-600 rounded">
                    My Profiles
                </a>
            </li>
            <li>
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-600 rounded">
                    Account Settings
                </a>
            </li>
        </ul>
        <div class="border-t border-gray-600"></div>
        <ul class="py-1 text-gray-200">
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-600 rounded">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

{{-- Dropdown toggle script --}}
@push('scripts')
<script>
    document.getElementById('userMenuButton').addEventListener('click', () => {
        document.getElementById('userDropdown').classList.toggle('hidden');
    });
</script>
@endpush