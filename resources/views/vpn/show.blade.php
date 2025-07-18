<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-700">Configuration</h3>
                        <pre class="mt-2 p-4 bg-gray-50 rounded-md text-sm text-gray-800 overflow-x-auto">{{ $conf }}</pre>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-700">QR Code</h3>
                        <div class="mt-2">
                            <img src="{{ $qrDataUri }}" alt="QR code">
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('vpn.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300 transition">
                            Back to list
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>