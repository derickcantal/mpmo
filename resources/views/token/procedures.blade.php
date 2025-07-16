{{-- resources/views/token/procedures.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
      Token Treasury Operations
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 1) Airdrop Form --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4 dark:text-gray-100">Airdrop Tokens</h3>

                <form action="{{ route('token.airdrop') }}" method="POST" class="space-y-4">
                @csrf

                <div id="airdrop-rows">
                    <div class="flex gap-2 mb-2">
                    <select name="user_ids[]" required
                            class="flex-1 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 px-4 py-2 focus:ring-pink-400 focus:border-pink-400">
                        <option value="">Select User</option>
                        @foreach($users as $u)
                        <option value="{{ $u->userid }}">{{ $u->name }} (ID:{{ $u->userid }})</option>
                        @endforeach
                    </select>

                    <input name="amounts[]" type="number" step="0.0001" placeholder="Amount" required
                            class="flex-1 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 px-4 py-2 focus:ring-pink-400 focus:border-pink-400"/>
                    </div>
                </div>

                <button type="button" onclick="addAirdropRow()"
                        class="text-sm text-pink-500 hover:underline">+ Add Recipient</button>

                <button type="submit"
                        class="mt-2 w-full bg-green-600 text-white py-2 rounded-full font-bold hover:bg-green-700 transition">
                    Run Airdrop
                </button>
                </form>
            </div>



      {{-- 2) Staking Rewards Form --}}
      <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium mb-4 dark:text-gray-100">Distribute Staking Rewards</h3>
        <form action="{{ route('token.distributeStakingRewards') }}" method="POST" class="space-y-4">
          @csrf
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Rewards (JSON: {"userId":amount, ...})</label>
          <textarea name="rewards" rows="4" required
            class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-pink-400 focus:border-pink-400"></textarea>
          @error('rewards')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
          <button type="submit" 
            class="mt-2 w-full bg-blue-600 text-white py-2 rounded-full font-bold hover:bg-blue-700 transition">
            Distribute Rewards
          </button>
        </form>
      </div>

      {{-- 3) Liquidity Incentive Form --}}
      <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium mb-4 dark:text-gray-100">Liquidity Incentives</h3>
        <form action="{{ route('token.incentivizeLiquidity') }}" method="POST" class="space-y-4">
          @csrf
          <div>
            <label for="pool_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Liquidity Pool ID</label>
            <input id="pool_id" name="pool_id" type="text" required
              class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-pink-400 focus:border-pink-400">
            @error('pool_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
          </div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Incentives (JSON: {"userId":amount, ...})</label>
          <textarea name="allocations" rows="4" required
            class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-pink-400 focus:border-pink-400"></textarea>
          @error('allocations')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
          <button type="submit"
            class="mt-2 w-full bg-purple-600 text-white py-2 rounded-full font-bold hover:bg-purple-700 transition">
            Incentivize Liquidity
          </button>
        </form>
      </div>


      
    </div>
    @push('scripts')
    <script>
    function addAirdropRow() {
        const container = document.getElementById('airdrop-rows');
        const row = document.createElement('div');
        row.className = 'flex gap-2 mb-2';
        row.innerHTML = `
        <select name="user_ids[]" required class="flex-1 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 px-4 py-2 focus:ring-pink-400 focus:border-pink-400">
            <option value="">Select User</option>
            @foreach($users as $u)
            <option value="{{ $u->userid }}">{{ $u->name }} (ID:{{ $u->userid }})</option>
            @endforeach
        </select>
        <input name="amounts[]" type="number" step="0.0001" placeholder="Amount" required
                class="flex-1 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 px-4 py-2 focus:ring-pink-400 focus:border-pink-400"/>`;
        container.appendChild(row);
    }
    </script>
    @endpush

  </div>
</x-app-layout>
