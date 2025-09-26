<x-app-layout>
    <x-slot name="header">
        <h2>Scanner un QR Code</h2>
    </x-slot>

    <div id="qr-scan" class="py-6">
        <div id="reader" style="width:400px; border:1px solid gray; margin:auto;"></div>
        <div id="result" class="mt-4 text-center text-gray-700 dark:text-gray-300"></div>
    </div>

    {{-- 1️⃣ Charger la librairie QR --}}
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>

    {{-- 2️⃣ Script personnalisé pour scanner --}}
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            console.log("QR détecté :", decodedText);

            try {
                const parsed = JSON.parse(decodedText);
                document.getElementById("result").innerText = "QR détecté ✅";

                // Envoi au backend Laravel
                fetch("{{ route('qr.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ qrdata: decodedText })
                }).then(r => r.json())
                  .then(data => alert("QR stocké avec succès !"))
                  .catch(err => console.error(err));

            } catch(e) {
                document.getElementById("result").innerText = "QR scanné mais JSON invalide ❌";
                console.error("JSON invalide :", e);
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            const config = {
                fps: 15,
                qrbox: 400,
                experimentalFeatures: { useBarCodeDetectorIfSupported: true },
                rememberLastUsedCamera: true
            };

            const scanner = new Html5QrcodeScanner("reader", config);
            scanner.render(onScanSuccess);
        });
    </script>
</x-app-layout>

