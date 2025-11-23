{{-- ========================================
FILE: resources/views/kasir/partials/confirm-modal.blade.php
======================================== --}}
<div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Konfirmasi Transaksi</h3>
        <div id="confirmContent" class="space-y-2 text-gray-700 dark:text-gray-300 mb-6"></div>
        <div class="flex gap-3">
            <button onclick="closeConfirmModal()"
                class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg hover:bg-gray-400 transition">
                Batal
            </button>
            <button onclick="confirmTransaction()"
                class="flex-1 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                Ya, Simpan
            </button>
        </div>
    </div>
</div>