{{-- resources/views/qr/scan.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Scanner un QR Code') }}
        </h2>
    </x-slot>

    <div id="qr-scan" class="py-6">
        <div id="reader" class="mx-auto" style="width: 400px;"></div>
        <div id="result" class="mt-4 text-center text-gray-700 dark:text-gray-300"></div>
    </div>

    {{-- html5-qrcode --}}
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById("result").innerText = "QR Code: " + decodedText;

            // Envoi au backend Laravel via fetch
            fetch("{{ route('qr.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ qrdata: decodedText })
            })
            .then(r => r.json())
            .then(data => {
                console.log(data);
                alert("QR Code stocké avec succès !");
            })
            .catch(err => console.error(err));
        }

        document.addEventListener("DOMContentLoaded", () => {
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: 250 }
            );
            html5QrcodeScanner.render(onScanSuccess);
        });
    </script>
</x-app-layout>

