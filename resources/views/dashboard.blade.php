<x-app-layout>
    <div class="py-6 bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4">

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-300 dark:border-gray-700 p-6 transition-colors duration-300">

                <!-- FLEX 70:30 -->
                <div class="flex gap-6">

                    <!-- ====================== -->
                    <!--       KOLOM KIRI       -->
                    <!-- ====================== -->
                    <div class="flex-1 space-y-6">

                        <!-- FORM TAMBAH BARANG -->
                        <div
                            class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600 space-y-4 transition-colors duration-300">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Barang</h3>
                            <div class="space-y-4">

                                <div>
                                    <label class="font-semibold text-gray-700 dark:text-gray-200">Nama Barang</label>
                                    <select name="id_barang"
                                        class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 transition-colors duration-300">
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach ($barangs as $barang)
                                            <option value="{{ $barang->id_barang }}"
                                                data-harga-kardus="{{ $barang->harga_jual_kardus }}"
                                                data-harga-ecer="{{ $barang->harga_jual_ecer }}">
                                                {{ $barang->nama_barang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="font-semibold text-gray-700 dark:text-gray-200">Kardus</label>
                                    <input type="number" name="jumlah_kardus"
                                        class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 transition-colors duration-300">
                                </div>

                                <div>
                                    <label class="font-semibold text-gray-700 dark:text-gray-200">Ecer</label>
                                    <input type="number" name="jumlah_ecer"
                                        class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 transition-colors duration-300">
                                </div>

                                <button id="btn-tambah"
                                    class="w-full py-2 rounded-xl mt-3
                                    bg-blue-500 text-gray-900 hover:bg-blue-600
                                    dark:bg-blue-600 dark:text-white dark:hover:bg-blue-700
                                    font-bold transition-colors duration-300">
                                    Tambah ke Keranjang
                                </button>

                            </div>
                        </div>

                        <!-- TABEL BARANG -->
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700 w-full">
                            <thead class="bg-gray-200 dark:bg-gray-900">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                        Nama Barang</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                        Kardus</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                        Ecer</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                        Harga Kardus</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                        Harga Ecer</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                        Subtotal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody
                                class="bg-gray-100 dark:bg-gray-800 divide-y divide-gray-300 dark:divide-gray-700 text-gray-900 dark:text-gray-100"
                                id="tabel-barang">
                            </tbody>

                        </table>

                    </div>

                    <!-- ====================== -->
                    <!--       KOLOM KANAN      -->
                    <!-- ====================== -->
                    <div class="w-[350px] space-y-4 sticky top-6 self-start">

                        <!-- TOTAL PANEL -->
                        <div
                            class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600 space-y-3 transition-colors duration-300">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Rincian Pembayaran</h3>

                            <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                                <span>Total:</span>
                                <span id="total-display">0</span>
                            </div>

                            <div class="flex justify-between text-gray-900 dark:text-white">
                                <span>Bayar:</span>
                                <span id="bayar-display">0</span>
                            </div>

                            <div class="flex justify-between text-gray-900 dark:text-white">
                                <span>Kembali:</span>
                                <span id="kembali-display">0</span>
                            </div>
                        </div>

                        <!-- METODE PEMBAYARAN -->
                        <div
                            class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600 space-y-4 transition-colors duration-300 mt-3">
                            <label class="font-semibold text-gray-700 dark:text-gray-200">Metode Pembayaran</label>
                            <select id="metode-bayar"
                                class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 transition-colors duration-300">
                                <option value="tunai">Tunai</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>

                        <!-- BAYAR PANEL -->
                        <div
                            class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600 space-y-4 transition-colors duration-300 mt-3">
                            <div>
                                <label class="font-semibold text-gray-700 dark:text-gray-200">Bayar</label>
                                <input type="number" name="bayar"
                                    class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 transition-colors duration-300">
                            </div>

                            <div>
                                <label class="font-semibold text-gray-700 dark:text-gray-200">Kembalian</label>
                                <input type="text" id="kembali-display2" disabled
                                    class="mt-1 rounded-lg bg-gray-200 dark:bg-gray-800 dark:text-white w-full p-3 font-bold transition-colors duration-300">
                            </div>

                            <button
                                class="w-full py-2 rounded-xl
                            bg-green-500 text-gray-900 hover:bg-green-600
                            dark:bg-green-600 dark:text-white dark:hover:bg-green-700
                            font-bold transition-colors duration-300">
                                Simpan Transaksi
                            </button>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- ========================= -->
    <!--          SCRIPT           -->
    <!-- ========================= -->
    <script>
        function formatAngka(angka) {
            return angka.toLocaleString("id-ID");
        }

        const btnTambah = document.getElementById("btn-tambah");
        const tbody = document.getElementById("tabel-barang");

        const totalDisplayHeader = document.getElementById("total-display");
        const bayarDisplayHeader = document.getElementById("bayar-display");
        const kembaliDisplayHeader = document.getElementById("kembali-display");

        const selectBarang = document.querySelector('select[name="id_barang"]');
        const jumlahKardusInput = document.querySelector('input[name="jumlah_kardus"]');
        const jumlahEcerInput = document.querySelector('input[name="jumlah_ecer"]');
        const bayarInput = document.querySelector('input[name="bayar"]');
        const kembaliDisplayFooter = document.getElementById("kembali-display2");
        const metodeBayar = document.getElementById("metode-bayar");

        // ======================
        // Disable input ecer jika harga ecer = 0
        // ======================
        selectBarang.addEventListener("change", () => {
            const hargaE = parseInt(selectBarang.selectedOptions[0].dataset.hargaEcer);
            if (hargaE === 0) {
                jumlahEcerInput.value = 0;
                jumlahEcerInput.disabled = true;
                jumlahEcerInput.classList.add('bg-gray-200', 'cursor-not-allowed');
            } else {
                jumlahEcerInput.disabled = false;
                jumlahEcerInput.classList.remove('bg-gray-200', 'cursor-not-allowed');
            }
        });

        // ======================
        // Update total dan kembalian
        // ======================
        function updateTotal() {
            let subtotal = 0;

            tbody.querySelectorAll("tr").forEach(tr => {
                let nilai = tr.children[5].innerText.replace(/\./g, "");
                subtotal += parseInt(nilai) || 0;
            });

            let bayar = parseInt(bayarInput.value.replace(/\./g, "")) || 0;

            // Jika metode QRIS, bayar otomatis = total
            if (metodeBayar.value === "qris") {
                bayar = subtotal;
            }

            const kembali = bayar - subtotal;

            totalDisplayHeader.innerText = formatAngka(subtotal);
            bayarDisplayHeader.innerText = formatAngka(bayar);
            kembaliDisplayHeader.innerText = formatAngka(kembali >= 0 ? kembali : 0);
            kembaliDisplayFooter.value = formatAngka(kembali >= 0 ? kembali : 0);
        }

        // ======================
        // Tambah ke keranjang
        // ======================
        btnTambah.addEventListener("click", () => {
            if (!selectBarang.value) return alert("Pilih barang dulu!");

            const nama = selectBarang.options[selectBarang.selectedIndex].text;
            const jumlahKardus = parseInt(jumlahKardusInput.value) || 0;
            const jumlahEcer = parseInt(jumlahEcerInput.value) || 0;
            const hargaK = parseInt(selectBarang.selectedOptions[0].dataset.hargaKardus);
            const hargaE = parseInt(selectBarang.selectedOptions[0].dataset.hargaEcer);
            const total = jumlahKardus * hargaK + jumlahEcer * hargaE;

            const tr = document.createElement("tr");
            tr.classList.add("transition-colors", "duration-300");
            tr.innerHTML = `
        <td class="px-6 py-2">${nama}</td>
        <td class="px-6 py-2">${jumlahKardus}</td>
        <td class="px-6 py-2">${jumlahEcer}</td>
        <td class="px-6 py-2">${formatAngka(hargaK)}</td>
        <td class="px-6 py-2">${formatAngka(hargaE)}</td>
        <td class="px-6 py-2">${formatAngka(total)}</td>
        <td class="px-6 py-2">
            <button class="hapus bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 transition-colors duration-300">Hapus</button>
        </td>
    `;
            tbody.appendChild(tr);
            updateTotal();

            // ======================
            // Reset form setelah tambah
            // ======================
            selectBarang.value = "";
            jumlahKardusInput.value = "";
            jumlahEcerInput.value = "";
            jumlahEcerInput.disabled = false;
            jumlahEcerInput.classList.remove('bg-gray-200', 'cursor-not-allowed');
        });

        // ======================
        // Hapus barang
        // ======================
        tbody.addEventListener("click", (e) => {
            if (e.target.classList.contains("hapus")) {
                e.target.closest("tr").remove();
                updateTotal();
            }
        });

        // ======================
        // Auto format input bayar
        // ======================
        bayarInput.addEventListener("input", (e) => {
            if (metodeBayar.value === "qris") return; // tidak bisa ubah jika QRIS
            let value = e.target.value.replace(/\D/g, ""); // hapus semua selain angka
            e.target.value = value; // jangan format di input, biar tidak reset
            updateTotal();
        });


        // ======================
        // Ganti metode bayar
        // ======================
        metodeBayar.addEventListener("change", () => {
            if (metodeBayar.value === "qris") {
                bayarInput.value = totalDisplayHeader.innerText;
                bayarInput.disabled = true;
                bayarInput.classList.add('bg-gray-200', 'cursor-not-allowed');
            } else {
                bayarInput.disabled = false;
                bayarInput.classList.remove('bg-gray-200', 'cursor-not-allowed');
            }
            updateTotal();
        });
    </script>
</x-app-layout>
