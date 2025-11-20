<x-app-layout>
    <div class="py-6 bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4">

            <!-- ================= QRIS DINAMIS ================= -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border dark:border-gray-700 p-6 transition-colors duration-300 mb-6"
                id="qris-section">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">QRIS Statis ➜ QRIS Dinamis</h2>

                <div class="space-y-4">
                    <div>
                        <label class="font-semibold text-gray-700 dark:text-gray-200">Upload Foto QRIS
                            Statis:</label><br>
                        <input type="file" id="upload" accept="image/*">
                    </div>
                    <div>
                        <label class="font-semibold text-gray-700 dark:text-gray-200">Nominal QRIS (Rp):</label><br>
                        <input type="number" id="nominal" placeholder="otomatis sesuai total" value="0"
                            class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 transition-colors duration-300"
                            readonly>
                    </div>
                    <div class="mt-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white">QRIS Dinamis:</h3>
                        <canvas id="qrDyn"></canvas>
                    </div>
                </div>
            </div>

            <!-- ================= FORM BARANG ================= -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border dark:border-gray-700 p-6 transition-colors duration-300">
                <div class="flex gap-6">
                    <!-- KOLOM KIRI: Form Barang & Tabel -->
                    <div class="flex-1 space-y-6">
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
                                    class="w-full py-2 rounded-xl mt-3 bg-blue-500 text-gray-900 hover:bg-blue-600 dark:bg-blue-600 dark:text-white dark:hover:bg-blue-700 font-bold transition-colors duration-300">Tambah
                                    ke Keranjang</button>
                            </div>
                        </div>

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
                                        Harga Kardus</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-300 uppercase tracking-wider">
                                        Ecer</th>
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

                    <!-- KOLOM KANAN: Total & Pembayaran -->
                    <div class="w-[350px] space-y-4 sticky top-6 self-start">
                        <div
                            class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600 space-y-3 transition-colors duration-300">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Rincian Pembayaran</h3>
                            <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                                <span>Total:</span>
                                <span id="total-display">0</span>
                            </div>
                            <div class="flex justify-between text-gray-900 dark:text-white">
                                <span>Bayar:</span>
                                <input type="number" id="bayar-input"
                                    class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 transition-colors duration-300"
                                    value="0">
                            </div>
                            <div class="flex justify-between text-gray-900 dark:text-white">
                                <span>Kembali:</span>
                                <span id="kembali-display">0</span>
                            </div>

                            <!-- PILIH METODE -->
                            <div class="mt-3">
                                <label class="font-semibold text-gray-700 dark:text-gray-200">Metode Pembayaran:</label>
                                <select id="metode"
                                    class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 transition-colors duration-300">
                                    <option value="tunai">Tunai</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>
                        </div>

                        <div
                            class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600 space-y-4 transition-colors duration-300 mt-3">
                            <div>
                                <label class="font-semibold text-gray-700 dark:text-gray-200">Kembalian</label>
                                <input type="text" id="kembali-display2" disabled
                                    class="mt-1 rounded-lg bg-gray-200 dark:bg-gray-800 dark:text-white w-full p-3 font-bold transition-colors duration-300">
                            </div>
                            <button
                                class="w-full py-2 rounded-xl bg-green-500 text-gray-900 hover:bg-green-600 dark:bg-green-600 dark:text-white dark:hover:bg-green-700 font-bold transition-colors duration-300">
                                Simpan Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= SCRIPT ================= -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script>
        function formatAngka(angka) {
            return Number(angka).toLocaleString("id-ID");
        }

        const btnTambah = document.getElementById("btn-tambah");
        const tbody = document.getElementById("tabel-barang");
        const totalDisplayHeader = document.getElementById("total-display");
        const bayarInput = document.getElementById("bayar-input");
        const kembaliDisplayHeader = document.getElementById("kembali-display");
        const kembaliDisplayFooter = document.getElementById("kembali-display2");
        const selectBarang = document.querySelector('select[name="id_barang"]');
        const jumlahKardusInput = document.querySelector('input[name="jumlah_kardus"]');
        const jumlahEcerInput = document.querySelector('input[name="jumlah_ecer"]');
        const nominalInput = document.getElementById("nominal");
        const metodeSelect = document.getElementById("metode");

        function updateTotal() {
            let subtotal = 0;
            tbody.querySelectorAll("tr").forEach(tr => {
                subtotal += parseInt(tr.children[5].innerText.replace(/\./g, "")) || 0;
            });
            totalDisplayHeader.innerText = formatAngka(subtotal);

            if (metodeSelect.value === "qris") {
                nominalInput.value = subtotal;
                bayarInput.value = subtotal;
                kembaliDisplayHeader.innerText = formatAngka(0);
                kembaliDisplayFooter.value = formatAngka(0);
                generateQRIS(subtotal);
            } else {
                let bayar = parseInt(bayarInput.value || 0);
                let kembali = bayar - subtotal;
                kembaliDisplayHeader.innerText = formatAngka(kembali >= 0 ? kembali : 0);
                kembaliDisplayFooter.value = formatAngka(kembali >= 0 ? kembali : 0);
            }
        }

        btnTambah.addEventListener("click", () => {
            if (!selectBarang.value) return alert("Pilih barang dulu!");
            const nama = selectBarang.options[selectBarang.selectedIndex].text;
            const jumlahKardus = parseInt(jumlahKardusInput.value) || 0;
            const jumlahEcer = parseInt(jumlahEcerInput.value) || 0;
            const hargaK = parseInt(selectBarang.selectedOptions[0].dataset.hargaKardus);
            const hargaE = parseInt(selectBarang.selectedOptions[0].dataset.hargaEcer);
            const total = jumlahKardus * hargaK + jumlahEcer * hargaE;
            const tr = document.createElement("tr");
            tr.innerHTML =
                `<td class="px-6 py-2">${nama}</td>
                <td class="px-6 py-2">${jumlahKardus}</td>
                <td class="px-6 py-2">${formatAngka(hargaK)}</td>
                <td class="px-6 py-2">${jumlahEcer}</td>
                <td class="px-6 py-2">${formatAngka(hargaE)}</td>
                <td class="px-6 py-2">${formatAngka(total)}</td>
                <td class="px-6 py-2"><button class="hapus bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 transition-colors duration-300">Hapus</button></td>`;
            tbody.appendChild(tr);
            updateTotal();
            selectBarang.value = "";
            jumlahKardusInput.value = "";
            jumlahEcerInput.value = "";
        });

        tbody.addEventListener("click", e => {
            if (e.target.classList.contains("hapus")) {
                e.target.closest("tr").remove();
                updateTotal();
            }
        });

        bayarInput.addEventListener("input", updateTotal);
        jumlahKardusInput.addEventListener("input", updateTotal);
        jumlahEcerInput.addEventListener("input", updateTotal);

        metodeSelect.addEventListener("change", () => {
            if (metodeSelect.value === "qris") {
                document.getElementById("qris-section").style.display = "block";
                nominalInput.readOnly = true;
            } else {
                document.getElementById("qris-section").style.display = "none";
                nominalInput.readOnly = false;
            }
            updateTotal();
        });

        // =============== QRIS DINAMIS ===============
        function crc16_ccitt(str) {
            let crc = 0xFFFF;
            for (let c of str) {
                crc ^= c.charCodeAt(0) << 8;
                for (let i = 0; i < 8; i++) {
                    if ((crc & 0x8000) !== 0) crc = (crc << 1) ^ 0x1021;
                    else crc <<= 1;
                    crc &= 0xFFFF;
                }
            }
            return crc.toString(16).toUpperCase().padStart(4, '0');
        }

        function generateQRIS(total) {
            let file = document.getElementById("upload").files[0];
            if (!file) return; // QRIS tidak bisa generate tanpa foto statis
            let img = new Image();
            img.src = URL.createObjectURL(file);
            img.onload = () => {
                let canvas = document.createElement("canvas");
                let ctx = canvas.getContext("2d");
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);

                let raw = ctx.getImageData(0, 0, canvas.width, canvas.height);
                let qr = jsQR(raw.data, canvas.width, canvas.height);
                if (!qr) return; // QR tidak terdeteksi

                let payload = qr.data;
                payload = payload.replace(/54\d{2}\d+/g, "");
                payload = payload.replace(/6304([0-9A-Fa-f]{4})$/, "");
                let nominalStr = String(total);
                let length = nominalStr.length.toString().padStart(2, "0");
                let tag54 = `54${length}${nominalStr}`;
                let payloadNoCRC = payload + tag54 + "6304";
                let crc = crc16_ccitt(payloadNoCRC);
                let finalPayload = payloadNoCRC + crc;

                QRCode.toCanvas(document.getElementById("qrDyn"), finalPayload, {
                    width: 300
                });
            };
        }

        // Update QRIS jika file berubah
        document.getElementById("upload").addEventListener("change", () => {
            if (metodeSelect.value === "qris") updateTotal();
        });
    </script>
</x-app-layout>
