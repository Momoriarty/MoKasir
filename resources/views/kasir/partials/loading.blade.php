{{-- ========================================
FILE: resources/views/kasir/partials/loading.blade.php
======================================== --}}
<div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl">
        <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600 mx-auto"></div>
        <p class="mt-4 text-gray-700 dark:text-gray-300 font-semibold">Memproses transaksi...</p>
    </div>
</div>