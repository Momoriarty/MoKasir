{{-- ========================================
FILE: resources/views/kasir/partials/table-cart.blade.php
======================================== --}}
<div class="overflow-x-auto rounded-lg border dark:border-gray-700">
    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
        <thead class="bg-gray-200 dark:bg-gray-900 text-gray-900 dark:text-gray-300">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold uppercase">Nama Barang</th>
                <th class="px-6 py-3 text-left text-xs font-bold uppercase">Jenis</th>
                <th class="px-6 py-3 text-center text-xs font-bold uppercase">Jumlah</th>
                <th class="px-6 py-3 text-left text-xs font-bold uppercase">Harga</th>
                <th class="px-6 py-3 text-left text-xs font-bold uppercase">Subtotal</th>
                <th class="px-6 py-3 text-center text-xs font-bold uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody id="tabel-barang"
            class="bg-gray-100 dark:bg-gray-800 divide-y divide-gray-300 dark:divide-gray-700 text-gray-900 dark:text-white">
            <tr id="emptyRow">
                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                    🛒 Keranjang masih kosong
                </td>
            </tr>
        </tbody>
    </table>
</div>