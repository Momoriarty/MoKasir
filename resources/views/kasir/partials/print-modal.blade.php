{{-- ========================================
FILE: resources/views/kasir/partials/print-modal.blade.php
======================================== --}}
<div id="printModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <span class="text-6xl">✓</span>
            <h3 class="text-2xl font-bold text-green-600 mt-4">Transaksi Berhasil!</h3>
            <p class="text-gray-600 dark:text-gray-300 mt-2">ID: <span id="transaksiId"></span></p>
        </div>

        <div id="strukContent"
            class="border-2 border-dashed border-gray-300 dark:border-gray-600 p-4 rounded-lg mb-4 text-sm">
            <!-- Struk akan di-generate di sini -->
        </div>

        <div class="flex gap-3">
            <button onclick="printStruk()"
                class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                🖨️ Print Struk
            </button>
            <button onclick="closePrintModal()"
                class="flex-1 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                Selesai
            </button>
        </div>
    </div>
</div>