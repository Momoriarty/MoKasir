{{-- resources/views/kasir.blade.php --}}
<x-app-layout>
    <div class="py-6 bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4">

            <!-- TOAST NOTIFICATION -->
            <div id="toast" class="fixed top-4 right-4 z-50 hidden">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 border-l-4 border-green-500 max-w-md">
                    <div class="flex items-center">
                        <span id="toast-icon" class="text-2xl mr-3">✓</span>
                        <div class="flex-1">
                            <p id="toast-title" class="font-semibold text-gray-900 dark:text-white"></p>
                            <p id="toast-message" class="text-sm text-gray-600 dark:text-gray-300"></p>
                        </div>
                        <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600 ml-4">✕</button>
                    </div>
                </div>
            </div>

            <!-- LOADING OVERLAY -->
            <div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600 mx-auto"></div>
                    <p class="mt-4 text-gray-700 dark:text-gray-300 font-semibold">Memproses transaksi...</p>
                </div>
            </div>

            <!-- MODAL KONFIRMASI -->
            <div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Konfirmasi Transaksi</h3>
                    <div id="confirmContent" class="space-y-2 text-gray-700 dark:text-gray-300 mb-6"></div>
                    <div class="flex gap-3">
                        <button onclick="closeConfirmModal()" class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg hover:bg-gray-400 transition">
                            Batal
                        </button>
                        <button onclick="setTimeout(confirmTransaction, 100)" class="flex-1 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                            Ya, Simpan
                        </button>
                    </div>
                </div>
            </div>

            <!-- MODAL PRINT STRUK -->
            <div id="printModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
                    <div class="text-center mb-6">
                        <span class="text-6xl">✓</span>
                        <h3 class="text-2xl font-bold text-green-600 mt-4">Transaksi Berhasil!</h3>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">ID: <span id="transaksiId"></span></p>
                    </div>
                    
                    <div id="strukContent" class="border-2 border-dashed border-gray-300 dark:border-gray-600 p-4 rounded-lg mb-4 text-sm">
                        <!-- Struk akan di-generate di sini -->
                    </div>

                    <div class="flex gap-3">
                        <button onclick="printStruk()" class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                            🖨️ Print Struk
                        </button>
                        <button onclick="closePrintModal()" class="flex-1 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                            Selesai
                        </button>
                    </div>
                </div>
            </div>

            <!-- KEYBOARD SHORTCUTS INFO -->
            <div class="mb-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-3 text-sm">
                <strong>💡 Shortcut Keyboard:</strong> 
                <span class="ml-2">F2: Fokus ke Barang</span> | 
                <span class="ml-2">F9: Simpan Transaksi</span> |
                <span class="ml-2">ESC: Batal/Tutup</span>
            </div>

            <!-- ================= FORM BARANG ================= -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border dark:border-gray-700 p-6 transition duration-300">
                <div class="flex gap-6">

                    <!-- ================= KOLOM KIRI ================= -->
                    <div class="flex-1 space-y-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600 space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Barang</h3>

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

                        <!-- TABEL -->
                        <div class="overflow-x-auto rounded-lg border dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                <thead class="bg-gray-200 dark:bg-gray-900 text-gray-900 dark:text-gray-300">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Nama Barang</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold uppercase">Kardus</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Harga Kardus</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold uppercase">Ecer</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Harga Ecer</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Subtotal</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tabel-barang"
                                    class="bg-gray-100 dark:bg-gray-800 divide-y divide-gray-300 dark:divide-gray-700 text-gray-900 dark:text-white">
                                    <tr id="emptyRow">
                                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                            🛒 Keranjang masih kosong
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ================= KOLOM KANAN ================= -->
                    <div class="w-[350px] space-y-4 sticky top-6 self-start">

                        <!-- RINCIAN PEMBAYARAN -->
                        <div id="rincian-box"
                            class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600 space-y-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">💰 Rincian Pembayaran</h3>

                            <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                                <span>Total:</span>
                                <span id="total-display">Rp 0</span>
                            </div>

                            <div>
                                <label class="font-semibold text-gray-900 dark:text-white">Bayar:</label>
                                <input type="number" id="bayar-input" min="0"
                                    class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 focus:ring-2 focus:ring-green-500"
                                    value="0" placeholder="Masukkan jumlah bayar">
                            </div>

                            <div class="flex justify-between text-gray-900 dark:text-white">
                                <span>Kembali:</span>
                                <span id="kembali-display" class="font-bold">Rp 0</span>
                            </div>

                            <!-- PILIH METODE -->
                            <div>
                                <label class="font-semibold text-gray-900 dark:text-white">Metode Pembayaran:</label>
                                <select id="metode"
                                    class="mt-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white w-full p-3 focus:ring-2 focus:ring-blue-500">
                                    <option value="Tunai">💵 Tunai</option>
                                    <option value="Qris">📱 QRIS</option>
                                </select>
                            </div>
                        </div>

                        <!-- QRIS DINAMIS -->
                        <div id="qris-box"
                            class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600 space-y-3 hidden">
                            <h3 class="font-semibold text-center text-gray-900 dark:text-white">📱 QRIS Dinamis</h3>

                            <div id="qr-loading" class="text-center text-sm text-gray-600 dark:text-gray-300 hidden">
                                <div class="animate-pulse">Menyiapkan QR code...</div>
                            </div>

                            <canvas id="qrDyn" class="mx-auto rounded-lg" width="260" height="260"></canvas>

                            <p class="text-center text-sm text-gray-600 dark:text-gray-300">
                                Scan untuk membayar sesuai total
                            </p>
                        </div>

                        <!-- TOMBOL SIMPAN -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border dark:border-gray-600">
                            <button id="btn-simpan" type="button"
                                class="w-full py-3 rounded-xl bg-green-500 text-white hover:bg-green-600 font-bold transition shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                                💾 Simpan Transaksi (F9)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= SCRIPTS ================= -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

    <script>
        // ========== VARIABLES ==========
        let transactionData = null;

        // ========== TOAST NOTIFICATION ==========
        function showToast(title, message, type = 'success') {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toast-icon');
            const titleEl = document.getElementById('toast-title');
            const messageEl = document.getElementById('toast-message');

            const icons = {
                success: '✓',
                error: '✕',
                warning: '⚠',
                info: 'ℹ'
            };

            const colors = {
                success: 'border-green-500',
                error: 'border-red-500',
                warning: 'border-yellow-500',
                info: 'border-blue-500'
            };

            icon.textContent = icons[type] || icons.success;
            titleEl.textContent = title;
            messageEl.textContent = message;
            
            toast.querySelector('div').className = `bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 border-l-4 ${colors[type]} max-w-md`;
            toast.classList.remove('hidden');

            setTimeout(() => hideToast(), 5000);
        }

        function hideToast() {
            document.getElementById('toast').classList.add('hidden');
        }

        // ========== LOADING ==========
        function showLoading() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }

        // ========== UTILITY FUNCTIONS ==========
        function formatRupiah(angka) {
            if (isNaN(Number(angka))) return "Rp 0";
            return "Rp " + Number(angka).toLocaleString("id-ID");
        }

        function updateOptionText(opt, stokKardus, stokEcer) {
            const nama = opt.dataset.nama;
            opt.text = `${nama} - Stok Kardus: ${stokKardus}, Stok Ecer: ${stokEcer}`;
            opt.disabled = stokKardus === 0 && stokEcer === 0;
        }

        // ========== ELEMENTS ==========
        const tbody = document.getElementById("tabel-barang");
        const emptyRow = document.getElementById("emptyRow");
        const totalDisplayHeader = document.getElementById("total-display");
        const bayarInput = document.getElementById("bayar-input");
        const kembaliDisplayHeader = document.getElementById("kembali-display");
        const metode = document.getElementById("metode");
        const qrisBox = document.getElementById("qris-box");
        const qrCanvas = document.getElementById("qrDyn");
        const qrLoading = document.getElementById("qr-loading");
        const btnTambah = document.getElementById("btn-tambah");
        const selectBarang = document.getElementById("selectBarang");
        const jumlahKardusInput = document.getElementById("jumlahKardus");
        const jumlahEcerInput = document.getElementById("jumlahEcer");
        const btnSimpan = document.getElementById("btn-simpan");
        const maxKardus = document.getElementById("maxKardus");
        const maxEcer = document.getElementById("maxEcer");

        // ========== KEYBOARD SHORTCUTS ==========
        document.addEventListener('keydown', (e) => {
            // F2: Focus ke select barang
            if (e.key === 'F2') {
                e.preventDefault();
                selectBarang.focus();
            }
            
            // F9: Simpan transaksi
            if (e.key === 'F9') {
                e.preventDefault();
                if (!btnSimpan.disabled) {
                    showConfirmModal();
                }
            }
            
            // ESC: Tutup modal
            if (e.key === 'Escape') {
                closeConfirmModal();
                closePrintModal();
            }
        });

        // ========== HANDLE SELECT BARANG CHANGE ==========
        selectBarang.addEventListener("change", () => {
            const opt = selectBarang.selectedOptions[0];
            if (!opt.value) {
                maxKardus.textContent = '';
                maxEcer.textContent = '';
                jumlahKardusInput.disabled = true;
                jumlahEcerInput.disabled = true;
                jumlahKardusInput.value = "";
                jumlahEcerInput.value = "";
                return;
            }

            const stokKardus = parseInt(opt.dataset.stokKardus || 0);
            const stokEcer = parseInt(opt.dataset.stokEcer || 0);

            jumlahKardusInput.disabled = stokKardus === 0;
            jumlahEcerInput.disabled = stokEcer === 0;
            
            maxKardus.textContent = stokKardus > 0 ? `Maksimal: ${stokKardus} kardus` : 'Stok kardus habis';
            maxEcer.textContent = stokEcer > 0 ? `Maksimal: ${stokEcer} ecer` : 'Stok ecer habis';

            if (stokKardus === 0) jumlahKardusInput.value = "";
            if (stokEcer === 0) jumlahEcerInput.value = "";
        });

        // ========== UPDATE TOTAL & RINCIAN ==========
        function updateTotal() {
            const rincianBox = document.getElementById("rincian-box");
            
            let subtotal = 0;
            const rows = tbody.querySelectorAll("tr:not(#emptyRow)");
            
            rows.forEach(tr => {
                // Index 5 adalah kolom Subtotal
                const subtotalText = tr.children[5]?.innerText || "0";
                const total = parseInt(subtotalText.replace(/\D/g, "")) || 0;
                subtotal += total;
            });

            // Pajak untuk QRIS
            let pajak = 0;
            if (metode.value === "Qris" && subtotal > 0) {
                pajak = 1000;
            }

            // Tampilkan pajak
            let pajakRow = rincianBox.querySelector("#pajak-row");
            if (pajak > 0) {
                if (!pajakRow) {
                    pajakRow = document.createElement("div");
                    pajakRow.id = "pajak-row";
                    pajakRow.classList.add("flex", "justify-between", "text-sm", "text-gray-700", "dark:text-gray-300");
                    rincianBox.insertBefore(pajakRow, totalDisplayHeader.parentNode);
                }
                pajakRow.innerHTML = `<span>Biaya Admin QRIS:</span><span>${formatRupiah(pajak)}</span>`;
            } else {
                if (pajakRow) pajakRow.remove();
            }

            const totalBayar = subtotal + pajak;
            totalDisplayHeader.innerText = formatRupiah(totalBayar);

            // Update bayar & kembali
            let bayar = parseInt(bayarInput.value || 0);
            
            // Auto set untuk QRIS
            if (metode.value === "Qris" && subtotal > 0) {
                bayar = totalBayar;
                bayarInput.value = bayar;
                bayarInput.disabled = true;
            } else if (metode.value === "Tunai") {
                bayarInput.disabled = false;
            }

            const kembali = bayar - totalBayar;
            kembaliDisplayHeader.innerText = formatRupiah(kembali >= 0 ? kembali : 0);

            // Update button state
            const isBayarCukup = bayar >= totalBayar;
            btnSimpan.disabled = subtotal === 0 || !isBayarCukup;

            // Generate QRIS jika metode QRIS dan ada barang
            if (metode.value === "Qris" && subtotal > 0) {
                generateQRIS(totalBayar);
            } else {
                clearQRCode();
                qrisBox.classList.add("hidden");
            }
        }

        // ========== CRC16 UNTUK EMV QR ==========
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

        function removeOldAmountAndCRC(payload) {
            payload = payload.replace(/54\d{2}\d{1,12}/, "");
            payload = payload.replace(/6304[0-9A-Fa-f]{4}$/, "");
            return payload;
        }

        function clearQRCode() {
            try {
                const ctx = qrCanvas.getContext('2d');
                ctx.clearRect(0, 0, qrCanvas.width, qrCanvas.height);
            } catch (e) {
                console.error('Error clearing QR:', e);
            }
        }

        // ========== GENERATE QRIS ==========
        function generateQRIS(totalBayar) {
            console.log('Generating QRIS for amount:', totalBayar);
            
            qrisBox.classList.remove("hidden");
            qrLoading.classList.remove("hidden");
            clearQRCode();

            let img = new Image();
            img.crossOrigin = "anonymous";
            img.src = "{{ asset('qris.jpeg') }}";

            img.onload = () => {
                try {
                    let tmp = document.createElement("canvas");
                    let ctxTmp = tmp.getContext("2d");
                    tmp.width = img.width;
                    tmp.height = img.height;
                    ctxTmp.drawImage(img, 0, 0);

                    let raw;
                    try {
                        raw = ctxTmp.getImageData(0, 0, tmp.width, tmp.height);
                    } catch (err) {
                        qrLoading.classList.add("hidden");
                        showToast('Error', 'Gagal membaca QR statis (CORS)', 'error');
                        console.error('CORS error:', err);
                        return;
                    }

                    let qr = jsQR(raw.data, tmp.width, tmp.height);
                    if (!qr) {
                        qrLoading.classList.add("hidden");
                        showToast('Error', 'QR code tidak valid', 'error');
                        return;
                    }

                    console.log('Original QR data:', qr.data);

                    let payload = qr.data;
                    payload = removeOldAmountAndCRC(payload);

                    let nominal = String(totalBayar).replace(/\D/g, "") || "0";
                    let length = String(nominal.length).padStart(2, "0");
                    let tag54 = `54${length}${nominal}`;

                    let payloadNoCRC = payload + tag54 + "6304";
                    let crc = crc16_ccitt(payloadNoCRC);
                    let finalPayload = payloadNoCRC + crc;

                    console.log('Final QRIS payload:', finalPayload);

                    clearQRCode();
                    QRCode.toCanvas(qrCanvas, finalPayload, { width: 260 })
                        .then(() => {
                            qrLoading.classList.add("hidden");
                            console.log('QRIS generated successfully');
                        })
                        .catch(err => {
                            qrLoading.classList.add("hidden");
                            showToast('Error', 'Gagal generate QR dinamis', 'error');
                            console.error('QR generation error:', err);
                        });

                } catch (err) {
                    qrLoading.classList.add("hidden");
                    showToast('Error', 'Error saat memproses QR: ' + err.message, 'error');
                    console.error('Processing error:', err);
                }
            };

            img.onerror = () => {
                qrLoading.classList.add("hidden");
                showToast('Error', 'Gagal memuat qris.jpeg. Pastikan file ada di public/qris.jpeg', 'error');
                console.error('Image load error');
            };
        }

        // ========== METODE CHANGE ==========
        metode.addEventListener("change", () => {
            console.log('Payment method changed to:', metode.value);
            const subtotal = getCurrentSubtotal();
            
            if (metode.value === "Qris") {
                // Set bayar otomatis untuk QRIS
                const totalWithAdmin = subtotal + 1000;
                bayarInput.value = totalWithAdmin;
                bayarInput.disabled = true;
                
                // Generate QRIS jika ada barang
                if (subtotal > 0) {
                    generateQRIS(totalWithAdmin);
                } else {
                    qrisBox.classList.add("hidden");
                }
            } else {
                // Tunai - enable manual input
                bayarInput.disabled = false;
                qrisBox.classList.add("hidden");
                clearQRCode();
            }
            
            updateTotal();
        });

        // ========== TAMBAH BARANG ==========
        btnTambah.addEventListener("click", () => {
            if (!selectBarang.value) {
                showToast('Peringatan', 'Silakan pilih barang terlebih dahulu', 'warning');
                return;
            }

            const opt = selectBarang.selectedOptions[0];
            const nama = opt.dataset.nama;
            const jumlahKardus = parseInt(jumlahKardusInput.value) || 0;
            const jumlahEcer = parseInt(jumlahEcerInput.value) || 0;

            if (jumlahKardus <= 0 && jumlahEcer <= 0) {
                showToast('Peringatan', 'Masukkan jumlah minimal 1', 'warning');
                return;
            }

            let stokKardus = parseInt(opt.dataset.stokKardus || 0);
            let stokEcer = parseInt(opt.dataset.stokEcer || 0);

            if (jumlahKardus > stokKardus) {
                showToast('Error', 'Jumlah kardus melebihi stok!', 'error');
                return;
            }
            if (jumlahEcer > stokEcer) {
                showToast('Error', 'Jumlah ecer melebihi stok!', 'error');
                return;
            }

            const hargaK = parseInt(opt.dataset.hargaKardus) || 0;
            const hargaE = parseInt(opt.dataset.hargaEcer) || 0;
            const total = jumlahKardus * hargaK + jumlahEcer * hargaE;

            // Hide empty row
            emptyRow.classList.add('hidden');

            const tr = document.createElement("tr");
            tr.className = "hover:bg-gray-200 dark:hover:bg-gray-700 transition";
            tr.innerHTML = `
                <td class="px-6 py-3" data-id-barang="${selectBarang.value}">${nama}</td>
                <td class="px-6 py-3 text-center">${jumlahKardus}</td>
                <td class="px-6 py-3">${formatRupiah(hargaK)}</td>
                <td class="px-6 py-3 text-center">${jumlahEcer}</td>
                <td class="px-6 py-3">${formatRupiah(hargaE)}</td>
                <td class="px-6 py-3 font-semibold">${formatRupiah(total)}</td>
                <td class="px-6 py-3 text-center">
                    <button class="hapus bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition text-sm">
                        🗑️ Hapus
                    </button>
                </td>
            `;
            tbody.appendChild(tr);

            // Kurangi stok di dropdown
            stokKardus -= jumlahKardus;
            stokEcer -= jumlahEcer;
            opt.dataset.stokKardus = stokKardus;
            opt.dataset.stokEcer = stokEcer;
            updateOptionText(opt, stokKardus, stokEcer);

            updateTotal();

            // Reset form
            selectBarang.value = "";
            jumlahKardusInput.value = "";
            jumlahEcerInput.value = "";
            maxKardus.textContent = '';
            maxEcer.textContent = '';
            jumlahKardusInput.disabled = true;
            jumlahEcerInput.disabled = true;

            showToast('Berhasil', `${nama} ditambahkan ke keranjang`, 'success');
            selectBarang.focus();
        });

        // ========== HAPUS BARANG ==========
        tbody.addEventListener("click", e => {
            if (e.target.classList.contains("hapus") || e.target.closest('.hapus')) {
                const btn = e.target.classList.contains("hapus") ? e.target : e.target.closest('.hapus');
                const tr = btn.closest("tr");
                const idBarang = tr.children[0].dataset.idBarang;
                const namaBarang = tr.children[0].innerText;
                const jumlahKardus = parseInt(tr.children[1].innerText) || 0;
                const jumlahEcer = parseInt(tr.children[3].innerText) || 0;

                // Kembalikan stok ke dropdown
                const opt = Array.from(selectBarang.options).find(o => o.value === idBarang);
                if (opt) {
                    let stokKardus = parseInt(opt.dataset.stokKardus || 0) + jumlahKardus;
                    let stokEcer = parseInt(opt.dataset.stokEcer || 0) + jumlahEcer;
                    opt.dataset.stokKardus = stokKardus;
                    opt.dataset.stokEcer = stokEcer;
                    updateOptionText(opt, stokKardus, stokEcer);
                }

                tr.remove();
                
                // Show empty row if no items
                const remainingRows = tbody.querySelectorAll("tr:not(#emptyRow)");
                if (remainingRows.length === 0) {
                    emptyRow.classList.remove('hidden');
                }

                updateTotal();
                showToast('Dihapus', `${namaBarang} dihapus dari keranjang`, 'info');
            }
        });

        // ========== BAYAR INPUT ==========
        bayarInput.addEventListener("input", updateTotal);

        // ========== COLLECT DATA ==========
        function collectBarangData() {
            let data = [];
            tbody.querySelectorAll("tr:not(#emptyRow)").forEach(tr => {
                const cells = tr.children;
                data.push({
                    id_barang: cells[0].dataset.idBarang,
                    jumlah_kardus: parseInt(cells[1].innerText) || 0,
                    harga_kardus: parseInt(cells[2].innerText.replace(/\D/g, "")) || 0,
                    jumlah_ecer: parseInt(cells[3].innerText) || 0,
                    harga_ecer: parseInt(cells[4].innerText.replace(/\D/g, "")) || 0,
                    subtotal: parseInt(cells[5].innerText.replace(/\D/g, "")) || 0
                });
            });
            return data;
        }

        function getCurrentSubtotal() {
            let subtotal = 0;
            tbody.querySelectorAll("tr:not(#emptyRow)").forEach(tr => {
                const cells = tr.children;
                if (cells[5]) {
                    const cellVal = cells[5].innerText.replace(/\D/g, "");
                    subtotal += parseInt(cellVal) || 0;
                }
            });
            return subtotal;
        }

        // ========== CONFIRM MODAL ==========
        function showConfirmModal() {
            const subtotal = getCurrentSubtotal();
            if (subtotal <= 0) {
                showToast('Peringatan', 'Keranjang masih kosong', 'warning');
                return;
            }

            const totalBayar = parseInt(totalDisplayHeader.innerText.replace(/\D/g, "")) || 0;
            const bayar = parseInt(bayarInput.value || 0);
            const kembali = bayar - totalBayar;

            if (bayar < totalBayar) {
                showToast('Error', 'Jumlah bayar kurang dari total', 'error');
                return;
            }

            const barangData = collectBarangData();
            const itemCount = barangData.length;

            const confirmContent = document.getElementById('confirmContent');
            confirmContent.innerHTML = `
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="font-semibold">Total Item:</span>
                        <span>${itemCount} barang</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold">Total Bayar:</span>
                        <span>${formatRupiah(totalBayar)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold">Uang Diterima:</span>
                        <span>${formatRupiah(bayar)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold">Kembalian:</span>
                        <span class="text-green-600 font-bold">${formatRupiah(kembali)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold">Metode:</span>
                        <span class="font-semibold">${metode.value === 'Tunai' ? '💵 Tunai' : '📱 QRIS'}</span>
                    </div>
                </div>
            `;

            // Store transaction data for confirmation
            transactionData = {
                total_harga: subtotal,
                total_bayar: bayar,
                metode: metode.value,
                barang: barangData
            };

            console.log('Transaction data prepared:', transactionData);
            document.getElementById('confirmModal').classList.remove('hidden');
        }

   function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    // JANGAN reset transactionData di sini!
    // transactionData = null;
}

