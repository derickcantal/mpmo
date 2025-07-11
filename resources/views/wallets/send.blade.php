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

        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
            <div class="relative mb-4">
                <input type="text" id="scanned-text"
                    class="block w-full pr-10 p-2.5 border rounded-lg"
                    placeholder="Scan QR code here">
                <button type="button" onclick="openScanner()" class="absolute inset-y-0 right-0 pr-3 text-gray-500 hover:text-blue-600">
                    <!-- QR icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h4v4H4V4zm0 12h4v4H4v-4zm12-12h4v4h-4V4zm0 12h4v4h-4v-4zM9 4h6v1H9V4zm0 5h1v6H9V9zm5 0h1v6h-1V9zm-5 6h6v1H9v-1z"/>
                    </svg>
                </button>
            </div>

            <!-- QR Scanner hidden by default -->
            <div id="qr-reader-container" class="hidden mb-4">
                <div id="qr-reader" style="width: 100%; height: 300px;"></div>
                <button type="button" onclick="closeScanner()" class="mt-2 bg-red-500 text-white px-4 py-2 rounded">Close Scanner</button>
            </div>

            <div id="message" class="text-green-600 text-sm"></div>
        </div>
    </div>
    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script>
            let html5Qrcode;
            const qrRegionId = "qr-reader";

            async function openScanner() {
                const container = document.getElementById('qr-reader-container');
                container.classList.remove('hidden');

                if (!html5Qrcode) {
                    html5Qrcode = new Html5Qrcode(qrRegionId);
                }

                try {
                    await html5Qrcode.start(
                        { facingMode: "environment" }, // Use back camera on phones
                        {
                            fps: 10,
                            qrbox: { width: 250, height: 250 }
                        },
                        qrCodeSuccessCallback,
                        qrCodeErrorCallback
                    );
                } catch (err) {
                    console.error("Camera start failed:", err);
                    document.getElementById('message').textContent = 'Failed to start camera: ' + err;
                }
            }

            async function closeScanner() {
                if (html5Qrcode) {
                    try {
                        await html5Qrcode.stop();
                        await html5Qrcode.clear();
                    } catch (err) {
                        console.error("Error stopping scanner:", err);
                    }
                }
                document.getElementById('qr-reader-container').classList.add('hidden');
            }

            function qrCodeSuccessCallback(decodedText) {
                document.getElementById('scanned-text').value = decodedText;
                sendToServer(decodedText);
                closeScanner();
            }

            function qrCodeErrorCallback(errorMessage) {
                // Comment this out for less console noise
                console.warn(errorMessage);
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
                    document.getElementById('message').textContent = data.message || 'Scan submitted successfully.';
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('message').textContent = 'Error submitting scan.';
                });
            }
        </script>
    @endpush
</x-app-layout>
    
