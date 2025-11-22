{{-- resources/views/kasir.blade.php --}}
<x-app-layout>
    <div class="py-6 bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4">

            <!-- ================= FORM BARANG ================= -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border dark:border-gray-700 p-6 transition duration-300">
                <div class="flex gap-6">

                    <!-- ================= KOLOM KIRI ================= -->
                    <div class="flex-1 space-y-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600 space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Barang</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="font-semibold">Nama Barang</label>
                                    <select name="id_barang"
                                        class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3">
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
                                    <label class="font-semibold">Kardus</label>
                                    <input type="number" name="jumlah_kardus" min="0" step="1"
                                        class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3">
                                </div>

                                <div>
                                    <label class="font-semibold">Ecer</label>
                                    <input type="number" name="jumlah_ecer" min="0" step="1"
                                        class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3">
                                </div>

                                <button id="btn-tambah"
                                    class="stat-card bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 shadow-lg text-white hover:scale-105 transition-transform duration-300">
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </div>

                        <!-- TABEL -->
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                            <thead class="bg-gray-200 dark:bg-gray-900 text-gray-900 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Nama Barang</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Kardus</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Harga Kardus</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Ecer</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Harga Ecer</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Subtotal</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-barang"
                                class="bg-gray-100 dark:bg-gray-800 divide-y divide-gray-300 dark:divide-gray-700 text-gray-900 dark:text-white">
                            </tbody>
                        </table>
                    </div>

                    <!-- ================= KOLOM KANAN ================= -->
                    <div class="w-[350px] space-y-4 sticky top-6 self-start">

                        <!-- RINCIAN PEMBAYARAN -->
                        <div id="rincian-box"
                            class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600 space-y-3">
                            <h3 class="text-lg font-semibold">Rincian Pembayaran</h3>

                            <div class="flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span id="total-display">0</span>
                            </div>

                            <div>
                                <label class="font-semibold">Bayar:</label>
                                <input type="number" id="bayar-input" min="0"
                                    class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3"
                                    value="0">
                            </div>

                            <div class="flex justify-between">
                                <span>Kembali:</span>
                                <span id="kembali-display">0</span>
                            </div>

                            <!-- PILIH METODE -->
                            <div>
                                <label class="font-semibold">Metode Pembayaran:</label>
                                <select id="metode"
                                    class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3">
                                    <option value="tunai">Tunai</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>
                        </div>

                        <!-- QRIS DINAMIS - diletakkan langsung di bawah rincian -->
                        <div id="qris-box"
                            class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600 space-y-3 hidden">
                            <h3 class="font-semibold text-center">QRIS Dinamis</h3>

                            <div id="qr-loading" class="text-center text-sm text-gray-600 dark:text-gray-300 hidden">
                                Menyiapkan QR... tunggu sebentar
                            </div>

                            <canvas id="qrDyn" class="mx-auto" width="260" height="260" style="max-width:100%"></canvas>

                            <p class="text-center text-sm text-gray-600 dark:text-gray-300">
                                Scan untuk membayar sesuai total.
                            </p>
                        </div>

                        <!-- FOOTER -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600">
                            <label class="font-semibold">Kembalian</label>
                            <input type="text" id="kembali-display2" disabled
                                class="mt-1 rounded-lg bg-gray-200 dark:bg-gray-800 dark:text-white w-full p-3 font-bold">

                            <button id="btn-simpan"
                                class="w-full py-2 mt-3 rounded-xl bg-green-500 text-white hover:bg-green-600 font-bold transition">
                                Simpan Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= SCRIPT QRIS & KASIR ================= -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

    <script>
        // ---------- UTIL ----------
        function formatAngka(angka) {
            // tampilkan "0" jika NaN
            if (isNaN(Number(angka))) return "0";
            return Number(angka).toLocaleString("id-ID");
        }

        function formatRupiah(angka) {
            if (isNaN(Number(angka))) return "Rp 0";
            return "Rp " + Number(angka).toLocaleString("id-ID");
        }

        // ---------- ELEMS ----------
        const tbody = document.getElementById("tabel-barang");
        const totalDisplayHeader = document.getElementById("total-display");
        const bayarInput = document.getElementById("bayar-input");
        const kembaliDisplayHeader = document.getElementById("kembali-display");
        const kembaliDisplayFooter = document.getElementById("kembali-display2");

        const metode = document.getElementById("metode");
        const qrisBox = document.getElementById("qris-box");
        const qrCanvas = document.getElementById("qrDyn");
        const qrLoading = document.getElementById("qr-loading");

        const btnTambah = document.getElementById("btn-tambah");
        const selectBarang = document.querySelector('select[name="id_barang"]');
        const jumlahKardusInput = document.querySelector('input[name="jumlah_kardus"]');
        const jumlahEcerInput = document.querySelector('input[name="jumlah_ecer"]');

        const btnSimpan = document.getElementById("btn-simpan");

        // ---------- CRC16 (untuk EMV QR) ----------
        function crc16_ccitt(str) {
            let crc = 0xFFFF;
            for (let i = 0; i < str.length; i++) {
                crc ^= str.charCodeAt(i) << 8;
                for (let j = 0; j < 8; j++) {
                    crc = (crc & 0x8000) ? ((crc << 1) ^ 0x1021) : (crc << 1);
                    crc &= 0xFFFF;
                }
            }
            return crc.toString(16).toUpperCase().padStart(4, '0');
        }

        // ---------- UPDATE TOTAL ----------
        function updateTotal() {
            let subtotal = 0;
            tbody.querySelectorAll("tr").forEach(tr => {
                // kolom subtotal index 5
                const cellVal = tr.children[5].innerText.replace(/\./g, "").replace(/Rp\s?/i, "");
                subtotal += parseInt(cellVal) || 0;
            });

            totalDisplayHeader.innerText = formatRupiah(subtotal);

            let bayar = parseInt(bayarInput.value || 0);
            let kembali = bayar - subtotal;

            kembaliDisplayHeader.innerText = formatRupiah(kembali >= 0 ? kembali : 0);
            kembaliDisplayFooter.value = formatRupiah(kembali >= 0 ? kembali : 0);

            if (metode.value === "qris") {
                // generate QRIS hanya jika subtotal > 0
                if (subtotal > 0) generateQRIS(subtotal);
                else {
                    // bersihkan QR dan sembunyikan
                    clearQRCode();
                    qrisBox.classList.add("hidden");
                }
            }
        }

        // ---------- CLEAR QR CANVAS ----------
        function clearQRCode() {
            try {
                const ctx = qrCanvas.getContext('2d');
                ctx.clearRect(0, 0, qrCanvas.width, qrCanvas.height);
            } catch (e) {
                // ignore
            }
        }

        // ---------- SAFE REGEX untuk hapus tag 54 dan CRC ----------
        function removeOldAmountAndCRC(payload) {
            // hapus tag 54 (amount) bila ada: format 54LL{digits}
            payload = payload.replace(/54\d{2}\d{1,12}/, "");
            // hapus CRC lama (tag 63 dengan length 04 + 4 hex)
            payload = payload.replace(/6304[0-9A-Fa-f]{4}$/, "");
            return payload;
        }

        // ---------- GENERATE QRIS ----------
        function generateQRIS(totalBayar) {
            qrisBox.classList.remove("hidden");
            qrLoading.classList.remove("hidden");
            clearQRCode();

            // pastikan path benar di public folder Laravel
            let img = new Image();
            // supaya canvas tidak ter-sandbox ketika server mengirim header yang benar
            img.crossOrigin = "anonymous";
            img.src = "{{ asset('qris.jpeg') }}"; // <-- pastikan file ada di public/qris.jpeg

            img.onload = () => {
                try {
                    // buat canvas sementara untuk membaca QR statis
                    let tmp = document.createElement("canvas");
                    let ctxTmp = tmp.getContext("2d");
                    tmp.width = img.width;
                    tmp.height = img.height;
                    ctxTmp.drawImage(img, 0, 0);

                    // coba dapatkan imageData (jika ter-tainted, akan throw)
                    let raw;
                    try {
                        raw = ctxTmp.getImageData(0, 0, tmp.width, tmp.height);
                    } catch (err) {
                        qrLoading.classList.add("hidden");
                        console.error("Canvas tainted / CORS error saat membaca qris.jpeg:", err);
                        alert(
                            "Gagal membaca QR statis karena CORS/tainted. Pastikan gambar diakses melalui server (pakai asset()) dan tidak lewat file:// .");
                        return;
                    }

                    let qr = jsQR(raw.data, tmp.width, tmp.height);

                    if (!qr) {
                        qrLoading.classList.add("hidden");
                        console.error("jsQR tidak menemukan QR di qris.jpeg");
                        alert(
                            "Gagal membaca QR statis! Pastikan qris.jpeg berisi QR code EMV yang valid dan tidak blur.");
                        return;
                    }

                    let payload = qr.data;
                    // bersihkan amount lama & crc lama
                    payload = removeOldAmountAndCRC(payload);

                    // siapkan nominal (tanpa pemisah), sesuai implementasi awal (dalam satuan penuh)
                    // kalau mau pakai dua desimal (contoh: 10000 -> 10000.00), perlu konversi
                    let nominal = String(totalBayar);
                    // pastikan nominal hanya angka
                    nominal = nominal.replace(/\D/g, "") || "0";

                    // length untuk tag 54 harus 2 digit (panjang karakter)
                    let length = String(nominal.length).padStart(2, "0");
                    let tag54 = `54${length}${nominal}`;

                    // gabungkan dan tambahkan placeholder CRC
                    let payloadNoCRC = payload + tag54 + "6304";
                    let crc = crc16_ccitt(payloadNoCRC);

                    let finalPayload = payloadNoCRC + crc;

                    // gambar ke canvas qris dinamis
                    // bersihkan dulu
                    clearQRCode();
                    QRCode.toCanvas(qrCanvas, finalPayload, {
                        width: 260
                    })
                        .then(() => {
                            qrLoading.classList.add("hidden");
                        })
                        .catch(err => {
                            qrLoading.classList.add("hidden");
                            console.error("Gagal menggambar QR dinamis:", err);
                            alert("Gagal membuat QR dinamis.");
                        });

                } catch (err) {
                    qrLoading.classList.add("hidden");
                    console.error("Error saat generateQRIS:", err);
                    alert("Terjadi kesalahan saat memproses QR statis.");
                }
            };

            img.onerror = (e) => {
                qrLoading.classList.add("hidden");
                console.error("img.onerror saat memuat qris.jpeg", e);
                alert(
                    "Gagal memuat file qris.jpeg. Pastikan file ada di folder public dan dapat diakses oleh browser.");
            };
        }

        // ---------- HANDLER METODE ----------
        metode.addEventListener("change", () => {
            if (metode.value === "qris") {
                // disable input bayar — QR akan jadi acuan
                bayarInput.value = 0;
                bayarInput.disabled = true;
                kembaliDisplayHeader.innerText = formatRupiah(0);
                kembaliDisplayFooter.value = formatRupiah(0);

                let total = getCurrentSubtotal();
                if (total > 0) generateQRIS(total);
                else {
                    qrisBox.classList.add("hidden");
                    clearQRCode();
                }
            } else {
                // tunai
                bayarInput.disabled = false;
                qrisBox.classList.add("hidden");
                clearQRCode();
            }
        });

        // ---------- UTILITY: ambil subtotal sekarang ----------
        function getCurrentSubtotal() {
            let subtotal = 0;
            tbody.querySelectorAll("tr").forEach(tr => {
                const cellVal = tr.children[5].innerText.replace(/\./g, "").replace(/Rp\s?/i, "");
                subtotal += parseInt(cellVal) || 0;
            });
            return subtotal;
        }

        // ---------- TAMBAH BARANG ----------
        btnTambah.addEventListener("click", () => {
            // validasi input
            if (!selectBarang.value) return alert("Pilih barang dulu!");
            const nama = selectBarang.options[selectBarang.selectedIndex].text;
            const jumlahKardus = parseInt(jumlahKardusInput.value) || 0;
            const jumlahEcer = parseInt(jumlahEcerInput.value) || 0;

            if (jumlahKardus <= 0 && jumlahEcer <= 0) return alert("Masukkan jumlah kardus atau ecer (minimal 1).");

            // ambil harga dari data-*
            const opt = selectBarang.selectedOptions[0];
            const hargaK = parseInt(opt.dataset.hargaKardus || opt.dataset.hargaKardus?.replace(/\D/g, "") || 0) ||
                0;
            const hargaE = parseInt(opt.dataset.hargaEcer || opt.dataset.hargaEcer?.replace(/\D/g, "") || 0) || 0;

            if (isNaN(hargaK) || isNaN(hargaE)) {
                return alert("Harga barang tidak valid. Periksa data-harga pada option.");
            }

            const total = jumlahKardus * hargaK + jumlahEcer * hargaE;

            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td class="px-6 py-2" data-id-barang="${selectBarang.value}">${nama}</td>
                <td class="px-6 py-2 text-center">${jumlahKardus}</td>
                <td class="px-6 py-2">${formatRupiah(hargaK)}</td>
                <td class="px-6 py-2 text-center">${jumlahEcer}</td>
                <td class="px-6 py-2">${formatRupiah(hargaE)}</td>
                <td class="px-6 py-2">${formatRupiah(total)}</td>
                <td class="px-6 py-2">
                    <button class="hapus bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Hapus</button>
                </td>
            `;

            tbody.appendChild(tr);
            updateTotal();

            // reset form kecil
            selectBarang.value = "";
            jumlahKardusInput.value = "";
            jumlahEcerInput.value = "";
        });

        // ---------- HAPUS BARANG ----------
        tbody.addEventListener("click", e => {
            if (e.target.classList.contains("hapus")) {
                e.target.closest("tr").remove();
                updateTotal();
            }
        });

        // ---------- BAYAR INPUT ----------
        bayarInput.addEventListener("input", updateTotal);
        function collectBarangData() {
            let data = [];

            tbody.querySelectorAll("tr").forEach(tr => {
                data.push({
                    id_barang: tr.children[0].dataset.idBarang,
                    jumlah: (parseInt(tr.children[1].innerText) || 0) + (parseInt(tr.children[3].innerText) || 0),
                    harga_jual: parseInt(tr.children[2].innerText.replace(/\D/g, "")) || 0,
                    subtotal: parseInt(tr.children[5].innerText.replace(/\D/g, "")) || 0
                });
            });

            return data;
        }

        // ---------- SIMPAN TRANSAKSI (dummy) ----------
        btnSimpan.addEventListener("click", () => {
            const subtotal = getCurrentSubtotal();
            if (subtotal <= 0) return alert("Keranjang kosong.");

            const barangData = collectBarangData();

            fetch("{{ route('kasir.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    total_harga: subtotal,                          // total harga sebenarnya
                    total_bayar: parseInt(bayarInput.value || 0),    // uang yang dibayar
                    metode: metode.value,
                    barang: barangData
                })
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        alert("Transaksi berhasil disimpan!");
                        window.location.reload();
                    } else {
                        alert(
                            "Gagal: " + res.message +
                            "\nError: " + (res.error ?? "Tidak ada detail")
                        );
                        console.log("DETAIL ERROR DARI SERVER:", res);
                    }
                })
                .catch(err => {
                    alert("Error: " + err);
                });
        });
        const subtotal = getCurrentSubtotal();

        // Inisialisasi: set tampil awal
        (function init() {
            totalDisplayHeader.innerText = formatRupiah(0);
            kembaliDisplayHeader.innerText = formatRupiah(0);
            kembaliDisplayFooter.value = formatRupiah(0);
            bayarInput.disabled = false;
            clearQRCode();
        })();
    </script>


</x-app-layout>