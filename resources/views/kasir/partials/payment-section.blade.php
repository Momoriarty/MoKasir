{{-- ========================================
FILE: resources/views/kasir/partials/payment-section.blade.php
======================================== --}}
<div class="w-[350px] space-y-4 sticky top-6 self-start">
    <!-- RINCIAN PEMBAYARAN -->
    <div id="rincian-box" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600 space-y-3">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">💰 Rincian Pembayaran</h3>

        <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white">
            <span>Total:</span>
            <span id="total-display">Rp 0</span>
        </div>

        <div>
            <label class="font-semibold text-gray-900 dark:text-white">Bayar:</label>
            <input type="number" id="bayar-input" min="0"
                class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 focus:ring-2 focus:ring-green-500"
                value="0" placeholder="Masukkan jumlah bayar">
        </div>

        <div class="flex justify-between text-gray-900 dark:text-white">
            <span>Kembali:</span>
            <span id="kembali-display" class="font-bold">Rp 0</span>
        </div>

        <div>
            <label class="font-semibold text-gray-900 dark:text-white">Metode Pembayaran:</label>
            <select id="metode"
                class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 focus:ring-2 focus:ring-blue-500">
                <option value="Tunai">💵 Tunai</option>
                <option value="Qris">📱 QRIS</option>
            </select>
        </div>
    </div>

    <!-- QRIS BOX -->
    <div id="qrisBox" class="hidden mt-3">
        <div class="flex items-center gap-3 mb-2">
            <span class="text-sm font-semibold">QRIS Pembayaran</span>
            <div id="qrLoading" class="animate-pulse text-xs text-gray-500 hidden">
                Menghasilkan QR...
            </div>
        </div>

        <canvas id="qrCanvas" width="260" height="260" class="border mx-auto"></canvas>
    </div>


    <!-- TOMBOL SIMPAN -->
    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600">
        <button id="btn-simpan" type="button"
            class="w-full py-3 rounded-xl bg-green-500 text-white hover:bg-green-600 font-bold transition shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
            disabled>
            💾 Simpan Transaksi (F9)
        </button>
    </div>
</div>