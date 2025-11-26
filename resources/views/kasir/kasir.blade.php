{{-- resources/views/kasir.blade.php --}}
<x-app-layout>
    <div class="py-6 bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4">

            <!-- TOAST NOTIFICATION -->
            @include('kasir.partials.toast')

            <!-- LOADING OVERLAY -->
            @include('kasir.partials.loading')

            <!-- MODAL KONFIRMASI -->
            @include('kasir.partials.confirm-modal')

            <!-- MODAL PRINT STRUK -->
            @include('kasir.partials.print-modal')

            <!-- KEYBOARD SHORTCUTS INFO -->
            <div
                class="mb-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-3 text-sm">
                <strong>💡 Shortcut Keyboard:</strong>
                <span class="ml-2">F2: Fokus ke Barang</span> |
                <span class="ml-2">F3: Fokus ke Penitipan</span> |
                <span class="ml-2">F9: Simpan Transaksi</span> |
                <span class="ml-2">ESC: Batal/Tutup</span>
            </div>

            <!-- ================= FORM BARANG ================= -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border dark:border-gray-700 p-6 transition duration-300">
                <div class="flex gap-6">

                    <!-- ================= KOLOM KIRI ================= -->
                    <div class="flex-1 space-y-6">

                        <!-- TAB SELECTOR -->
                        @include('kasir.partials.tab-selector')

                        <!-- FORM BARANG STOK -->
                        @include('kasir.partials.form-barang', ['barangs' => $barangs])

                        <!-- FORM PENITIPAN -->
                        @include('kasir.partials.form-penitipan', [
                            'penitipanDetails' => $penitipanDetails,
                        ])

                        <!-- TABEL -->
                        @include('kasir.partials.table-cart')
                    </div>

                    <!-- ================= KOLOM KANAN ================= -->
                    @include('kasir.partials.payment-section')
                </div>
            </div>
        </div>
    </div>

    <!-- QR Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

    <!-- SweetAlert2 + Toast -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = {
            timeoutId: null,

            show(title, message, type = "success") {
                const toastBox = document.getElementById("toast");
                const icon = document.getElementById("toast-icon");
                const titleEl = document.getElementById("toast-title");
                const messageEl = document.getElementById("toast-message");

                if (!toastBox) return console.error("Toast HTML tidak ditemukan!");

                // Set icon & warna
                switch (type) {
                    case "success":
                        icon.textContent = "✓";
                        toastBox.classList.remove("border-red-500", "border-yellow-500");
                        toastBox.classList.add("border-green-500");
                        break;
                    case "error":
                        icon.textContent = "✕";
                        toastBox.classList.remove("border-green-500", "border-yellow-500");
                        toastBox.classList.add("border-red-500");
                        break;
                    case "warning":
                        icon.textContent = "!";
                        toastBox.classList.remove("border-green-500", "border-red-500");
                        toastBox.classList.add("border-yellow-500");
                        break;
                }

                titleEl.textContent = title;
                messageEl.textContent = message;

                toastBox.classList.remove("hidden");

                // Auto-hide after 3 sec
                clearTimeout(this.timeoutId);
                this.timeoutId = setTimeout(() => this.hide(), 3000);
            },

            hide() {
                const toastBox = document.getElementById("toast");
                if (toastBox) {
                    toastBox.classList.add("hidden");
                }
            }
        };

        // Make hideToast() available globally
        window.hideToast = () => Toast.hide();
    </script>


    <!-- Config -->
    <script>
        window.kasirConfig = {
            csrfToken: "{{ csrf_token() }}",
            storeUrl: "{{ route('kasir.store') }}",
            qrisImage: "{{ asset('qris.jpeg') }}",
            userName: "{{ Auth::user()->name }}",
            appName: "{{ config('app.name', 'MoKasir') }}"
        };

        // Data
        window.kasirData = {
            barangs: @json($barangs),
            penitipanDetails: @json($penitipanDetails)
        };
    </script>

    <!-- Your scripts (Toast sudah tersedia) -->
    <script src="{{ asset('js/kasir/main.js') }}"></script>
    <script src="{{ asset('js/kasir/managers.js') }}"></script>
    <script src="{{ asset('js/kasir/payment-qris.js') }}"></script>

</x-app-layout>
