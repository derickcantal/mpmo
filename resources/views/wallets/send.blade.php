<x-app-layout>
    @include('layouts.home.navigation')
<div class="mx-auto sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg py-8">
        <div class="mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex px-5 py-3 text-gray-700 bg-transparent dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                    <a href="{{ route('wallet.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Manage
                    </a>
                    </li>
                    <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                            Send</span>
                    </div>
                    </li>
                    <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                            {{ $address }}</span>
                    </div>
                    </li>
                </ol>
            </nav>
            @include('layouts.notifications') 
                <div class="p-6 max-w-md mx-auto">
                    <form action="{{ route('wallet.send.post') }}" method="POST">
                        @csrf 
                        <input type="hidden" name="address" value="{{ $address }}">

                        <label class="block mb-1">Recipient Address</label>
                        <div class="relative mb-4">
                            <input type="text" id="scanned-text" name="to_address"
                                class="block w-full p-2.5 border rounded-lg"
                                placeholder="Scan QR code here" required>
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

                        <label class="block mb-1">Amount (TRX)</label>
                        <input name="amount" type="number" step="0.000001" class="block w-full p-2.5 border rounded-lg" required>

                        <button type="submit" class="bg-red-600 text-white px-4 py-2 mt-2 rounded hover:bg-red-700">Send TRX</button>
                    </form>

                    
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
        </div>
    </div>
</div>
</x-app-layout>
    
