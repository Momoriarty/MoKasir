{{-- ========================================
FILE: resources/views/kasir/partials/form-penitipan.blade.php
======================================== --}}
<div id="formPenitipan" class="hidden bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600 space-y-4">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Barang Titipan</h3>

    <div class="space-y-4">
        <div>
            <label class="font-semibold">Barang Penitipan <span class="text-red-500">*</span></label>
            <select name="id_penitipan_detail" id="selectPenitipan"
                class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Barang Titipan --</option>
                @foreach ($penitipanDetails as $detail)
                    @if($detail->jumlah_sisa > 0)
                        <option value="{{ $detail->id_penitipan_detail }}" data-nama="{{ $detail->nama_barang }}"
                            data-penitip="{{ $detail->penitipan->nama_penitip }}" data-harga="{{ $detail->harga_jual }}"
                            data-stok="{{ $detail->jumlah_sisa }}">
                            {{ $detail->nama_barang }} - {{ $detail->penitipan->nama_penitip }} (Sisa:
                            {{ $detail->jumlah_sisa }})
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold">Harga Jual</label>
            <input type="text" id="hargaPenitipan" readonly
                class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 bg-gray-100 dark:bg-gray-800"
                placeholder="Pilih barang terlebih dahulu">
        </div>

        <div>
            <label class="font-semibold">Jumlah</label>
            <input type="number" name="jumlah_penitipan" id="jumlahPenitipan" min="0" step="1"
                class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 focus:ring-2 focus:ring-blue-500"
                placeholder="0" disabled>
            <p id="maxPenitipan" class="text-xs text-gray-500 mt-1"></p>
        </div>

        <button id="btn-tambah-penitipan" type="button"
            class="w-full bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 shadow-lg text-white hover:scale-105 transition-transform duration-300 font-semibold">
            ➕ Tambah ke Keranjang
        </button>


    </div>
</div>