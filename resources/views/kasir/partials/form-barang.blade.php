
{{-- ========================================
     FILE: resources/views/kasir/partials/form-barang.blade.php
     ======================================== --}}
<div id="formBarang" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600 space-y-4">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Barang Stok</h3>

    <div class="space-y-4">
        <div>
            <label class="font-semibold">Nama Barang <span class="text-red-500">*</span></label>
            <select name="id_barang" id="selectBarang"
                class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Barang --</option>
                @foreach ($barangs as $barang)
                    <option value="{{ $barang->id_barang }}"
                        data-nama="{{ $barang->nama_barang }}"
                        data-harga-kardus="{{ $barang->harga_jual_kardus }}"
                        data-harga-ecer="{{ $barang->harga_jual_ecer }}"
                        data-stok-kardus="{{ $barang->stok_kardus }}"
                        data-stok-ecer="{{ $barang->stok_ecer }}" 
                        {{ ($barang->stok_kardus == 0 && $barang->stok_ecer == 0) ? 'disabled' : '' }}>
                        {{ $barang->nama_barang }} - Stok Kardus: {{ $barang->stok_kardus }}, Stok Ecer: {{ $barang->stok_ecer }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold">Kardus</label>
            <input type="number" name="jumlah_kardus" id="jumlahKardus" min="0" step="1"
                class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 focus:ring-2 focus:ring-blue-500"
                placeholder="0">
            <p id="maxKardus" class="text-xs text-gray-500 mt-1"></p>
        </div>

        <div>
            <label class="font-semibold">Ecer</label>
            <input type="number" name="jumlah_ecer" id="jumlahEcer" min="0" step="1"
                class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 focus:ring-2 focus:ring-blue-500"
                placeholder="0">
            <p id="maxEcer" class="text-xs text-gray-500 mt-1"></p>
        </div>

        <button id="btn-tambah" type="button"
            class="w-full bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-4 shadow-lg text-white hover:scale-105 transition-transform duration-300 font-semibold">
            ➕ Tambah ke Keranjang
        </button>
    </div>
</div>
