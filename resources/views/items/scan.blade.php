@extends('layouts.app')

@section('title', 'Scan QR Code')
@section('page_title', 'Scan QR Code Barang')
@section('page_subtitle', 'Gunakan kamera perangkat Anda atau unggah gambar QR Code untuk mencari barang.')

@section('content')
    <div class="card scanner-container">
        <div class="card-header">
            <h2>Kamera Scanner</h2>
            <a href="{{ route('items.index') }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">Kembali</a>
        </div>

        <div style="padding: 10px 0; text-align: center; color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">
            Arahkan QR Code barang ke kamera laptop atau handphone Anda. Sistem akan mendeteksi SKU dan mengarahkan Anda langsung ke detail barang.
        </div>

        <!-- Feedback Alert -->
        <div id="feedback" class="scanner-feedback"></div>

        <!-- QR Reader Container -->
        <div id="reader"></div>

        <div style="margin-top: 20px; text-align: center;">
            <p style="font-size: 12px; color: var(--text-muted);">
                * Pastikan Anda mengizinkan akses kamera pada browser Anda.
            </p>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Load html5-qrcode library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    
    <script>
        let html5QrcodeScanner;

        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Scan result: ${decodedText}`, decodedResult);
            
            // Stop scanning once detected
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear().catch(error => {
                    console.error("Failed to clear scanner", error);
                });
            }

            const feedbackEl = document.getElementById('feedback');
            feedbackEl.className = "scanner-feedback alert-success";
            feedbackEl.innerText = "QR Code terdeteksi: " + decodedText + ". Memeriksa database...";
            feedbackEl.style.display = "block";

            // Query backend to find item
            fetch(`/items/find-by-sku/${encodeURIComponent(decodedText)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Barang tidak ditemukan');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        feedbackEl.innerText = "Barang ditemukan! Mengalihkan halaman...";
                        window.location.href = data.redirect_url;
                    }
                })
                .catch(error => {
                    console.error(error);
                    feedbackEl.className = "scanner-feedback alert-danger";
                    feedbackEl.innerText = "Barang dengan SKU '" + decodedText + "' tidak terdaftar.";
                    
                    // Restart scanner after 3 seconds
                    setTimeout(() => {
                        feedbackEl.style.display = "none";
                        startScanner();
                    }, 3000);
                });
        }

        function onScanError(errorMessage) {
            // Quietly ignore scan failure errors to avoid console noise
        }

        function startScanner() {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", 
                { 
                    fps: 15, 
                    qrbox: { width: 250, height: 250 },
                    rememberLastUsedCamera: true
                },
                /* verbose= */ false
            );
            html5QrcodeScanner.render(onScanSuccess, onScanError);
        }

        // Run when page is loaded
        document.addEventListener("DOMContentLoaded", function() {
            startScanner();
        });
    </script>
@endsection