// ========== CONFIRM TRANSACTION ==========
function confirmTransaction() {
    // JANGAN tutup modal dulu!
    // closeConfirmModal();
    
    if (!transactionData) {
        showToast('Error', 'Data transaksi tidak valid', 'error');
        closeConfirmModal();
        return;
    }

    showLoading();

    // Log data yang akan dikirim
    console.log('Sending transaction data:', transactionData);

    fetch("{{ route('kasir.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify(transactionData)
    })
    .then(async response => {
        console.log('Response status:', response.status);
        const data = await response.json();
        console.log('Response data:', data);
        
        if (!response.ok) {
            throw new Error(data.message || 'Server error');
        }
        
        return data;
    })
    .then(res => {
        hideLoading();
        closeConfirmModal(); // ✅ Tutup modal SETELAH sukses
        // transactionData = null; // ✅ Reset SETELAH proses selesai
        
        if (res.success) {
            console.log('Transaction success:', res);
            showPrintModal(res.transaksi);
        } else {
            showToast('Error', res.message || 'Gagal menyimpan transaksi', 'error');
            console.error('Transaction failed:', res);
        }
    })
    .catch(err => {
        hideLoading();
        closeConfirmModal(); // ✅ Tutup modal saat error
        console.error('Fetch error:', err);
        showToast('Error', 'Terjadi kesalahan: ' + err.message, 'error');
    });
}
        // ========== PRINT MODAL ==========
        function showPrintModal(transaksi) {
            document.getElementById('transaksiId').textContent = transaksi.id_transaksi || 'N/A';
            
            const strukContent = document.getElementById('strukContent');
            const now = new Date();
            const tanggal = now.toLocaleDateString('id-ID', { 
                day: '2-digit', 
                month: 'long', 
                year: 'numeric' 
            });
            const waktu = now.toLocaleTimeString('id-ID');

            let itemsHTML = '';
            transactionData.barang.forEach(item => {
                const opt = Array.from(selectBarang.options).find(o => o.value == item.id_barang);
                const namaBarang = opt ? opt.dataset.nama : 'Unknown';
                itemsHTML += `
                    <div class="flex justify-between text-xs mb-1">
                        <span>${namaBarang}</span>
                        <span>${formatRupiah(item.subtotal)}</span>
                    </div>
                    <div class="text-xs text-gray-500 mb-2 pl-2">
                        ${item.jumlah_kardus > 0 ? `${item.jumlah_kardus} Kardus × ${formatRupiah(item.harga_kardus)}` : ''}
                        ${item.jumlah_kardus > 0 && item.jumlah_ecer > 0 ? ' + ' : ''}
                        ${item.jumlah_ecer > 0 ? `${item.jumlah_ecer} Ecer × ${formatRupiah(item.harga_ecer)}` : ''}
                    </div>
                `;
            });

            const totalBayar = parseInt(totalDisplayHeader.innerText.replace(/\D/g, "")) || 0;
            const kembali = transactionData.total_bayar - totalBayar;

            strukContent.innerHTML = `
                <div class="text-center mb-4">
                    <h4 class="font-bold text-lg">{{ config('app.name', 'TOKO ANDA') }}</h4>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Alamat Toko Anda</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Telp: 0812-xxxx-xxxx</p>
                </div>
                
                <div class="border-t border-b border-gray-300 dark:border-gray-600 py-2 mb-2 text-xs">
                    <div class="flex justify-between">
                        <span>Tanggal:</span>
                        <span>${tanggal}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Waktu:</span>
                        <span>${waktu}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Kasir:</span>
                        <span>{{ Auth::user()->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>No. Transaksi:</span>
                        <span>${transaksi.id_transaksi || 'N/A'}</span>
                    </div>
                </div>

                <div class="mb-2">
                    ${itemsHTML}
                </div>

                <div class="border-t border-gray-300 dark:border-gray-600 pt-2">
                    <div class="flex justify-between text-sm mb-1">
                        <span>Subtotal:</span>
                        <span>${formatRupiah(transactionData.total_harga)}</span>
                    </div>
                    ${metode.value === 'Qris' ? `
                    <div class="flex justify-between text-xs mb-1">
                        <span>Biaya Admin:</span>
                        <span>${formatRupiah(1000)}</span>
                    </div>
                    ` : ''}
                    <div class="flex justify-between font-bold text-lg mb-2">
                        <span>TOTAL:</span>
                        <span>${formatRupiah(totalBayar)}</span>
                    </div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Bayar (${metode.value}):</span>
                        <span>${formatRupiah(transactionData.total_bayar)}</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span>Kembali:</span>
                        <span>${formatRupiah(kembali)}</span>
                    </div>
                </div>

                <div class="text-center mt-4 pt-2 border-t border-gray-300 dark:border-gray-600">
                    <p class="text-xs text-gray-600 dark:text-gray-400">Terima kasih atas kunjungan Anda</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Barang yang sudah dibeli tidak dapat dikembalikan</p>
                </div>
            `;

            document.getElementById('printModal').classList.remove('hidden');
        }

        function closePrintModal() {
            document.getElementById('printModal').classList.add('hidden');
            window.location.reload();
        }

        function printStruk() {
            const strukContent = document.getElementById('strukContent').innerHTML;
            const printWindow = window.open('', '', 'width=300,height=600');
            
            printWindow.document.write(`
                <html>
                <head>
                    <title>Struk Transaksi</title>
                    <style>
                        body { 
                            font-family: 'Courier New', monospace; 
                            font-size: 12px; 
                            padding: 10px;
                            max-width: 300px;
                        }
                        @media print {
                            body { margin: 0; }
                        }
                    </style>
                </head>
                <body>
                    ${strukContent}
                </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.focus();
            
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        }

        // ========== BUTTON SIMPAN ==========
        btnSimpan.addEventListener("click", showConfirmModal);

        // ========== INITIALIZATION ==========
        (function init() {
            console.log('Initializing Kasir page...');
            totalDisplayHeader.innerText = formatRupiah(0);
            kembaliDisplayHeader.innerText = formatRupiah(0);
            bayarInput.disabled = false;
            clearQRCode();
            btnSimpan.disabled = true;
            
            // Disable inputs initially
            jumlahKardusInput.disabled = true;
            jumlahEcerInput.disabled = true;
            
            console.log('Kasir page initialized successfully');
        })();
    </script>
</x-app-layout>