<x-app-layout>
    <x-slot name="header">Send TRX</x-slot>

    <div class="p-6 max-w-md mx-auto">
        @if ($errors->has('send_error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">{{ $errors->first('send_error') }}</div>
        @endif

        <form action="{{ route('wallet.send.post') }}" method="POST">
            @csrf 

                  
            <input type="hidden" name="address" value="{{ $address }}">

            <label class="block mb-1">Recipient Address</label>
            <input name="to_address" type="text" class="border rounded w-full p-2 mb-4" required>

            <label class="block mb-1">Amount (TRX)</label>
            <input name="amount" type="number" step="0.000001" class="border rounded w-full p-2 mb-4" required>

            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Send TRX</button>
        </form>

        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-center">Scan QR Code</h2>

        <!-- Textbox with icon -->
        <div class="relative mb-4">
            <input type="text" id="scanned-text" name="scanned_text" readonly placeholder="Scan a QR Code"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10">
            <button type="button" onclick="toggleScanner()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-blue-600">
                <!-- QR code icon from Heroicons -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h4v4H4V4zm0 12h4v4H4v-4zm12-12h4v4h-4V4zm0 12h4v4h-4v-4zM9 4h6v1H9V4zm0 5h1v6H9V9zm5 0h1v6h-1V9zm-5 6h6v1H9v-1z"/>
                </svg>
            </button>
        </div>

        <!-- Scanner area, hidden by default -->
        <div id="qr-reader-container" class="hidden mb-4 rounded-md border">
            <div id="qr-reader"></div>
            <button type="button" onclick="stopScanner()" class="mt-2 text-white bg-red-500 hover:bg-red-600 font-medium rounded-lg text-sm px-4 py-2">Close Scanner</button>
        </div>

        <!-- Success message -->
        <div id="message" class="text-sm text-green-600 font-medium text-center"></div>
    </div>

     <script>
        let html5QrcodeScanner;

        function toggleScanner() {
            const container = document.getElementById('qr-reader-container');

            if (container.classList.contains('hidden')) {
                container.classList.remove('hidden');
                startScanner();
            } else {
                stopScanner();
            }
        }

        function startScanner() {
            html5QrcodeScanner = new Html5Qrcode("qr-reader");

            html5QrcodeScanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                onScanSuccess,
                onScanError
            ).catch(err => console.error(err));
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner.clear();
                }).catch(err => console.error(err));
            }
            document.getElementById('qr-reader-container').classList.add('hidden');
        }

        function onScanSuccess(decodedText) {
            document.getElementById('scanned-text').value = decodedText;
            sendToServer(decodedText);
            stopScanner();  // Stop after successful scan
        }

        function onScanError(error) {
            console.warn(`Scan error: ${error}`);
        }

        function sendToServer(scannedText) {
            fetch('{{ route('qr.submit') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ scanned_text: scannedText })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('message').textContent = data.message || 'Scanned data submitted successfully!';
            })
            .catch(error => {
                console.error(error);
                document.getElementById('message').textContent = 'Error submitting scan';
            });
        }
    </script>
      
    </div>
</x-app-layout>
    
