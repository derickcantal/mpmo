{{-- resources/views/layouts/nav-apps-dropdown.blade.php --}}

{{-- Apps dropdown trigger --}}
<div class="relative inline-block text-left">
    <button id="appsToggle" class="relative p-2 rounded-lg hover:bg-gray-700 focus:outline-none">
        <svg class="w-6 h-6 text-gray-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h6v6H4V4zm0 10h6v6H4v-6zm10-10h6v6h-6V4zm0 10h6v6h-6v-6z" />
        </svg>
    </button>

    {{-- Apps dropdown menu --}}

    <div id="appsDropdown" class="hidden origin-top-right absolute right-0 top-full mt-2 w-56 bg-gray-700 rounded-lg shadow-lg z-30">
        <div class="px-4 py-2 text-gray-200 font-semibold border-b border-gray-600">Apps</div>
            <div class="grid grid-cols-3 gap-2 p-3">
                @if(auth()->user()->accesstype === 'super-admin' || auth()->user()->accesstype === 'Administrator')
                    <a href="{{ route('manageuser.index') }}" class="flex flex-col items-center p-2 hover:bg-gray-600 rounded">
                        <x-heroicon-o-users class="w-5 h-5 text-indigo-400 mb-1" />
                        <span class="text-xs text-gray-200">Users</span>
                    </a>
                    <a href="{{ route('managetempusers.index') }}" class="flex flex-col items-center p-2 hover:bg-gray-600 rounded">
                        <x-heroicon-o-user-group class="w-5 h-5 text-indigo-400 mb-1" />
                        <span class="text-xs text-gray-200">T-Users</span>
                    </a>
                @endif
                @if(auth()->user()->accesstype === 'super-admin')
                    <a href="{{ route('managewallet.index') }}" class="flex flex-col items-center p-2 hover:bg-gray-600 rounded">
                        <x-heroicon-o-qr-code class="w-5 h-5 text-indigo-400 mb-1" />
                        <span class="text-xs text-gray-200">QR</span>
                    </a>
                @endif
                <a href="{{ route('managemyprofile.index') }}" class="flex flex-col items-center p-2 hover:bg-gray-600 rounded">
                    <x-heroicon-o-user class="w-5 h-5 text-indigo-400 mb-1" />
                    <span class="text-xs text-gray-200">Profile</span>
                </a>
                <a href="{{ route('managemyprofile.signature') }}" class="flex flex-col items-center p-2 hover:bg-gray-600 rounded">
                    <x-heroicon-o-document-text class="w-5 h-5 text-indigo-400 mb-1" />
                    <span class="text-xs text-gray-200">Wallet</span>
                </a>
                <a href="{{ route('managetxn.index') }}" class="flex flex-col items-center p-2 hover:bg-gray-600 rounded">
                    <x-heroicon-o-receipt-refund class="w-5 h-5 text-indigo-400 mb-1" />
                    <span class="text-xs text-gray-200">Transactions</span>
                </a>
                @if(auth()->user()->accesstype === 'super-admin')
                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center p-2 hover:bg-gray-600 rounded">
                        <x-heroicon-o-cog class="w-5 h-5 text-indigo-400 mb-1" />
                        <span class="text-xs text-gray-200">Settings</span>
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="col-span-3">
                    @csrf
                    <button type="submit" class="flex w-full items-center justify-center p-2 hover:bg-gray-600 rounded">
                        <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5 text-indigo-400 mr-1" />
                        <span class="text-xs text-gray-200">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Dropdown toggle script --}}
@push('scripts')
<script>
    document.getElementById('appsToggle').addEventListener('click', () => {
        document.getElementById('appsDropdown').classList.toggle('hidden');
    });
</script>
@endpush