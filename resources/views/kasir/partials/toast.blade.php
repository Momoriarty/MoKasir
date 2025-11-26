{{-- ========================================
     FILE: resources/views/kasir/partials/toast.blade.php
     ======================================== --}}
<div id="toast" class="hidden fixed top-4 right-4 z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 border-l-4 border-green-500 max-w-md">
        <div class="flex items-center">
            <span id="toast-icon" class="text-2xl mr-3">✓</span>
            <div class="flex-1">
                <p id="toast-title" class="font-semibold text-gray-900 dark:text-white">Berhasil</p>
                <p id="toast-message" class="text-sm text-gray-600 dark:text-gray-300">Pesan</p>
            </div>
            <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600 ml-4">✕</button>
        </div>
    </div>
</div>
