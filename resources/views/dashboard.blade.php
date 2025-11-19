<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Transaksi Penjualan
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">

                <!-- HEADER ATAS: Judul & Total -->
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold">TRANSAKSI PENJUALAN | MASTER</h3>
                        <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                    </div>
                    <div class="w-48 space-y-2">
                        <div class="bg-red-500 text-white p-3 rounded text-right">
                            <div class="text-xs">TOTAL</div>
                            <div class="text-2xl font-bold" id="total-display">0</div>
                        </div>
                        <div class="bg-yellow-200 p-3 rounded text-right">
                            <div class="text-xs">BAYAR</div>
                            <div class="text-2xl font-bold text-blue-700" id="bayar-display">0</div>
                        </div>
                        <div class="bg-green-200 p-3 rounded text-right">
                            <div class="text-xs">KEMBALI</div>
                            <div class="text-2xl font-bold text-green-700" id="kembali-display">0</div>
                        </div>
                    </div>
                </div>



                <!-- INPUT BARANG: kardus, ecer, nama -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">

                    <!-- Nama Barang -->
                    <div class="flex flex-col">
                        <label class="mb-1 font-medium">Nama Barang</label>
                        <select name="id_barang" class="w-full border rounded p-2">
                            <option value="">--Pilih Barang--</option>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->id_barang }}"
                                    data-harga-kardus="{{ $barang->harga_jual_kardus }}"
                                    data-harga-ecer="{{ $barang->harga_jual_ecer }}">
                                    {{ $barang->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jumlah Kardus -->
                    <div class="flex flex-col">
                        <label class="mb-1 font-medium">Kardus</label>
                        <input type="number" name="jumlah_kardus" class="w-full border rounded p-2">
                    </div>

                    <!-- Jumlah Ecer -->
                    <div class="flex flex-col">
                        <label class="mb-1 font-medium">Ecer</label>
                        <input type="number" name="jumlah_ecer" class="w-full border rounded p-2">
                    </div>

                    <!-- Tombol Tambah -->
                    <div class="flex items-end">
                        <button type="button" id="btn-tambah"
                            class="w-full bg-blue-500 text-white font-medium rounded p-2 hover:bg-blue-600 transition">
                            Tambah
                        </button>
                    </div>

                </div>

                <!-- TABEL BARANG -->
                <div class="overflow-auto border rounded">
                    <table class="w-full text-xs table-auto" id="tabel-barang">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="p-2 border">Nama</th>
                                <th class="p-2 border">Jumlah (Kardus)</th>
                                <th class="p-2 border">Jumlah (Ecer)</th>
                                <th class="p-2 border">Harga Per Kardus</th>
                                <th class="p-2 border">Harga Per Ecer</th>
                                <th class="p-2 border">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Row barang akan ditambahkan di sini -->
                        </tbody>
                    </table>
                </div>
                <!-- FORM TANGGAL & TIPE PEMBAYARAN -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold">Tunai/Kredit</label>
                        <select class="w-full border rounded p-2 mt-1" name="tipe_pembayaran">
                            <option value="TUNAI">TUNAI</option>
                            <option value="KREDIT">KREDIT</option>
                        </select>
                    </div>
                </div>
                <!-- FOOTER: subtotal, bayar, tombol aksi -->
                <div class="grid grid-cols-3 gap-4 mt-4">
                    <!-- SUBTOTAL -->
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>SubTotal:</span>
                            <span id="subtotal-display">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sumbangan:</span>
                            <input type="number" class="border rounded p-1 w-20" name="sumbangan" value="0">
                        </div>
                        <div class="flex justify-between font-bold text-lg">
                            <span>GrandTotal:</span>
                            <span id="grandtotal-display">0</span>
                        </div>
                    </div>
                    <!-- BAYAR -->
                    <div class="space-y-2">
                        <label class="font-semibold">Bayar</label>
                        <input type="text" name="bayar" class="w-full border rounded p-2 text-xl font-bold">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Kembali:</span>
                            <span id="kembali-display2">0</span>
                        </div>
                    </div>
                    <!-- BUTTON -->
                    <div class="flex items-end gap-2">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                        <button class="bg-gray-600 text-white px-4 py-2 rounded">Cetak</button>
                        <button class="bg-green-600 text-white px-4 py-2 rounded">Baru</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Ambil elemen penting
        const btnTambah = document.getElementById('btn-tambah');
        const tbody = document.querySelector('#tabel-barang tbody');
        const subtotalDisplay = document.getElementById('subtotal-display');
        const grandtotalDisplay = document.getElementById('grandtotal-display');
        const bayarInput = document.querySelector('input[name="bayar"]');
        const sumbanganInput = document.querySelector('input[name="sumbangan"]');
        const kembaliDisplayFooter = document.getElementById('kembali-display2');
        const totalDisplayHeader = document.getElementById('total-display');
        const bayarDisplayHeader = document.getElementById('bayar-display');
        const kembaliDisplayHeader = document.getElementById('kembali-display');

        // Fungsi hitung total
        function updateTotal() {
            let subtotal = 0;
            tbody.querySelectorAll('tr').forEach(tr => {
                const total = parseInt(tr.children[5].innerText) || 0;
                subtotal += total;
            });

            const sumbangan = parseInt(sumbanganInput.value) || 0;
            const grandtotal = subtotal + sumbangan;

            const bayar = parseInt(bayarInput.value) || 0;
            const kembali = bayar - grandtotal;

            // Update footer
            subtotalDisplay.innerText = subtotal;
            grandtotalDisplay.innerText = grandtotal;
            kembaliDisplayFooter.innerText = kembali >= 0 ? kembali : 0;

            // Update header
            totalDisplayHeader.innerText = subtotal;
            bayarDisplayHeader.innerText = bayar;
            kembaliDisplayHeader.innerText = kembali >= 0 ? kembali : 0;
        }

        // Tambah barang ke tabel
        btnTambah.addEventListener('click', () => {
            const selectBarang = document.querySelector('select[name="id_barang"]');
            const jumlahKardus = parseInt(document.querySelector('input[name="jumlah_kardus"]').value) || 0;
            const jumlahEcer = parseInt(document.querySelector('input[name="jumlah_ecer"]').value) || 0;
            const namaBarang = selectBarang.options[selectBarang.selectedIndex].text;

            if (!selectBarang.value) {
                alert('Pilih barang dulu!');
                return;
            }

            // Contoh harga, bisa disesuaikan atau ambil dari dataset
            const hargaKardus = parseInt(selectBarang.selectedOptions[0].dataset.hargaKardus) || 0;
            const hargaEcer = parseInt(selectBarang.selectedOptions[0].dataset.hargaEcer) || 0;


            const total = jumlahKardus * hargaKardus + jumlahEcer * hargaEcer;

            const tr = document.createElement('tr');
            tr.innerHTML = `
        <td class="p-2 border">${namaBarang}</td>
        <td class="p-2 border">${jumlahKardus}</td>
        <td class="p-2 border">${jumlahEcer}</td>
        <td class="p-2 border">${hargaKardus}</td>
        <td class="p-2 border">${hargaEcer}</td>
        <td class="p-2 border">${total}</td>
    `;
            tbody.appendChild(tr);

            updateTotal();

            // Reset input
            selectBarang.value = '';
            document.querySelector('input[name="jumlah_kardus"]').value = '';
            document.querySelector('input[name="jumlah_ecer"]').value = '';
        });

        // Event listener sumbangan & bayar
        sumbanganInput.addEventListener('input', updateTotal);
        bayarInput.addEventListener('input', updateTotal);
    </script>
</x-app-layout>
